<?php
/* define value of type
 * 
 * All=0
 * Trader=1
 * Suspender=2
 * CSD=3
 */
$search_cond = '';
$qwhere = 'WHERE 1';
$qstr = '&t=actors'; //Query string collector
if(isset($_REQUEST["select_type_id"]) == true && $_REQUEST["select_type_id"] != 0) {
   $select_type_id = addslashes($_REQUEST["select_type_id"]);
   $qwhere .= " AND type='" . $select_type_id . "'";
} else {
    $qwhere = $qwhere;
}
if(isset($_REQUEST["keyword"]) == true && $_REQUEST["keyword"] != "") {
    $strSearch = $_REQUEST["keyword"];
    $search_cond = addslashes(trim($_REQUEST["keyword"]));
    $keyword = $search_cond;
    $qwhere .= " AND actor_name like '%" . $search_cond . "%'";    
} 
if(isset($_REQUEST["keyword"]) || isset($_REQUEST["keyword"])) {
    $qstr .= '&type=' . urlencode($_REQUEST['select_type_id']) . '&keyword=' . urlencode($_REQUEST['keyword']);
}
$qwhere .= " AND status=1";
?>
<div class="msg" style="margin-left: 3px;">Actors</div>
<form method="get" action="admin.php?t=actor" style="margin-left: 3px;">
    <input type="hidden" name="t" value="actor_search" />
    <table>
        <tbody><tr>
            <td>Keyword:</td>
            <td>
                <input type="text" class="txt_keyword" value="<?=$strSearch?>" size="30" name="keyword" id="keyword" autocomplete="off">
            </td>
            <td>Type:</td>
            <td>
                <select name="select_type_id" class="select_search">
                    <option <?php if($select_type_id == 0) echo 'selected="selected"'; ?> value="0">All</option>
                    <option <?php if($select_type_id == 1) echo 'selected="selected"'; ?> value="1">Trader</option>
                    <option <?php if($select_type_id == 2) echo 'selected="selected"'; ?> value="2">Suspender</option>
                    <option <?php if($select_type_id == 3) echo 'selected="selected"'; ?> value="3">CSD</option>
                    <option <?php if($select_type_id == 4) echo 'selected="selected"'; ?> value="4">Provider</option>
                </select>
            </td>
            <td>
                <input type="submit" value="Search" class="button" name="search">
            </td>                   
         </tr>     
        </tbody>
    </table>
</form>
<?php
if(!defined('OSTADMININC') || !$thisuser->isadmin()) die('Access Denied');
$qselect = "SELECT actor_id,actor_name,contact_info,type";
$qfrom = ' FROM '.ACTOR_TABLE;
//get log count based on the query so far..
$total = db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit = PAGE_LIMIT;
$page = ($_GET['p'] && is_numeric($_GET['p'])) ? $_GET['p'] : 1;  
//pagenate
$pageNav = new Pagenate($total, $page, $pagelimit);
$pageNav->setURL('admin.php', $qstr);
$query = "$qselect $qfrom $qwhere ORDER BY updated DESC,actor_name ASC LIMIT " . $pageNav->getStart() . "," . $pageNav->getLimit();
//echo $query;
$result = db_query($query);
$showing = db_num_rows($resp) ? $pageNav->showing() : "";
?>
<form action="admin.php?t=actor" method="POST" name="actors" onSubmit="return checkbox_checker(document.forms['actors'],1,0);">
    <table width="100%" border="0" cellspacing=1 cellpadding=2>    
        <input type=hidden name='do' value='mass_process'>
        <tr>
            <td>
                <table border="0" cellspacing=0 cellpadding=2 class="dtable" align="center" width="100%">
                    <tr>
                            <th width="7px">&nbsp;</th>
                            <th class="align_title">Name</th>
                            <th class="align_title">Type</th>
                            <th class="align_title">Contact information</th>
                    </tr>
                    <?
                    $class = 'row1';
                    $total = 0;
                    $ids = ($errors && is_array($_POST['ids'])) ? $_POST['ids'] : null;
                    if($result && ($num = db_num_rows($result))):            
                        while ($row = db_fetch_array($result)) {                
                            $sel = false;
                            if(($ids && in_array($row['actor_id'], $ids)) && ($leagueID && $leagueID == $row['actor_id'])){
                                $class = "$class highlight";
                                $sel = true;
                            }    
                            ?>
                        <tr class="<?=$class?>" id="<?=$row['actor_id']?>">
                            <td width=7px>
                              <input type="checkbox" name="ids[]" value="<?=$row['actor_id']?>" <?=$sel ? 'checked' : ''?> 
                                        onClick="highLight(this.value,this.checked);"> </td>
                            <td><a href="admin.php?t=actor&id=<?=$row['actor_id']?>"><?=$row['actor_name']?></a></td>
                            <td class="align_title">
                                <?php
                                $type = $row['type'];
                                if($type == 1) {
                                    echo 'Trader';
                                } else if($type == 2) {
                                    echo 'Suspender';
                                } else if($type == 3) {
                                    echo 'CSD';
                                } else {
                                    echo 'Provider';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($row['contact_info'] == '') {
                                    echo '&nbsp;';
                                } else {
                                    echo $row['contact_info'];
                                }
                                ?>
                            </td>
                        </tr>
                        <?
                        $class = ($class =='row2') ? 'row1' : 'row2';
                        } //end of while.
                    else: //not actor found!! ?> 
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
                <a href="#" onclick="return select_all(document.forms['actors'],true)">All</a>&nbsp;&nbsp;
                <a href="#" onclick="return reset_all(document.forms['actors'])">None</a>&nbsp;&nbsp;
                <a href="#" onclick="return toogle_all(document.forms['actors'],true)">Toggle</a>&nbsp;&nbsp;
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
                    onClick=' return confirm("Are you sure you want to DELETE selected actor(s)?");'>
            </td>
        </tr>
        <?
        endif;
        ?>
    </table>
</form>
