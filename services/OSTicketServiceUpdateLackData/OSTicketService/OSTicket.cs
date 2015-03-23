using System;
using System.Configuration;
using System.IO;
using System.ServiceProcess;
using System.Timers;
using log4net;
// using Fanex.Logging;

namespace OSTicketService
{
    public partial class OSTicket : ServiceBase
    {
        private static readonly ILog LOG = LogManager.GetLogger(System.Reflection.MethodBase.GetCurrentMethod().DeclaringType);

        /// <summary>
        /// Default records per action (300 records)
        /// </summary>
        private const int DEFAULT_RECORDS_PER_ACTION = 300;

        /// <summary>
        /// Default interval for tmrTicker is 30 seconds
        /// </summary>
        private const int DEFAULT_TIMER_INTERVAL = 30;

        /// <summary>
        /// Default interval for refreshing is 300 seconds (5 minutes)
        /// </summary>
        private const int DEFAULT_REFRESHING_INTERVAL = 300;

        public int RecordsPerAction { get; private set; }
        public int TimerInterval { get; private set; }
        public int RefreshingInterval { get; private set; }

        private DateTime NextRefreshingTime;
        private Timer myTimer = null;
        private StreamWriter myLogWriter = null;

        OSTicketDBHelper myOSTicketHelper;

        private int latestTeamId = 228962;

        public OSTicket()
        {
            InitializeComponent();

            int recordsPerAction = DEFAULT_RECORDS_PER_ACTION;
            int timerInterval = DEFAULT_TIMER_INTERVAL;
            int refreshingInterval = DEFAULT_REFRESHING_INTERVAL;

            int.TryParse(ConfigurationManager.AppSettings["RecordsPerAction"], out recordsPerAction);
            int.TryParse(ConfigurationManager.AppSettings["TimerInterval"], out timerInterval);
            int.TryParse(ConfigurationManager.AppSettings["RefreshInterval"], out refreshingInterval);

            myOSTicketHelper = new OSTicketDBHelper();

            RecordsPerAction = recordsPerAction;
            RefreshingInterval = refreshingInterval;
            TimerInterval = timerInterval;

            myTimer = new Timer(TimerInterval * 1000);
            myTimer.Elapsed += new ElapsedEventHandler(checkForRefreshing);
            myTimer.Enabled = false;


        }

        protected override void OnStart(string[] args)
        {
            LOG.Info("Service started at " + DateTime.Now.ToString());
            // Logger.Log.Info("Service started at " + DateTime.Now.ToString());

            NextRefreshingTime = DateTime.Now;

            // refreshing for the first time
            //checkForRefreshing(this, null);

            myTimer.Enabled = true;
        }

        protected override void OnStop()
        {
            myTimer.Enabled = false;

            LOG.Info("Service stopped at " + DateTime.Now.ToString());
            // Logger.Log.Info("Service stopped at " + DateTime.Now.ToString());
            myLogWriter.Close();
        }

        private void checkForRefreshing(object sender, ElapsedEventArgs e)
        {
            if (DateTime.Now >= NextRefreshingTime)
            {
                LOG.Info("Check for new record(s) at " + DateTime.Now.ToString());
                // Logger.Log.Info("Check for new record(s) at " + DateTime.Now.ToString());

                myOSTicketHelper.ImportDataToOSTicketDB(RecordsPerAction, latestTeamId);

                NextRefreshingTime = DateTime.Now.AddSeconds(RefreshingInterval);
            }
        }
    }
}
