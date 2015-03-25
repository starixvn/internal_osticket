<?php
/*
 * Load data with ajax
 */
require('../main.inc.php');
if($_GET['type'] == 'match') { // insert match
    $intLeagueId = $_GET['league_id'];
    $intHomeId = $_GET['home_id'];
    $intAwayId = $_GET['away_id'];
    $intSportType = $_GET['sport_type'];
    $strEventDate = $_GET['event_date'];
    $strMatchCode = $_GET['match_code'];
    $intIcbMatchId = $_GET['icb_match_id'];    
    insertNewMatch($intLeagueId, $intHomeId, $intAwayId, $intSportType, $strEventDate, $strMatchCode, $intIcbMatchId);
} else if($_GET['type'] == 'league') { // insert league
    $intSportType = $_GET['sport_type'];
    $intIbcLeagueId = $_GET['ibc_league_id'];
    $strLeagueName = $_GET['league_name'];    
    insertNewLeague($strLeagueName, $intSportType, $intIbcLeagueId);
} else if($_GET['type'] == 'change_league') { // load match list by league_id
    $intLeagueId = addslashes(trim($_GET['league_id']));
    $intSportType = addslashes(trim($_GET['sport_type']));    
    getMatchListOsticketByLeagueId($intSportType, $intLeagueId);
} else if($_GET['type'] == 'filter_event_date_ibc') { // load match list by event date from IBC database
    $intLeagueId = addslashes(trim($_GET['league_id']));
    $intSportType = addslashes(trim($_GET['sport_type']));
    $strSD = addslashes(trim($_GET['sd']));
    $strED = addslashes(trim($_GET['ed']));    
    getMatchListByLeagueId($intSportType, $intLeagueId, $strSD, $strED);
} else if($_GET['type'] == '1' || $_GET['type'] == '2' || $_GET['type'] == '3' || $_GET['type'] == '4') { // load actor list    
    $strName = $_GET['name'];
    $strContactInfo = $_GET['contact_info'];
    $intType = $_GET['type'];    
    insertActor($strName, $strContactInfo, $intType);
} else if($_GET['type'] == 'sport_league') { // load league by sport_type for Match Form
    $sport_type = addslashes(trim($_GET['sport_type']));
    $isIBC = addslashes(trim($_GET['is_ibc']));
    getLeagueListBySportType($sport_type, $isIBC);
} else if($_GET['type'] == 'change_sport') { // load team list by sport_type for Match Form
    $sport_type = addslashes(trim($_GET['sport_type']));    
    getListWhenSportChange($sport_type);
} else if($_GET['type'] == 'sport') { // insert league
    $strSportName = $_GET['sport_name'];    
    insertNewSport($strSportName);
} else if($_GET['type'] == 'sync_data') { // insert league
    $intTypeAPI = $_GET['typeAPI'];    
    syncDataFromServerIBC($intTypeAPI);
} else if($_GET['type'] == 'search_more_league') { // insert league
    $sport_type = addslashes(trim($_GET['sport_type']));        
    $isIBC = addslashes(trim($_GET['is_ibc']));
    $keyword = addslashes(trim($_GET['keyword']));
    getMoreLeagueListBySportType($sport_type, $isIBC, $keyword);
}

