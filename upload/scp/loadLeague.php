<?php

/*
 * Load data with ajax
 */
require('../main.inc.php');

$arrLeague = array();
$strKeyword = addslashes($_GET['keyword']);
$sportType = addslashes($_GET['sportType']);
$queryTotal = "SELECT COUNT(*) as total FROM " . IBC_LEAGUE_TABLE . " WHERE sport_type='" . $sportType . "' and league_name like '%" . $strKeyword . "%'";
$resultTotal = db_fetch_array(db_query($queryTotal));
$arrLeague['total'] = $resultTotal['total'];

$arrLeague['list'] = array();
$query = "SELECT league_id,league_name FROM " . IBC_LEAGUE_TABLE . " WHERE sport_type='" . $sportType . "' and league_name like '%" . $strKeyword . "%'";
$result_league = db_query($query);
if ($result_league && db_num_rows($result_league)) {
    while ($row_league = db_fetch_array($result_league)) {
        $arrList = array();
        $arrList['id'] = $row_league['league_id'];
        $arrList['name'] = $row_league['league_name'];
        $arrLeague['list'][] = $arrList;
    }
}
header('Content-Type: application/json');
echo json_encode($arrLeague);
