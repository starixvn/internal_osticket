use osticket;

/*
DELETE FROM ibc_league WHERE league_id >= 0;
DELETE FROM ibc_match WHERE match_id >= 0;
DELETE FROM ibc_team WHERE team_id >= 0;
DELETE FROM ost_sport WHERE sport_type >= 0;
*/

DELIMITER //
CREATE PROCEDURE ost_getmaxleagueid()
BEGIN
	SELECT MAX(league_id) FROM ibc_league;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE ost_getmaxmatchid()
BEGIN
	SELECT MAX(match_id) FROM ibc_match;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE ost_getmaxteamid()
BEGIN
	SELECT MAX(team_id) FROM ibc_team;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE ost_newsport(
	sport_type int,
	sport_name varchar(255)
)
BEGIN
	INSERT IGNORE INTO ost_sport(sport_type, sport_name) VALUES(sport_type, sport_name);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE ost_newteam(
	team_id int,
	team_name varchar(255),
	sport_type int
)
BEGIN
	INSERT IGNORE INTO ibc_team(team_id, team_name, sport_type) VALUES(team_id, team_name, sport_type);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE ost_newleague(
	league_id int,
	league_name varchar(255),
	sport_type int
)
BEGIN
	INSERT IGNORE INTO ibc_league(league_id, league_name, sport_type) VALUES(league_id, league_name, sport_type);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE ost_newmatch(
	match_id int,
	league_id int,
	home_id int,
	away_id int,
	event_date date,
	match_code varchar(255),
	sport_type int
)
BEGIN
	INSERT IGNORE INTO ibc_match(match_id, league_id, home_id, away_id, event_date, match_code, sport_type) VALUES(match_id, league_id, home_id, away_id, event_date, match_code, sport_type);
END //
DELIMITER ;