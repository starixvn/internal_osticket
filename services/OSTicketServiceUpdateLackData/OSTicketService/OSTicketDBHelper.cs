using System;
using System.Configuration;
using System.Data;
using System.IO;
using System.Net;
using System.Web.Script.Serialization;
using IBCServer.Models;
using log4net;
using MySql.Data.MySqlClient;
//using Fanex.Logging;
using System.Linq;

namespace OSTicketService
{
    public class OSTicketDBHelper
    {
        // sql query
        //const string SQL_NEW_SPORT = "insert ignore into ost_sport(sport_type, sport_name) values(@sport_type, @sport_name)";
        //const string SQL_NEW_TEAM = "insert ignore into ibc_team(team_id, team_name, sport_type) values(@team_id, @team_name, @sport_type)";
        //const string SQL_NEW_LEAGUE = "insert ignore into ibc_league(league_id, league_name, sport_type) values(@league_id, @league_name, @sport_type)";
        //const string SQL_NEW_MATCH = "insert ignore into ibc_match(match_id, league_id, home_id, away_id, event_date, match_code, sport_type) values(@match_id, @league_id, @home_id, @away_id, @event_date, @match_code, @sport_type)";

        const string SQL_NEW_SPORT = "ost_newsport";
        const string SQL_NEW_TEAM = "ost_newteam";
        const string SQL_NEW_LEAGUE = "ost_newleague";
        const string SQL_UPDATE_MATCH = "ost_updateibcmatch";
        const string SQL_GET_MAX_LEAGUE_ID = "ost_getmaxleagueid";
        const string SQL_GET_MAX_MATCH_ID = "ost_getmaxmatchid";
        const string SQL_GET_MAX_TEAM_ID = "ost_getmaxteamid";

        private static readonly ILog LOG = LogManager.GetLogger(System.Reflection.MethodBase.GetCurrentMethod().DeclaringType);
        private static readonly string DEFAULT_IBCDB_SERVICE = ConfigurationManager.AppSettings["IBCDBService"];
        private static readonly string DEFAULT_CONNECTION_STRING = ConfigurationManager.AppSettings["OSTicketConnectionString"];


        public string ConnectionString { get; private set; }
        public string IBCDBService { get; private set; }

        public int LatestTeamId { get; private set; }

        public OSTicketDBHelper()
        {
            ConnectionString = DEFAULT_CONNECTION_STRING;
            IBCDBService = DEFAULT_IBCDB_SERVICE;
        }

        public OSTicketDBHelper(string connectionString, string ibcDBServiceLink)
        {
            ConnectionString = connectionString;
            IBCDBService = ibcDBServiceLink;
        }

        /// <summary>
        /// Fetches all latest ids from OSTicket database
        /// </summary>
        public void FetchLatestIDs(ref int latestTeamId, ref int latestMachId)
        {
            try
            {
                MySqlConnection sc = new MySqlConnection(ConnectionString);
                sc.Open();

                MySqlCommand cmd;
                object result;

                cmd = new MySqlCommand(SQL_GET_MAX_MATCH_ID, sc) { CommandType = CommandType.StoredProcedure };
                result = cmd.ExecuteScalar();
                latestMachId = result == DBNull.Value ? 0 : Convert.ToInt32(result);

                cmd = new MySqlCommand(SQL_GET_MAX_TEAM_ID, sc) { CommandType = CommandType.StoredProcedure };
                result = cmd.ExecuteScalar();
                latestTeamId = result == DBNull.Value ? 0 : Convert.ToInt32(result);

                sc.Close();
            }
            catch (Exception ex)
            {
                LOG.Error("Error while fetching latest IDs from OsTicket DB: " + ex.Message, ex);
                // Logger.Log.Error("Error while fetching latest IDs from OsTicket DB: " + ex.Message, ex);
            }
        }

