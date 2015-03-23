using System.Collections.Generic;
using System.Web.Http;
using IBCServer.Models;
namespace IBCServer.Controllers.APIControllers
{
    public class LeagueController : ApiController
    {
        public IEnumerable<League> Get()
        {
            return Get(null, null);
        }
        public IEnumerable<League> Get(int? id)
        {
            return Get(id, null);
        }
        public IEnumerable<League> Get(int? id, int? total)
        {
            IEnumerable<League> ret = null;

            // this is bad code due to fanex data
            dynamic parameters = new { };
            if (id != null && total != null)
            {
                parameters = new { LeagueId = id, Total = total };
            }
            else if (id != null && total == null)
            {
                parameters = new { LeagueId = id };
            }
            if (id == null && total != null)
            {
                parameters = new { Total = total };
            }
            // end bad code

            using (var obj = new Fanex.Data.ObjectDb("GetNewLeagues"))
            {
                ret = obj.Query<League>(parameters);
            }

            return ret;

            //SqlHelper sqlHelper = new SqlHelper();
            //DataTable leaguesTable =
            //    sqlHelper.ExecuteStoredProcedure("Ost_League_GetNewerLeagues",
            //    new string[] { "@LeagueId", "@Total" },
            //    new object[] { id, total }
            //);

            //IEnumerable<League> ret = leaguesTable.AsEnumerable().Select(
            //    x => new League()
            //    {
            //        LeagueID = x.Field<int>("leagueid"),
            //        LeagueName = x.Field<string>("leaguename"),
            //        SportType = x.Field<byte>("sporttype")
            //    });
            //return ret;
        }
    }
}
