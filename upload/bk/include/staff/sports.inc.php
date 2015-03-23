<?php
$search_cond = '';
$qwhere = '';
$qstr = '&t=sports'; //Query string collector
if(isset($_REQUEST["keyword"]) == true && $_REQUEST["keyword"] != "") {
    $strSearch = $_REQUEST["keyword"];
    $search_cond = addslashes(trim($_REQUEST["keyword"]));
    $qwhere = "WHERE sport_name like '%" . $search_cond . "%' and status=1";
    $qstr .= '&keyword=' . urlencode($_REQUEST['keyword']);
} else {
    $qwhere = "WHERE status=1";
}
?>
<div class="msg" style="margin-left: 3px;">Sports</div>
<form method="get" action="admin.php?t=sports">
    <input type="hidden" name="t" value="sport_search" />
    <table>
        <tbody>
            <tr>
                <td>Keyword: </td>
                <td><input type="text" class="txt_keyword" value="<?php echo $strSearch;?>" size="30" name="keyword" id="keyword" autocomplete="off"></td>
                <td><input type="submit" value="Search" class="button" name="search"></td>
            </tr>
        </tbody>
    </table>
</form>
<?php
if(!defined('OSTADMININC') || !$thisuser->isadmin()) die('Access Denied');
$qselect = 'SELECT sport_type,sport_name ';
$qfrom = ' FROM ' . SPORT_TABLE . ' ';
//get log count based on the query so far..
$total = db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit = PAGE_LIMIT;
$page = ($_GET['p'] && is_numeric($_GET['p'])) ? $_GET['p'] : 1;  
//pagenate
$pageNav = new Pagenate($total, $page, $pagelimit);
$pageNav->setURL('admin.php', $qstr);
$query = "$qselect $qfrom $qwhere ORDER BY updated DESC,sport_name ASC LIMIT " . $pageNav->getStart() . "," . $pageNav->getLimit();
//echo $query;exit;
$result = db_query($query);
$showing = db_num_rows($resp) ? $pageNav->showing() : "";
?>
<form action="admin.php?t=sport" method="POST" name="sports" onSubmit="return checkbox_checker(document.forms['leagues'],1,0);">
    <table width="100%" border="0" cellspacing=1 cellpadding=2>
        <input type=hidden name='do' value='mass_process'>
        <tr>
            <td>
                <table border="0" cellspacing=0 cellpadding=2 class="dtable" align="center" width="100%">
                    <tr>
                        <th width="7px">&nbsp;</th>
                        <th class="align_title">Sport</th>                
                    </tr>
                    <?
                    $class = 'row1';
                    $total = 0;
                    $ids = ($errors && is_array($_POST['ids'])) ? $_POST['ids'] : null;
                    if($result && ($num = db_num_rows($result))):            
                        while ($row = db_fetch_array($result)) {                
                            $sel = false;
                            if(($ids && in_array($row['sport_type'], $ids)) && ($leagueID && $leagueID == $row['sport_type'])){
                                $class = "$class highlight";
                                $sel = true;
                            }    
                            ?>
                        <tr class="<?=$class?>" id="<?=$row['sport_type']?>">
                            <td width=7px>
                              <input type="checkbox" name="ids[]" value="<?=$row['sport_type']?>" <?=$sel ? 'checked' : ''?> 
                                        onClick="highLight(this.value,this.checked);"> </td>
                            <td><a href="admin.php?t=sport&id=<?=$row['sport_type']?>"><?=$row['sport_name']?></a></td>               
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
                <a href="#" onclick="return select_all(document.forms['sports'],true)">All</a>&nbsp;&nbsp;
                <a href="#" onclick="return reset_all(document.forms['sports'])">None</a>&nbsp;&nbsp;
                <a href="#" onclick="return toogle_all(document.forms['sports'],true)">Toggle</a>&nbsp;&nbsp;
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
                    onClick=' return confirm("Are you sure you want to DELETE selected sport(s)?");'>
            </td>
        </tr>
        <?
        endif;
        ?>
    </table>
</form>