// get match list from IBC database
function getMatchListByLeagueId($intSportType = 0, $intLeagueId = 0, $strSD = '', $strED = '')
{
    $arrMatch = array();
    $strWhere = '';
    $strLeagueCond = "";
    if($intSportType != 0) {        
        if($intLeagueId != 0) {
            $strLeagueCond = "league_id='" . $intLeagueId . "' AND ";
        }
        //echo 'Current PHP version: ' . phpversion();exit;
        $start_date = new DateTime($strSD);
        $start_event_date = $start_date->format('Y-m-d') . ' ' . substr($strSD, 10);        
        
        $end_date = new DateTime($strED);
        $end_event_date =  $end_date->format('Y-m-d') . ' ' .  substr($strED, 10);
        
        if($strSD != '' && $strED == '') {
            $strWhere = " AND ( DATE_FORMAT(`ma`.kickofftime,'%Y-%m-%d %T') >= DATE_FORMAT('" . $start_event_date . "','%Y-%m-%d %T') ) ";
        } else if($strSD == '' && $strED != '') {
            $strWhere = " AND ( DATE_FORMAT(`ma`.kickofftime,'%Y-%m-%d %T') <= DATE_FORMAT('" . $end_event_date . "','%Y-%m-%d %T') )";
        } else if($strSD != '' && $strED != '') {
            $strWhere = " AND ( DATE_FORMAT(`ma`.kickofftime,'%Y-%m-%d %T') between DATE_FORMAT('" . $start_event_date . "','%Y-%m-%d %T') AND DATE_FORMAT('" . $end_event_date . "','%Y-%m-%d %T') )";   
        }
        
        $query = "SELECT match_id,kickofftime,event_date,match_code".
                ",CASE home_id
                WHEN 0
                THEN('')                
                ELSE(
                SELECT team_name FROM " . IBC_TEAM_TABLE . " AS `tea1` WHERE `ma`.`home_id`=`tea1`.`team_id`
                )
                END AS `home_team`".
                ",CASE away_id
                    WHEN 0
                    THEN('')                
                    ELSE(
                    SELECT team_name FROM " . IBC_TEAM_TABLE . " AS `tea2` WHERE `ma`.`away_id`=`tea2`.`team_id`
                    )
                    END AS `away_team`".
                "FROM " . IBC_MATCH_TABLE ." AS `ma` " .
                "WHERE  " . $strLeagueCond . "sport_type='" . $intSportType . "' AND `ma`.`home_id` > 0 AND `ma`.`away_id` > 0 " . $strWhere .
                "ORDER BY kickofftime ASC";
				
        //echo $query;exit;
        $result_match = db_query($query);
        if($result_match && db_num_rows($result_match)) { 
            $arrMatch['total'] = db_num_rows($result_match);
            $arrMatch['list'] = array();
            if($arrMatch['total'] <= 1000) {
                while ($row_match = db_fetch_array($result_match)) { 
                    $arrList = array();
                    $arrList['match_id'] = $row_match['match_id'];
                    $arrList['match_name'] = $row_match['home_team'] . ' - vs - ' . $row_match['away_team'] . ' [' . $row_match['match_code'] . '] ( ' . date('Y/m/d H:i:s', strtotime($row_match['kickofftime'])) . ' )';
                    $arrMatch['list'][] = $arrList;
                }
            }
        } else {
            $arrMatch['total'] = db_num_rows($result_match);
            $arrMatch['list'] = null;
        }        
    }
    header('Content-type: application/x-json');
    echo json_encode($arrMatch);
}

// insert new league
function insertNewLeague($strLeagueName = '', $intSportType = 0, $intIbcLeagueId = 0)
{
    $arrLeague = array();
    $arrLeagueList = array();
    if($strLeagueName != '' && $intSportType != 0 && $intIbcLeagueId != 0) {
        $sql = "INSERT INTO " . LEAGUE_TABLE . " SET league_name=".db_input($strLeagueName).
                ",sport_type=".db_input($intSportType).",ibc_league_id=".db_input($intIbcLeagueId). 
                ",status=1,updated=NOW()";
        $result = db_query($sql);
        if($result) {
            $leagueID=db_insert_id();
        }
        if($leagueID) {
            $query = "SELECT league_id,league_name FROM " . LEAGUE_TABLE ." WHERE status=1 and league_id='" . $leagueID ."'";                
            $result_league = db_query($query);
            if($result_league && db_num_rows($result_league)) { 
                while ($row_league = db_fetch_array($result_league)) {  
                    $arrLeague['league_id'] = $row_league['league_id'];
                    $arrLeague['league_name'] = $row_league['league_name'];
                    $arrLeagueList = $arrLeague;
                }
            }
        }
    }
    header('Content-type: application/x-json');
    echo json_encode($arrLeagueList);
}

