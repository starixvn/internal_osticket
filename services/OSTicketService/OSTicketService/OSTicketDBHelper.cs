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
        const string SQL_NEW_MATCH = "ost_newmatch";
        const string SQL_GET_MAX_LEAGUE_ID = "ost_getmaxleagueid";
        const string SQL_GET_MAX_MATCH_ID = "ost_getmaxmatchid";
        const string SQL_GET_MAX_TEAM_ID = "ost_getmaxteamid";

        private static readonly ILog LOG = LogManager.GetLogger(System.Reflection.MethodBase.GetCurrentMethod().DeclaringType);
        private static readonly string DEFAULT_IBCDB_SERVICE = ConfigurationManager.AppSettings["IBCDBService"];
        private static readonly string DEFAULT_CONNECTION_STRING = ConfigurationManager.AppSettings["OSTicketConnectionString"];


        public string ConnectionString { get; private set; }
        public string IBCDBService { get; private set; }

        public int LatestLeagueId { get; private set; }
        public int LatestMatchId { get; private set; }
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
        /// Fetchs all latest ids from OSTicket database
        /// </summary>
        private void FetchLatestIDs()
        {
            int latestTeamId = 0, latestLeagueId = 0, latestMachId = 0;

            try
            {
                MySqlConnection sc = new MySqlConnection(ConnectionString);
                sc.Open();

                MySqlCommand cmd;
                object result;

                cmd = new MySqlCommand(SQL_GET_MAX_LEAGUE_ID, sc) { CommandType = CommandType.StoredProcedure };
                result = cmd.ExecuteScalar();
                latestLeagueId = result == DBNull.Value ? 0 : Convert.ToInt32(result);

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
            finally
            {
                LatestLeagueId = latestLeagueId;
                LatestMatchId = latestMachId;
                LatestTeamId = latestTeamId;
            }
        }

        /// <summary>
        /// Get newer data in IBC DB
        /// </summary>
        /// <param name="total">Max number of records for importing</param>
        public All FetchNewerDataFromIBCDB(int total)
        {
            FetchLatestIDs();

            // CAUTION: params must be in this order: League, Match, Team
            string serviceURL = string.Format(IBCDBService, LatestLeagueId, LatestMatchId, LatestTeamId, total);
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
        public void ImportDataToOSTicketDB(int total)
        {
            All newerData = FetchNewerDataFromIBCDB(total);

            if (newerData == null)
            {
                LOG.Error("Nothing to import (newerData == null)");
                // Logger.Log.Error("Nothing to import (newerData == null)");
                return;
            }

            LOG.Info("Start importing data...");
            // Logger.Log.Info("Start importing data...");

            try
            {
                MySqlConnection sc = new MySqlConnection(ConnectionString);
                MySqlCommand cmd;
                int ret = 0;
                sc.Open();

                #region import sport table

                LOG.Info("Importing sport table...");
                // Logger.Log.Info("Importing sport table...");

                try
                {
                    cmd = new MySqlCommand(SQL_NEW_SPORT, sc) { CommandType = CommandType.StoredProcedure };
                    cmd.Parameters.Add("@sport_name", MySqlDbType.VarChar, 255);
                    cmd.Parameters.Add("@sport_type", MySqlDbType.Int32);
                    foreach (Sport s in newerData.Sports)
                    {
                        cmd.Parameters["@sport_type"].Value = s.SportType;
                        cmd.Parameters["@sport_name"].Value = s.SportName;

                        ret = cmd.ExecuteNonQuery();

                        LOG.Info(string.Format((ret == 0 ? "Did not import existed " : "Imported") + " SPORT({0},{1})", s.SportType, s.SportName));
                        // Logger.Log.Info(string.Format((ret == 0 ? "Did not import existed " : "Imported") + " SPORT({0},{1})", s.SportType, s.SportName));
                    }
                }
                catch (Exception ex)
                {
                    LOG.Error("Error while importing sport table: " + ex.Message, ex);
                    // Logger.Log.Error("Error while importing sport table: " + ex.Message, ex);
                }

                #endregion

                #region import League table

                LOG.Info("Importing League table...");
                // Logger.Log.Info("Importing League table...");

                try
                {
                    cmd = new MySqlCommand(SQL_NEW_LEAGUE, sc) { CommandType = CommandType.StoredProcedure };
                    cmd.Parameters.Add("@league_id", MySqlDbType.Int32);
                    cmd.Parameters.Add("@league_name", MySqlDbType.VarChar, 255);
                    cmd.Parameters.Add("@sport_type", MySqlDbType.Int32);
                    foreach (League l in newerData.Leagues)
                    {
                        cmd.Parameters["@league_id"].Value = l.LeagueID;
                        cmd.Parameters["@league_name"].Value = l.LeagueName;
                        cmd.Parameters["@sport_type"].Value = l.SportType;

                        ret = cmd.ExecuteNonQuery();

                        LOG.Info(string.Format((ret == 0 ? "Did not import existed " : "Imported") + " LEAGUE({0},{1},{2})", l.LeagueID, l.LeagueName, l.SportType));
                        // Logger.Log.Info(string.Format((ret == 0 ? "Did not import existed " : "Imported") + " LEAGUE({0},{1},{2})", l.LeagueID, l.LeagueName, l.SportType));
                    }
                }
                catch (Exception ex)
                {
                    LOG.Error("Error while importing league table: " + ex.Message, ex);
                    // Logger.Log.Error("Error while importing league table: " + ex.Message, ex);
                }

                #endregion

                #region import Team table

                LOG.Info("Importing League table...");
                // Logger.Log.Info("Importing League table...");

                try
                {
                    cmd = new MySqlCommand(SQL_NEW_TEAM, sc) { CommandType = CommandType.StoredProcedure };
                    cmd.Parameters.Add("@team_id", MySqlDbType.Int32);
                    cmd.Parameters.Add("@team_name", MySqlDbType.VarChar, 255);
                    cmd.Parameters.Add("@sport_type", MySqlDbType.Int32);
                    foreach (Team t in newerData.Teams)
                    {
                        cmd.Parameters["@team_id"].Value = t.TeamID;
                        cmd.Parameters["@team_name"].Value = t.TeamName;
                        cmd.Parameters["@sport_type"].Value = t.SportType;

                        ret = cmd.ExecuteNonQuery();

                        LOG.Info(string.Format((ret == 0 ? "Did not import existed " : "Imported") + " TEAM({0},{1},{2})", t.TeamID, t.TeamName, t.SportType));
                        // Logger.Log.Info(string.Format((ret == 0 ? "Did not import existed " : "Imported") + " TEAM({0},{1},{2})", t.TeamID, t.TeamName, t.SportType));
                    }
                }
                catch (Exception ex)
                {
                    LOG.Error("Error while importing team table: " + ex.Message, ex);
                    // Logger.Log.Error("Error while importing team table: " + ex.Message, ex);
                }

                #endregion

                #region import Match table

                LOG.Info("Importing Match table...");
                // Logger.Log.Info("Importing Match table...");

                try
                {
                    cmd = new MySqlCommand(SQL_NEW_MATCH, sc) { CommandType = CommandType.StoredProcedure };
                    cmd.Parameters.Add("@match_id", MySqlDbType.Int32);
                    cmd.Parameters.Add("@league_id", MySqlDbType.Int32);
                    cmd.Parameters.Add("@home_id", MySqlDbType.Int32);
                    cmd.Parameters.Add("@away_id", MySqlDbType.Int32);
                    cmd.Parameters.Add("@event_date", MySqlDbType.Date);
                    cmd.Parameters.Add("@match_code", MySqlDbType.VarChar, 255);
                    cmd.Parameters.Add("@sport_type", MySqlDbType.Int32);
                    cmd.Parameters.Add("@kickofftime", MySqlDbType.DateTime);

                    foreach (Match m in newerData.Matches)
                    {
                        cmd.Parameters["@match_id"].Value = m.MatchID;
                        cmd.Parameters["@league_id"].Value = m.LeagueID;
                        cmd.Parameters["@home_id"].Value = m.HomeID;
                        cmd.Parameters["@away_id"].Value = m.AwayID;
                        cmd.Parameters["@event_date"].Value = m.EventDate;
                        cmd.Parameters["@match_code"].Value = m.MatchCode;
                        cmd.Parameters["@sport_type"].Value = m.SportType;
                        cmd.Parameters["@kickofftime"].Value = m.KickOffTime;

                        ret = cmd.ExecuteNonQuery();

                        LOG.Info(string.Format((ret == 0 ? "Did not import existed " : "Imported") + " MATCH({0},{1},{2},{4},{5},{6})",
                            m.MatchID, m.LeagueID, m.HomeID, m.AwayID, m.EventDate, m.MatchCode, m.SportType));
                        // Logger.Log.Info(string.Format((ret == 0 ? "Did not import existed " : "Imported") + " MATCH({0},{1},{2},{4},{5},{6})",
                        //    m.MatchID, m.LeagueID, m.HomeID, m.AwayID, m.EventDate, m.MatchCode, m.SportType));
                    }
                }
                catch (Exception ex)
                {
                    LOG.Error("Error while importing match table: " + ex.Message, ex);
                    // Logger.Log.Error("Error while importing match table: " + ex.Message, ex);
                }

                #endregion

                sc.Close();
            }
            catch (Exception ex)
            {
                LOG.Error("Error while importing data from IBC DB to OsTicket DB: " + ex.Message, ex);
                // Logger.Log.Error("Error while importing data from IBC DB to OsTicket DB: " + ex.Message, ex);
            }

            LOG.Info("Finished importing data from IBC DB to OsTicket DB.");
            // Logger.Log.Info("Finished importing data from IBC DB to OsTicket DB.");
        }
    }
}
