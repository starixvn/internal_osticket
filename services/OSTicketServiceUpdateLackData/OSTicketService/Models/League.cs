using System;

namespace IBCServer.Models
{
    public class League
    {
        //public Sport Sport { get; set; }

        public int LeagueID { get; set; }
        public string LeagueName { get; set; }
        public byte SportType { get; set; }
        //public byte Status { get; set; }
        //public DateTime Updated { get; set; }
    }
}