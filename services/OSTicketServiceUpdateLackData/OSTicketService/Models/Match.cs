using System;

namespace IBCServer.Models
{
    public class Match
    {
        /*
        public League League { get; set; }
        public Team HomeTeam { get; set; }
        public Team AwayTeam { get; set; }
        public Sport Sport { get; set; }
        */

        public int MatchID { get; set; }
        public int LeagueID { get; set; }
        public int HomeID { get; set; }
        public int AwayID { get; set; }
        public DateTime EventDate { get; set; }
        public string MatchCode { get; set; }
        public int SportType { get; set; }
        public DateTime KickOffTime { get; set; }
        //public byte Status { get; set; }
        //public DateTime Updated { get; set; }
    }
}