// insert new match
function insertNewMatch($intLeagueId = 0,$intHomeId = 0,$intAwayId = 0,$intSportType = 0,$strEventDate = '',$strMatchCode = '',$intIcbMatchId = 0)
{
    $arrMatch = array();
    $arrMatchList = array();   
    if($intLeagueId != 0 && $intHomeId != 0 && $intAwayId != 0 && $intSportType != 0
        && $strEventDate != '' && $strMatchCode != '') {        
        $error = false;
        if($strMatchCode != '') {
            $sql='SELECT match_id FROM '.MATCH_TABLE.' WHERE match_code='.db_input($strMatchCode);           
            if(db_num_rows(db_query($sql))) {
                $error = true;
            }
        }
        $event_date = ($strEventDate?db_input(date('Y-m-d G:i',Misc::dbtime($strEventDate))):'NULL');        
        if($strEventDate  != '') {
           $sql='SELECT match_id FROM '.MATCH_TABLE.' WHERE home_id='.db_input($intHomeId).' AND away_id='.db_input($intAwayId).' AND event_date='.$event_date;           
           if(db_num_rows(db_query($sql))) {
               $error = true;
           }
        }
        if($error == false) {
            $sql = 'INSERT INTO ' . MATCH_TABLE . ' SET `league_id`='.db_input($intLeagueId).
                     ',`home_id`='.db_input($intHomeId).                 
                     ',`away_id`='.db_input($intAwayId).
                     ',`ibc_match_id`='.db_input($intIcbMatchId).
                     ',`event_date`='.$event_date.
                     ',`match_code`='.db_input($strMatchCode).
                     ',`sport_type`='.db_input($intSportType).
                     ',`updated`=NOW()';
            $result = db_query($sql);
            if($result) {
                $matchID = db_insert_id();
            }
            if($matchID) {
                $query = "SELECT match_id,event_date" .
                        ",CASE home_id
                        WHEN 0
                        THEN('')                
                        ELSE(
                        SELECT team_name FROM " . TEAM_TABLE . " AS `tea1` WHERE `ma`.`home_id`=`tea1`.`team_id`
                        )
                        END AS `home_team`".
                        ",CASE away_id
                            WHEN 0
                            THEN('')                
                            ELSE(
                            SELECT team_name FROM " . TEAM_TABLE . " AS `tea2` WHERE `ma`.`away_id`=`tea2`.`team_id`
                            )
                            END AS `away_team`".
                        " FROM " . MATCH_TABLE ." AS `ma` WHERE status=1 and match_id='" . $matchID ."'";
            $result_match = db_query($query);
            if($result_match && db_num_rows($result_match)) { 
               while ($row_match = db_fetch_array($result_match)) {  
                  $arrMatch['match_id'] = $row_match['match_id'];
                  $arrMatch['match_name'] = $row_match['home_team'] . ' - vs - ' . $row_match['away_team'] . ' ( ' . Format::db_date($row_match['event_date']) . ' )';
                  $arrMatchList=$arrMatch;
               }
            }
            }
        }
    }   
    header('Content-type: application/x-json');
    echo json_encode($arrMatchList);
}

// insert new actor (trader, suspender, csd)
function insertActor($strName,$strContactInfo,$intType)
{
    $arrActor = array();
    $arrActorList = array();
    if($strName != '') {
        $sql = "INSERT INTO " . ACTOR_TABLE . " SET actor_name=".db_input($strName).
                 ",contact_info=".db_input($strContactInfo).
                 ",type=".db_input($intType).",status=1,updated=NOW()";
        $result = db_query($sql);
        if($result) {
            $actorID=db_insert_id();
        }
        if($actorID) {       
            $query = "SELECT actor_id,actor_name" .
                     " FROM " . ACTOR_TABLE .
                     " WHERE status=1 and actor_id='" . $actorID ."'";                
            $result_actor = db_query($query);
            if($result_actor && db_num_rows($result_actor)) { 
               while ($row_actor = db_fetch_array($result_actor)) {  
                  $arrActor['actor_id'] = $row_actor['actor_id'];
                  $arrActor['actor_name'] = $row_actor['actor_name'];
                  $arrActorList=$arrActor;
               }
            }
        }
    }
    header('Content-type: application/x-json');
    echo json_encode($arrActorList);
}

