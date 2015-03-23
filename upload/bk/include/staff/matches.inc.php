<?php
$search_cond = '';
$select_league_id = 0;
$qwhere = 'WHERE 1';
$qstr = '&t=matches'; //Query string collector
if(isset($_REQUEST["select_league_id"]) == true && $_REQUEST["select_league_id"] != 0) {
   $select_league_id = addslashes($_REQUEST["select_league_id"]);
   $qwhere .= " AND ma.league_id='" . $select_league_id . "'";
} else {
    $qwhere = $qwhere;
}
if(isset($_REQUEST["select_sport_type"]) == true && $_REQUEST["select_sport_type"] != 0) {
   $select_sport_type = addslashes($_REQUEST["select_sport_type"]);
   $qwhere .= " AND ma.sport_type='" . $select_sport_type . "'";
} else {
    $qwhere = $qwhere;
}

if(isset($_REQUEST["select_league_id"]) || isset($_REQUEST["$select_sport_type"])) {
   
    $qstr .= '&league_id=' . urlencode($_REQUEST['select_league_id']).'&sport_type=' . urlencode($_REQUEST['select_sport_type']);
    
}
$qwhere .= " AND ma.status=1";
?>

<div class="msg" style="margin-left: 3px;">Matches</div>
<form method="get" action="admin.php?t=matches" style="margin-left: 3px;">
    <input type="hidden" name="t" value="match_search" />
    <table>
        <tbody>
            <tr>
                <td>Sport:</td>
                <td>
                    <select id="sport" name="select_sport_type" data-placeholder="Select Sport" class="sport-select select_search" tabindex="2">
                        <option selected="" value="0">All</option>
                        <?php
                       $query = "SELECT sport_type,sport_name FROM " . SPORT_TABLE . " WHERE status=1 ORDER BY updated DESC, sport_name ASC";                
                        $result_sport = db_query($query);
                         if($result_sport && db_num_rows($result_sport)) {            
                            while ($row_sport = db_fetch_array($result_sport)) {     
                        ?>
                        <option value="<?=$row_sport['sport_type']?>" <?php if($select_sport_type == $row_sport['sport_type']) echo 'selected="selected"';?>><?=$row_sport['sport_name']?></option>
                        <?php
                            }
                         }
                        ?>                
                    </select>
                </td>
                <td>League:</td>
                <td>
                    <select id="league" name="select_league_id" data-placeholder="Select League" class="league-select select_search_league" tabindex="2">
                        <option selected="" value="0">All</option>
                        <?php
                        $query = "SELECT league_id,league_name FROM " . LEAGUE_TABLE . " WHERE status=1 AND sport_type='" . $select_sport_type . "' ORDER BY updated DESC, league_name ASC";                
                        $result_league = db_query($query);
                         if($result_league && db_num_rows($result_league)) {            
                            while ($row_league = db_fetch_array($result_league)) {     
                        ?>
                        <option value="<?=$row_league['league_id']?>" <?php if($select_league_id == $row_league['league_id']) echo 'selected="selected"';?>><?=$row_league['league_name']?></option>
                        <?php
                            }
                         }
                        ?>                
                    </select>
                </td>
                <td> <input type="submit" value="Search" class="button" name="search"></td>                   
            </tr>     
        </tbody>
    </table>
</form>

<?php
if(!defined('OSTADMININC') || !$thisuser->isadmin()) die('Access Denied');

$qselect = "SELECT match_id,ma.status,ma.league_id,league_name,home_id".
           ",away_id,ibc_match_id,event_date,kickofftime,match_code" .
           ",CASE`ma`.sport_type
                WHEN 0
                THEN('')                
                ELSE(
                SELECT sport_name FROM " . SPORT_TABLE . " AS `spo` WHERE `ma`.`sport_type`=`spo`.`sport_type`
                )
                END AS `sport_name`" .
            ",CASE home_id
                WHEN 0
                THEN('')                
                ELSE(
                SELECT team_name FROM " . TEAM_TABLE . " AS `tea1` WHERE `ma`.`home_id`=`tea1`.`team_id`
                )
                END AS `home_team`" .
            ",CASE away_id
                WHEN 0
                THEN('')                
                ELSE(
                SELECT team_name FROM " . TEAM_TABLE . " AS `tea2` WHERE `ma`.`away_id`=`tea2`.`team_id`
                )
                END AS `away_team`";

