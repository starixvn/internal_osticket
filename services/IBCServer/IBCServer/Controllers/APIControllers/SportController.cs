using System.Collections.Generic;
using System.Web.Http;
using IBCServer.Models;
namespace IBCServer.Controllers.APIControllers
{
    public class SportController : ApiController
    {
        public IEnumerable<Sport> Get()
        {
            IEnumerable<Sport> ret = null;

            using (var obj = new Fanex.Data.ObjectDb("GetAllSports"))
            {
                ret = obj.Query<Sport>();
            }

            return ret;

            //SqlHelper sqlHelper = new SqlHelper();
            //DataTable leaguesTable = sqlHelper.ExecuteStoredProcedure("Ost_Sport_GetAll");

            //IEnumerable<Sport> ret = leaguesTable.AsEnumerable().Select(
            //    x => new Sport()
            //    {
            //        SportName = x.Field<string>("sportname"),
            //        SportType = x.Field<byte>("sporttype")
            //    });
            //return ret;
        }
    }
}