// get league list by sport_type
function getLeagueListBySportType($sport_type = 0, $isIBC = 'false')
{ 
    $arrLeague = array();
    $strTableName = LEAGUE_TABLE;
    $strWhere = '';
    if($sport_type != 0) {
        // check data of IBC database
        if($isIBC == 'false' || $isIBC == '') {
            $strWhere = "status=1 and ";
        } else {
            $strTableName = IBC_LEAGUE_TABLE;
        }      
        $query = "SELECT league_id,league_name FROM " . $strTableName ." WHERE " . $strWhere . "sport_type='" . $sport_type . "' ORDER BY league_id DESC, league_name ASC LIMIT 20"; 
        $result_league = db_query($query);
        if($result_league && db_num_rows($result_league)) { 
           while ($row_league = db_fetch_array($result_league)) { 
              $arrList = array();
              $arrList['league_id'] = $row_league['league_id'];
              $arrList['league_name'] = $row_league['league_name'];
              $arrLeague[]=$arrList;
           }
        } 
    }
    header('Content-type: application/x-json');
    echo json_encode($arrLeague);
}

// get more league list by sport_type
function getMoreLeagueListBySportType($sport_type = 0, $isIBC = 'false', $keyword = '')
{    
    $arrLeague = array();
    $strTableName = LEAGUE_TABLE;
    $strWhere = '';
    if($sport_type != 0) {
        // check data of IBC database
        if($isIBC == 'false' || $isIBC == '') {
            $strWhere = "status=1 and ";
        } else {
            $strTableName = IBC_LEAGUE_TABLE;
        }      
        $query = "SELECT league_id,league_name FROM " . $strTableName ." WHERE ". $strWhere . "sport_type='" . $sport_type . "' and league_name LIKE '%" . $keyword . "%' ORDER BY league_id DESC, league_name ASC"; 
        $result_league = db_query($query);
        if($result_league && db_num_rows($result_league)) { 
           while ($row_league = db_fetch_array($result_league)) { 
              $arrList = array();
              $arrList['league_id'] = $row_league['league_id'];
              $arrList['league_name'] = $row_league['league_name'];
              $arrLeague[]=$arrList;
           }
        } 
    }
    header('Content-type: application/x-json');
    echo json_encode($arrLeague);
}

function getListWhenSportChange($sport_type)
{
    $arrAllList = array();
    $arrLeague = array();
    $arrTeam = array();
    if($sport_type != 0) {
        // get league list
        $query_league = "SELECT league_id,league_name FROM " . LEAGUE_TABLE ." WHERE status=1 and sport_type='" . $sport_type . "' ORDER BY updated DESC, league_name ASC";                
        $result_league = db_query($query_league);
        if($result_league && db_num_rows($result_league)) { 
            while ($row_league = db_fetch_array($result_league)) { 
                 $arrLeagueList = array();
                 $arrLeagueList['league_id'] = $row_league['league_id'];
                 $arrLeagueList['league_name'] = $row_league['league_name'];
                 $arrLeague[]=$arrLeagueList;
            }
            $arrAllList['league'] = $arrLeague;
         }
         // get team list
        
         $query_team = "SELECT team_id,team_name FROM " . TEAM_TABLE ." WHERE status=1 and sport_type='" . $sport_type . "' ORDER BY updated DESC, team_name ASC";                
        $result_team = db_query($query_team);
        if($result_team && db_num_rows($result_team)) { 
           while ($row_team = db_fetch_array($result_team)) { 
               $arrTeamList = array();
               $arrTeamList['team_id'] = $row_team['team_id'];
               $arrTeamList['team_name'] = $row_team['team_name'];
               $arrTeam[]=$arrTeamList;
           }
           $arrAllList['team'] = $arrTeam;
        } 
    }
    header('Content-type: application/x-json');
    echo json_encode($arrAllList);
}
// get team list by sport_type and league_id
function getTeamListBySportTypeLeague_id($sport_type)
{
    $arrLeague = array();
    if($sport_type != 0) {
        $query = "SELECT team_id,team_name FROM " . TEAM_TABLE ." WHERE status=1 and sport_type='" . $sport_type . "' ORDER BY updated DESC, league_name ASC";                
        $result_league = db_query($query);
         if($result_league && db_num_rows($result_league)) { 
            while ($row_league = db_fetch_array($result_league)) { 
                $arrList = array();
                $arrList['league_id'] = $row_league['league_id'];
                $arrList['league_name'] = $row_league['league_name'];
                $arrLeague[]=$arrList;
            }
         } 
    }
    header('Content-type: application/x-json');
    echo json_encode($arrLeague);
}

