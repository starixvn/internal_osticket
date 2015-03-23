<?php
$search_cond = '';
$qwhere = 'WHERE 1';
$qstr = '&t=teams'; //Query string collector
if(isset($_REQUEST["select_sport_type"]) == true && $_REQUEST["select_sport_type"] != 0) {   
   $select_sport_type = addslashes($_REQUEST["select_sport_type"]);
   $qwhere .= " AND team.sport_type='" . $select_sport_type . "'";
} else {
    $qwhere = $qwhere;
}
if(isset($_REQUEST["keyword"]) == true && $_REQUEST["keyword"] != "") {  
    $strKeyword = $_REQUEST["keyword"];
    $search_cond = addslashes(trim($_REQUEST["keyword"]));
    $keyword = $search_cond;
    $qwhere .= " AND team_name like '%" . $search_cond . "%'";    
} 
if(isset($_REQUEST["keyword"]) || isset($_REQUEST["keyword"])) {
   
    $qstr .= '&sport_type=' . urlencode($_REQUEST['select_sport_type']) . '&keyword=' . urlencode($_REQUEST['keyword']);
}
$qwhere .= " AND team.status=1";
?>
<div class="msg" style="margin-left: 3px;">Teams</div>
<form method="get" action="admin.php?t=teams">
    <input type="hidden" name="t" value="team_search" />
    <table>
        <tbody>
            <tr>
                <td>Keyword:</td>
                <td>
                    <input type="text" class="txt_keyword" value="<?=$strKeyword?>" size="30" name="keyword" id="keyword" autocomplete="off">
                </td>
                <td>Sport:</td>
                <td>
                    <select name="select_sport_type" id="sport" data-placeholder="Select Sport" class="sport-select select_search" tabindex="2">
                        <option selected="" value="0">All</option>
                        <?php
                        $query="SELECT sport_type,sport_name FROM " . SPORT_TABLE ." WHERE status=1 ORDER BY updated DESC, sport_name ASC";                
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
                <td><input type="submit" value="Search" class="button" name="search"></td>                   
            </tr>     
        </tbody>
    </table>
</form>

<?php
if(!defined('OSTADMININC') || !$thisuser->isadmin()) die('Access Denied');

$qselect = "SELECT `team_id`,`team_name`,`sport_type`,`ibc_team_id`,
                CASE sport_type
                WHEN 0
                THEN('')                
                ELSE(
                SELECT sport_name FROM " . SPORT_TABLE . " AS `spo` WHERE `team`.`sport_type`=`spo`.`sport_type`
                )
                END AS `sport_name`";
$qfrom = " FROM " . TEAM_TABLE . " AS `team` ";
//get log count based on the query so far..
$total = db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit = PAGE_LIMIT;
$page = ($_GET['p'] && is_numeric($_GET['p'])) ? $_GET['p'] : 1;  
//pagenate
$pageNav=new Pagenate($total, $page, $pagelimit);
$pageNav->setURL('admin.php', $qstr);
$query = "$qselect $qfrom $qwhere ORDER BY updated DESC,team_name ASC LIMIT " . $pageNav->getStart() . "," . $pageNav->getLimit();
$result = db_query($query);
$showing = db_num_rows($resp) ? $pageNav->showing() : "";
?>
<div class="announcement">※<span>Only ticket-related records are listed</span></div>
<form action="admin.php?t=team" method="POST" name="teams" onSubmit="return checkbox_checker(document.forms['teams'],1,0);">
    <table width="100%" border="0" cellspacing=1 cellpadding=2>    
        <input type=hidden name='do' value='mass_process'>
        <tr>
            <td>
                <table border="0" cellspacing=0 cellpadding=2 class="dtable" align="center" width="100%">
                    <tr>
                        <th width="7px">&nbsp;</th>
                        <th class="align_title">Team</th>
                        <th class="align_title">Sport</th>
                        <th class="align_title">IBC Team ID</th> 
                    </tr>
                    <?
                    $class = 'row1';
                    $total = 0;
                    $ids = ($errors && is_array($_POST['ids'])) ? $_POST['ids'] : null;
                    if($result && ($num = db_num_rows($result))):            
                        while ($row = db_fetch_array($result)) {               
                            $sel = false;
                            if(($ids && in_array($row['team_id'], $ids)) && ($leagueID && $leagueID == $row['team_id'])) {
                                $class = "$class highlight";
                                $sel = true;
                            }    
                            ?>
                    <tr class="<?=$class?>" id="<?=$row['team_id']?>">
                        <td width=7px>
                          <input type="checkbox" name="ids[]" value="<?=$row['team_id']?>" <?=$sel?'checked':''?> 
                                    onClick="highLight(this.value,this.checked);"> </td>
                        <td><a href="admin.php?t=team&id=<?=$row['team_id']?>"><?=$row['team_name']?></a></td>
                        <td><?=$row['sport_name']?></td>
                        <td align='center'><?=$row['ibc_team_id']?></td>               

                    </tr>
                        <?
                        $class = ($class =='row2') ? 'row1' : 'row2';
                        } //end of while.
                    else: //not tickets found!! ?> 
                        <tr class="<?=$class?>">
                            <td colspan=6><b>Query returned 0 results</b></td>
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
                <a href="#" onclick="return select_all(document.forms['teams'],true)">All</a>&nbsp;&nbsp;
                <a href="#" onclick="return reset_all(document.forms['teams'])">None</a>&nbsp;&nbsp;
                <a href="#" onclick="return toogle_all(document.forms['teams'],true)">Toggle</a>&nbsp;&nbsp;
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
                    onClick=' return confirm("Are you sure you want to DELETE selected teams(s)?");'>
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
        $(".sport-select").chosen({allow_single_deselect:true});
        
    })
</script>