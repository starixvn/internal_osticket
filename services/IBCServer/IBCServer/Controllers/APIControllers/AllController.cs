using System.Web.Http;
using IBCServer.Models;

namespace IBCServer.Controllers.APIControllers
{
    public class AllController : ApiController
    {
        public All Get()
        {
            return new All()
            {
                Leagues = new LeagueController().Get(),
                Matches = new MatchController().Get(),
                Teams = new TeamController().Get(),
                Sports = new SportController().Get()
            };
        }

        public All Get(int? leagueId, int? matchId, int? teamId, int? total)
        {
            return new All()
            {
                Leagues = new LeagueController().Get(leagueId, total),
                Matches = new MatchController().Get(matchId, total),
                Teams = new TeamController().Get(teamId, total),
                Sports = new SportController().Get()
            };
        }
    }
}