// insert new sport
function insertNewSport($strSportName)
{
    $arrSport = array();
    $arrSportList = array();
    if($strSportName != '') {
        $sql = "INSERT INTO " . SPORT_TABLE . " SET sport_name=".db_input($strSportName).
                 ",status=1,updated=NOW()";
        $result = db_query($sql);
        if($result) {
            $sportType=db_insert_id();
        }        
        if($sportType) {
            $query = "SELECT sport_type,sport_name FROM " . SPORT_TABLE ." WHERE status=1 and sport_type='" . $sportType ."'";                
            $result_sport = db_query($query);
            if($result_sport && db_num_rows($result_sport)) { 
                while ($row_sport = db_fetch_array($result_sport)) {  
                    $arrSport['sport_type'] = $row_sport['sport_type'];
                    $arrSport['sport_name'] = $row_sport['sport_name'];
                    $arrSportList=$arrSport;
                }
            }
        }        
    }
    header('Content-type: application/x-json');
    echo json_encode($arrSportList);
}

// synchronise data from IBC server
function syncDataFromServerIBC($intTypeAPI)
{
    // synchronise sport data from IBC server    
    if($intTypeAPI == 1) {
        $arrSport = syncSportFromServer();
        header('Content-type: application/x-json');
        echo json_encode($arrSport);
    } else if($intTypeAPI == 2) { // synchronise league data from IBC server 
        $arrLeague = syncLeagueFromServer();
        header('Content-type: application/x-json');
        echo json_encode($arrLeague);
    } else if($intTypeAPI == 3) { // synchronise team data from IBC server 
        $arrTeam = syncTeamFromServer();
        header('Content-type: application/x-json');
        echo json_encode($arrTeam);
    } else if($intTypeAPI == 4) { // synchronise match data from IBC server 
        $arrMatch = syncMatchFromServer();
        header('Content-type: application/x-json');
        echo json_encode($arrMatch);
    } else {
        $arrData = array();
        $arrData['sport'] = syncSportFromServer();
        $arrData['league'] = syncLeagueFromServer();
        $arrData['team'] = syncTeamFromServer();
        $arrData['match'] = syncMatchFromServer();
        header('Content-type: application/x-json');
        echo json_encode($arrData);
    }    
}

// synchronise sport from IBC server
function syncSportFromServer()
{
    $arrSport = array();
    $sql_sport_max = "SELECT max(sport_type) AS 'max_sport_type' FROM " . SPORT_TABLE;
    $result_sport = db_query($sql_sport_max);
    if(db_num_rows($result_sport) > 0) {
        $row_sport = db_fetch_array($result_sport);
        $strURLAPI = URL_WEBSERVICE . 'sport/' .$row_sport['max_sport_type'];
    } else {
        $strURLAPI = URL_WEBSERVICE . 'sport';
    }
    // Get Json data      
    $json_sport = file_get_contents($strURLAPI, 0, null, null);
    $arrSportList = json_decode($json_sport);
    // check json
    if(count($arrSportList) > 0) {
        foreach($arrSportList as $sport) {                
            $sport = (array)$sport;
            $intSportType = $sport['SportType'];
            $strSportName = $sport['SportName'];
			$selectQuery = 'SELECT count(sport_type) AS number FROM ' . SPORT_TABLE . ' WHERE sport_type='. db_input($intSportType) . ' AND status=1';
			$select_sport = db_fetch_array(db_query($selectQuery));
            if($intSportType > 0 && $select_sport['number'] < 1) {
                $sql_sport = "INSERT INTO " . SPORT_TABLE . 
                   " SET sport_type=" . db_input($intSportType) .
                        ",sport_name=" . db_input($strSportName) .
                        ",status=1,updated=NOW()";
                $result_sport = db_query($sql_sport);
                if(!$result_sport) {
                    break;
                } else {                 
                    $arrList = array();
                    $arrList['sport_type'] = $intSportType;
                    $arrList['sport_name'] = $strSportName;
                    $arrSport[]=$arrList;
                }  
            }            
        }            
    }
    return $arrSport;
}

