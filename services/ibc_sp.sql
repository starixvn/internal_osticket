use bodb02
GO

/*	Version		Date			User		Comment
	======================================================
	0.1			Jan-14-2013		Tidas		First Initial
*/
/**
	Get all leagues which are newer than input id.
*/
ALTER PROCEDURE Ost_League_GetNewerLeagues
	@LeagueId int = 0,
	@Total int = 100
AS
SET NOCOUNT ON;

SELECT TOP (@Total) l.leagueid, l.leaguename, l.sporttype
FROM League l WITH (NOLOCK)
WHERE l.leagueid > @LeagueId
	AND l.leagueid > 0
ORDER BY leagueid ASC

GO


/*	Version		Date			User		Comment
	======================================================
	0.1			Jan-14-2013		Tidas		First Initial
*/
/**
	Get all matches which are newer than input id.
*/
ALTER PROCEDURE Ost_Match_GetNewerMatches
	@MatchId int = 0,
	@Total int = 100
AS
SET NOCOUNT ON;

(
	SELECT TOP (@Total) m.matchid, m.leagueid, m.homeid, m.awayid, m.eventdate, m.matchcode, m.sporttype
	FROM Match m WITH (NOLOCK)
	WHERE m.MatchId > @MatchId
		AND m.sporttype > 0 -- exclude outright sporttype
)
/*
UNION
(
	SELECT TOP (@Total) m.matchid, m.leagueid, m.homeid, m.awayid, m.eventdate, m.matchcode, m.sporttype
	FROM Match14 m WITH (NOLOCK)
	WHERE m.MatchId > @MatchId
		AND m.sporttype > 0 -- exclude outright sporttype
)
UNION
(
	SELECT TOP (@Total) m.matchid, m.leagueid, m.homeid, m.awayid, m.eventdate, m.matchcode, m.sporttype
	FROM DB06.BODB_Archive.dbo.match_bk m WITH (NOLOCK)
	WHERE m.MatchId > @MatchId
		AND m.sporttype > 0 -- exclude outright sporttype
)
*/
ORDER BY matchid ASC

GO

/*	Version		Date			User		Comment
	======================================================
	0.1			Jan-15-2013		Tidas		First Initial
*/
/**
	Get all teams which are newer than input id.
*/
ALTER PROCEDURE Ost_Team_GetNewerTeams
	@TeamId int = 0,
	@Total int = 100
AS
SET NOCOUNT ON;

SELECT TOP (@Total) t.teamid, t.teamname, t.sporttype
FROM Team t WITH (NOLOCK)
WHERE t.teamid > @TeamId
	AND t.sporttype > 0 -- exclude outright sporttype
ORDER BY teamid ASC

GO

/*	Version		Date			User		Comment
	======================================================
	0.1			Jan-15-2013		Tidas		First Initial
*/
/**
	Get all sport types
*/
ALTER PROCEDURE Ost_Sport_GetAll
AS
SET NOCOUNT ON;

SELECT s.sporttype, s.sportname
FROM Sports s WITH (NOLOCK)
WHERE s.sporttype > 0 -- exclude outright sporttype
ORDER BY sporttype ASC

GO