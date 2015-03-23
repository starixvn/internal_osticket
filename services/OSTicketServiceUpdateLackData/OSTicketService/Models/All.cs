using System.Collections.Generic;

namespace IBCServer.Models
{
    public class All
    {
        public IEnumerable<League> Leagues { get; set; }
        public IEnumerable<Match> Matches { get; set; }
        public IEnumerable<Sport> Sports { get; set; }
        public IEnumerable<Team> Teams { get; set; }
    }
}