// synchronise league from IBC server
function syncLeagueFromServer()
{
    $arrLeague = array();
    $sql_league_max = "SELECT max(league_id) AS 'max_ibc_league_id' FROM " . IBC_LEAGUE_TABLE;
    $result_league = db_query($sql_league_max);
    if(db_num_rows($result_league) > 0) {
        $row_league = db_fetch_array($result_league);
        $strURLAPI = URL_WEBSERVICE . 'league/' .$row_league['max_ibc_league_id'];
    } else {
        $strURLAPI = URL_WEBSERVICE . 'league';
    }
    // Get Json data      
    $json_league = file_get_contents($strURLAPI, 0, null, null);
    $arrLeagueList = json_decode($json_league);
    // check json
    if(count($arrLeagueList) > 0) {
        foreach($arrLeagueList as $league) {                
            $league = (array)$league;
            $intLeagueIdIBC = $league['LeagueID'];
            $strLeagueName = $league['LeagueName'];
            $intSportType = $league['SportType'];
            if($intLeagueIdIBC > 0) {
                $sql_league = "INSERT INTO " . IBC_LEAGUE_TABLE . 
                   " SET league_id=" . db_input($intLeagueIdIBC) .
                        ",league_name=" . db_input($strLeagueName) .
                        ",sport_type=" . db_input($intSportType);
                $result_league = db_query($sql_league);
                if(!$result_league) {
                    break;
                } else {                 
                    $arrList = array();
                    $intLeagueId = db_insert_id();
                    $arrList['league_id'] = $intLeagueId;                    
                    $arrLeague[]=$arrList;
                } 
            }             
        }            
    }
    return $arrLeague;
}

// synchronise team from IBC server
function syncTeamFromServer()
{
    $arrTeam = array();
    $sql_team_max = "SELECT max(team_id) AS 'max_ibc_team_id' FROM " . IBC_TEAM_TABLE;
    $result_team = db_query($sql_team_max);
    if(db_num_rows($result_team) > 0) {
        $row_team = db_fetch_array($result_team);
        $strURLAPI = URL_WEBSERVICE . 'team/' .$row_team['max_ibc_team_id'];
    } else {
        $strURLAPI = URL_WEBSERVICE . 'team';
    }
    // Get Json data      
    $json_team = file_get_contents($strURLAPI, 0, null, null);
    $arrTeamList = json_decode($json_team);
    // check json
    if(count($arrTeamList) > 0) {
        foreach($arrTeamList as $team) {                
            $team = (array)$team;
            $intTeamIdIBC = $team['TeamID'];
            $strTeamName = $team['TeamName'];
            $intSportType = $team['SportType'];
            if($intTeamIdIBC > 0) {
                $sql_team = "INSERT INTO " . IBC_TEAM_TABLE . 
                   " SET team_id=" . db_input($intTeamIdIBC) .
                        ",team_name=" . db_input($strTeamName) .
                        ",sport_type=" . db_input($intSportType);
                $result_team = db_query($sql_team);
                if(!$result_team) {
                    break;
                } else {                 
                    $arrList = array();
                    $intTeamId = db_insert_id();
                    $arrList['team_id'] = $intTeamId;                    
                    $arrTeam[]=$arrList;
                }  
            }             
        }            
    }
    return $arrTeam;
}

