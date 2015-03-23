using System.Collections.Generic;
using System.Web.Http;
using IBCServer.Models;
namespace IBCServer.Controllers.APIControllers
{
    public class MatchController : ApiController
    {
        public IEnumerable<Match> Get()
        {
            return Get(null, null);
        }
        public IEnumerable<Match> Get(int? id)
        {
            return Get(id, null);
        }
        public IEnumerable<Match> Get(int? id, int? total)
        {
            IEnumerable<Match> ret = null;

            // this is bad code due to fanex data
            dynamic parameters = new { };
            if (id != null && total != null)
            {
                parameters = new { MatchId = id, Total = total };
            }
            else if (id != null && total == null)
            {
                parameters = new { MatchId = id };
            }
            if (id == null && total != null)
            {
                parameters = new { Total = total };
            }
            // end bad code

            using (var obj = new Fanex.Data.ObjectDb("GetNewMatchs"))
            {
                ret = obj.Query<Match>(parameters);
            }

            return ret;

            //SqlHelper sqlHelper = new SqlHelper();
            //DataTable leaguesTable = sqlHelper.ExecuteStoredProcedure("Ost_Match_GetNewerMatches",
            //    new string[] { "@MatchId", "@Total" },
            //    new object[] { id, total }
            //);

            //IEnumerable<Match> ret = leaguesTable.AsEnumerable().Select(
            //    x => new Match()
            //    {
            //        MatchID = x.Field<int>("matchid"),
            //        LeagueID = x.Field<int>("leagueid"),
            //        HomeID = x.Field<int>("homeid"),
            //        AwayID = x.Field<int>("awayid"),
            //        EventDate = x.Field<DateTime>("eventdate"),
            //        MatchCode = x.Field<string>("matchcode"),
            //        SportType = x.Field<byte>("sporttype")
            //    });
            //return ret;
        }
    }
}
