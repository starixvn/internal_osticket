// --------------------------------------------------------------------------
// <copyright file="IBCDataAccess.cs" company="Nexcel Solutions Vietnam">
// Copyright (c) Nexcel Solutions Vietnam. All rights reserved.
// </copyright>
// --------------------------------------------------------------------------
// <history>
//   <change who="Edward.Nguyen" date="2013.9.16">Created</change>
// </history>

namespace IBCServer.DataAccess
{
    using System.Collections.Generic;
    using IBCServer.Models;
    using Fanex.Data;
    using System;

    public class IBCDataAccess
    {
        private const int TotalItem = 100;

        public IEnumerable<League> GetLeague(int? id, int? total = TotalItem)
        {
            IEnumerable<League> ret = null;

            using (var obj = new ObjectDb("GetNewLeagues"))
            {
                ret = obj.Query<League>(new { LeagueId = id, Total = total });
            }

            return ret;
        }

        public IEnumerable<Match> GetMatch(int? id, int? total = TotalItem)
        {
            IEnumerable<Match> ret = null;

            using (var obj = new ObjectDb("GetNewMatchs"))
            {
                ret = obj.Query<Match>(new { MatchId = id, Total = total });
            }

            return ret;
        }

        public IEnumerable<Sport> GetSport()
        {
            IEnumerable<Sport> ret = null;

            using (var obj = new ObjectDb("GetAllSports"))
            {
                ret = obj.Query<Sport>();
            }

            return ret;
        }

        public IEnumerable<Team> GetTeam(int? id, int? total = TotalItem)
        {
            IEnumerable<Team> ret = null;

            using (var obj = new ObjectDb("GetNewTeams"))
            {
                ret = obj.Query<Team>(new { TeamId = id, Total = total });
            }

            return ret;
        }
    }
}