// synchronise match from IBC server
function syncMatchFromServer()
{
    $arrMatch = array();
    $sql_match_max = "SELECT max(match_id) AS 'max_ibc_match_id' FROM " . IBC_MATCH_TABLE;
    $result_match = db_query($sql_match_max);
    if(db_num_rows($result_match) > 0) {
        $row_match = db_fetch_array($result_match);
        $strURLAPI = URL_WEBSERVICE . 'match/' .$row_match['max_ibc_match_id'];
    } else {
        $strURLAPI = URL_WEBSERVICE . 'match';
    }    
    // Get Json data      
    $json_match = file_get_contents($strURLAPI, 0, null, null);
    $arrMatchList = json_decode($json_match);
    // check json
    if(count($arrMatchList) > 0) {
        foreach($arrMatchList as $match) {                
            $match = (array)$match;
            $intMatchIdIBC = $match['MatchID'];
            $strLeagueId = $match['LeagueID'];
            $strHomeId = $match['HomeID'];
            $strAwayId = $match['AwayID'];
            $strEventDate = $match['EventDate'];
            $strKickOffTime = $match['KickOffTime'];
            $strMatchCode = $match['MatchCode'];
            $intSportType = $match['SportType'];
            if($intMatchIdIBC > 0) {
                $sql_match = "INSERT INTO " . IBC_MATCH_TABLE . 
                   " SET match_id=" . db_input($intMatchIdIBC) .
                        ",league_id=" . db_input($strLeagueId) .
                        ",home_id=" . db_input($strHomeId) .
                        ",away_id=" . db_input($strAwayId) .
                        ",kickofftime=" . db_input($strKickOffTime) .
                        ",event_date=" . db_input($strEventDate) .
                        ",match_code=" . db_input($strMatchCode) .
                        ",sport_type=" . db_input($intSportType);
                $result_match = db_query($sql_match);
                if(!$result_match) {
                    break;
                } else {                 
                    $arrList = array();
                    $intMatchId = db_insert_id();
                    $arrList['match_id'] = $intMatchId;                    
                    $arrMatch[]=$arrList;
                }  
            }             
        }            
    }
    return $arrMatch;
}

// get match list from osticket database
function getMatchListOsticketByLeagueId($intSportType = 0, $intLeagueId = 0, $strSD = '', $strED = '')
{
    $arrMatch = array();
    $strWhere = '';
    if($intLeagueId != 0 && $intSportType != 0) {
        $start_event_date = ($strSD?db_input(date('Y-m-d', strtotime($strSD))):'NULL');
        $end_event_date = ($strED?db_input(date('Y-m-d', strtotime($strED . ' 23:59'))):'NULL');
        if($strSD != '' && $strED == '') {
            $strWhere = " AND DATE_FORMAT(`ma`.event_date,'%Y-%m-%d') >= DATE_FORMAT(" . $start_event_date . ",'%Y-%m-%d')";
        } else if($strSD == '' && $strED != '') {
            $strWhere = " AND DATE_FORMAT(`ma`.event_date,'%Y-%m-%d') >= DATE_FORMAT(" . $end_event_date . ",'%Y-%m-%d')";
        } else if($strSD != '' && $strED != '') {
            $strWhere = " AND DATE_FORMAT(`ma`.event_date,'%Y-%m-%d') between DATE_FORMAT(" . $start_event_date . ",'%Y-%m-%d') AND DATE_FORMAT(" . $end_event_date . ",'%Y-%m-%d')";   
        }        
        $query = "SELECT match_id,event_date ".
                ",CASE home_id
                WHEN 0
                THEN('')                
                ELSE(
                SELECT team_name FROM " . TEAM_TABLE . " AS `tea1` WHERE `ma`.`home_id`=`tea1`.`team_id`
                )
                END AS `home_team`".
                ",CASE away_id
                    WHEN 0
                    THEN('')                
                    ELSE(
                    SELECT team_name FROM " . TEAM_TABLE . " AS `tea2` WHERE `ma`.`away_id`=`tea2`.`team_id`
                    )
                    END AS `away_team`".
                "FROM " . MATCH_TABLE ." AS `ma` " .
                "WHERE league_id='" . $intLeagueId . "' AND sport_type='" . $intSportType . "' " . $strWhere .
                "ORDER BY event_date ASC";
        $result_match = db_query($query);
        if($result_match && db_num_rows($result_match)) { 
            while ($row_match = db_fetch_array($result_match)) { 
                $arrList = array();
                $arrList['match_id'] = $row_match['match_id'];
                $arrList['match_name'] = $row_match['home_team'] . ' - vs - ' . $row_match['away_team'] . ' ( ' . Format::db_date($row_match['event_date']) . ' )';
                $arrMatch[] = $arrList;
            }
        } 
    }
    header('Content-type: application/x-json');
    echo json_encode($arrMatch);
}

function convertDateTime($strDateTime)
{
    /*$intTotal = mb_strlen($strDateTime);
    $strDate = substr($strDateTime, 10);
    $strTime = substr($strDateTime, 10);
     if(mb_strlen($strDateTime) == 10) {
        $strED =  str_replace('/', '-', $strED . ' 23:59:59');           
     } else {
         $strED =  str_replace('/', '-', $strED); 
     }*/
}
?>