        /// <summary>
        /// Get newer data in IBC DB
        /// </summary>
        /// <param name="total">Max number of records for importing</param>
        public All FetchNewerDataFromIBCDB(int total, int matchId)
        {
            // CAUTION: params must be in this order: League, Match, Team
            string serviceURL = string.Format(IBCDBService, 999999, 999999999, LatestTeamId, total);
            LOG.Info("Start fetching IBC Data from URL: " + serviceURL);
            // Logger.Log.Info("Start fetching IBC Data from URL: " + serviceURL);

            All ret = null;

            try
            {
                // fetch json result from IBC web api
                WebRequest request = WebRequest.Create(serviceURL);
                request.ContentType = "application/json";
                WebResponse response = request.GetResponse();
                StreamReader reader = new StreamReader(response.GetResponseStream());
                string jsonResult = reader.ReadToEnd();

                // deserializer jsonResult to object
                JavaScriptSerializer serializer = new JavaScriptSerializer();
                serializer.MaxJsonLength = int.MaxValue;
                ret = serializer.Deserialize<All>(jsonResult);

                var objectTeam = ret.Teams.OrderByDescending(u => u.TeamID).FirstOrDefault();
                if (objectTeam != null)
                {
                    LatestTeamId = objectTeam.TeamID;
                }
                else
                {
                    LOG.Info("No team.");
                }
            }
            catch (Exception ex)
            {
                LOG.Error("Error while fetching data from IBC DB: " + ex.Message, ex);
                // Logger.Log.Error("Error while fetching data from IBC DB: " + ex.Message, ex);
            }

            LOG.Info("Finished fetching IBC Data.");
            // Logger.Log.Info("Finished fetching IBC Data.");
            return ret;
        }

        /// <summary>
        /// import new data in IBC into osTicket DB
        /// </summary>
        /// <param name="total">Max number of records for importing</param>
        /// <returns>log during importing session</returns>
        public void ImportDataToOSTicketDB(int total, int teamId)
        {
            All newerData = FetchNewerDataFromIBCDB(total, LatestTeamId);

            if (teamId > LatestTeamId)
            {

                if (newerData == null)
                {
                    LOG.Error("Nothing to import (newerData == null)");
                    return;
                }

                LOG.Info("Start importing data...");

                try
                {
                    MySqlConnection sc = new MySqlConnection(ConnectionString);
                    MySqlCommand cmd;
                    int ret = 0;
                    sc.Open();

                    #region import Team table

                    LOG.Info("Importing team table...");
                    // Logger.Log.Info("Importing League table...");

                    try
                    {
                        cmd = new MySqlCommand(SQL_NEW_TEAM, sc) { CommandType = CommandType.StoredProcedure };
                        cmd.Parameters.Add("@team_id", MySqlDbType.Int32);
                        cmd.Parameters.Add("@team_name", MySqlDbType.VarChar, 255);
                        cmd.Parameters.Add("@sport_type", MySqlDbType.Int32);
                        cmd.CommandTimeout = 0;

                        foreach (Team t in newerData.Teams)
                        {
                            cmd.Parameters["@team_id"].Value = t.TeamID;
                            cmd.Parameters["@team_name"].Value = t.TeamName;
                            cmd.Parameters["@sport_type"].Value = t.SportType;

                            try
                            {
                                ret = cmd.ExecuteNonQuery();

                                LOG.Info(string.Format((ret == 0 ? "Did not import existed " : "Imported") + " TEAM({0},{1},{2})", t.TeamID, t.TeamName, t.SportType));
                                // Logger.Log.Info(string.Format((ret == 0 ? "Did not import existed " : "Imported") + " TEAM({0},{1},{2})", t.TeamID, t.TeamName, t.SportType));
                            }
                            catch
                            {
                                LOG.Info(string.Format("Log Error TEAM({0},{1},{2})", t.TeamID, t.TeamName, t.SportType));
                            }
                        }
                    }
                    catch (Exception ex)
                    {
                        LOG.Error("Error while importing team table: " + ex.Message, ex);
                        // Logger.Log.Error("Error while importing team table: " + ex.Message, ex);
                    }

                    #endregion

                    sc.Close();
                }
                catch (Exception ex)
                {
                    LOG.Error("Error while importing data from IBC DB to OsTicket DB: " + ex.Message, ex);
                }

                LOG.Info("Finished importing data from IBC DB to OsTicket DB.");
            }
        }
    }
}
