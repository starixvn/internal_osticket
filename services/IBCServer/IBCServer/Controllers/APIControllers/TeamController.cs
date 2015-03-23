using System.Collections;
using System.Collections.Generic;
using System.Web.Http;
using IBCServer.Models;
namespace IBCServer.Controllers.APIControllers
{
    public class TeamController : ApiController
    {
        public IEnumerable<Team> Get()
        {
            return Get(null, null);
        }
        public IEnumerable<Team> Get(int? id)
        {
            return Get(id, null);
        }
        public IEnumerable<Team> Get(int? id, int? total)
        {
            IEnumerable<Team> ret = null;

            // this is bad code due to fanex data
            dynamic parameters = new { };
            if (id != null && total != null)
            {
                parameters = new { TeamId = id, Total = total };
            }
            else if (id != null && total == null)
            {
                parameters = new { TeamId = id };
            }
            if (id == null && total != null)
            {
                parameters = new { Total = total };
            }
            // end bad code

            using (var obj = new Fanex.Data.ObjectDb("GetNewTeams"))
            {
                ret = obj.Query<Team>(parameters);
            }

            return ret;

            //SqlHelper sqlHelper = new SqlHelper();
            //DataTable leaguesTable = sqlHelper.ExecuteStoredProcedure("Ost_Team_GetNewerTeams",
            //    new string[] { "@TeamId", "@Total" },
            //    new object[] { id, total }
            //);

            //IEnumerable<Team> ret = leaguesTable.AsEnumerable().Select(
            //    x => new Team()
            //    {
            //        TeamID = x.Field<int>("teamid"),
            //        TeamName = x.Field<string>("teamname"),
            //        SportType = x.Field<byte>("sporttype")
            //    });
            //return ret;
        }
    }
}