$qfrom = ' FROM ' .MATCH_TABLE. ' AS ma JOIN ' . LEAGUE_TABLE . ' AS lea ON ma.league_id=lea.league_id';
//get log count based on the query so far..
$total = db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit = PAGE_LIMIT;
$page = ($_GET['p'] && is_numeric($_GET['p'])) ? $_GET['p'] : 1;  
//pagenate
$pageNav = new Pagenate($total, $page, $pagelimit);
$pageNav->setURL('admin.php', $qstr);
$query = "$qselect $qfrom $qwhere ORDER BY ma.updated DESC LIMIT " . $pageNav->getStart() . "," . $pageNav->getLimit();
//echo $query;exit;
$result = db_query($query);
$showing=db_num_rows($resp) ? $pageNav->showing() : "";
?>
<div class="announcement">※<span>Only ticket-related records are listed</span></div>
<form action="admin.php?t=match" method="POST" name="matches" onSubmit="return checkbox_checker(document.forms['matches'],1,0);">
    <table width="100%" border="0" cellspacing=1 cellpadding=2>    
        <input type=hidden name='do' value='mass_process'>
        <tr>
            <td>
                <table border="0" cellspacing=0 cellpadding=2 class="dtable" align="center" width="100%">
                    <tr>
                        <th width="7px">&nbsp;</th>
                        <th class="align_title">Match Name</th>
                        <th class="align_title">League</th>
                        <th class="align_title">Sport</th>                           
                        <th class="align_title">Event Date</th>
                        <th class="align_title">Kick Off Time</th>
                        <th class="align_title">IBC Match ID</th>
                        <th class="align_title">IBC Match Code</th>
                    </tr>
                    <?
                    $class = 'row1';
                    $total = 0;
                    $ids = ($errors && is_array($_POST['ids'])) ? $_POST['ids'] : null;
                    if($result && ($num = db_num_rows($result))):            
                        while ($row = db_fetch_array($result)) {                
                            $match_name = $row['home_team'] . ' - vs - ' . $row['away_team'];
                            $sel = false;
                            if(($ids && in_array($row['match_id'],$ids)) && ($leagueID && $leagueID == $row['match_id'])){
                                $class = "$class highlight";
                                $sel = true;
                            }
                            
                            $objEventDate = new DateTime($row['event_date']);
                            $event_date = $objEventDate->format('m/d/Y');
                            $objKickofftime = new DateTime($row['kickofftime']);
                            $kickofftime = $objKickofftime->format('m/d/Y') . ' ' . substr($row['kickofftime'], 10);
                            ?>
                        <tr class="<?=$class?>" id="<?=$row['match_id']?>">
                            <td width=7px>
                              <input type="checkbox" name="ids[]" value="<?=$row['match_id']?>" <?=$sel?'checked':''?> 
                                        onClick="highLight(this.value,this.checked);">
                            </td>
                            <td><a href="admin.php?t=match&id=<?=$row['match_id']?>"><?=$match_name?></a></td>                
                            <td><?=$row['league_name']?></td>
                            <td><?=$row['sport_name']?></td>                
                            <td align="center"><?=$event_date?></td>
                            <td align="center"><?=$kickofftime?></td>
                            <td><?=$row['ibc_match_id']?></td>
                            <td><?=$row['match_code']?></td>
                        </tr>
                        <?
                        $class = ($class =='row2') ? 'row1' : 'row2';
                        } //end of while.
                    else: //not tickets found!! ?> 
                        <tr class="<?=$class?>">
                            <td colspan=7><b>Query returned 0 results</b></td>
                        </tr>
                    <?
                    endif; ?>
                </table>
            </td>
        </tr>
        <?
        if($result && db_num_rows($result)): //Show options..
         ?>
        <tr>
            <td class="div_select">
                Select:&nbsp;
                <a href="#" onclick="return select_all(document.forms['matches'],true)">All</a>&nbsp;&nbsp;
                <a href="#" onclick="return reset_all(document.forms['matches'])">None</a>&nbsp;&nbsp;
                <a href="#" onclick="return toogle_all(document.forms['matches'],true)">Toggle</a>&nbsp;&nbsp;
            </td>
        </tr>
        <?
        if($num > 0){ 
        ?>
            <tr>
                <td>Page:<?=$pageNav->getPageLinks()?></td>
            </tr>
        <?} ?>
        <tr>
            <td align="center">           
                <input class="button" type="submit" name="delete" value="Delete" 
                    onClick=' return confirm("Are you sure you want to DELETE selected match(es)?");'>
            </td>
        </tr>
        <?
        endif;
        ?>
    </table>
</form>

<script>
    $(function(){
        // auto complate word for selectbox 'sport'
        $(".sport-select").chosen();
        $(".sport-select-deselect").chosen({allow_single_deselect:true});
        
        // auto complate word for selectbox 'league'
        $(".league-select").chosen();
        $(".league-select-deselect").chosen({allow_single_deselect:true});
        
        $("#sport").live("change", function() { 
            // clear all leagues
            $('#league').find('option').remove(); 
            $("#league").append('<option value="0">All</option>');
            $(".league-select").trigger("liszt:updated");
            var sport_type = $('#sport').val();

            $.ajax({
                type:"GET",
                url: "../loadAjax.php",
                data: {
                    sport_type: sport_type,
                    is_ibc: 'false',
                    type: 'sport_league'
                },
                dataType: "json",
                success: function(data){
                    if(data.length > 0) {
                        $.each(data, function(key, value){ 
                            $("#league").append('<option value="' + value.league_id + '">' + value.league_name + '</option>');                                      
                        });
                        $(".league-select").trigger("liszt:updated");
                    } else {
                        $("#league").append('<option value=""></option>');
                        $(".league-select").trigger("liszt:updated");
                    }
                },  
                error: function(){}  
            });
        })
    })
</script>
