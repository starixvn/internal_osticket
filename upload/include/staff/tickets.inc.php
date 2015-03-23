<?php
if(!defined('OSTSCPINC') || !@$thisuser->isStaff()) die('Access Denied');

//Get ready for some deep shit..(I admit..this could be done better...but the shit just works... so shutup for now).

$qstr='&'; //Query string collector
if($_REQUEST['status']) { //Query string status has nothing to do with the real status used below; gets overloaded.
    $qstr.='status='.urlencode($_REQUEST['status']);
}

//See if this is a search
$search=$_REQUEST['a']=='search'?true:false;
$searchTerm='';
//make sure the search query is 3 chars min...defaults to no query with warning message
if($search) {
  $searchTerm=$_REQUEST['query'];
  if( ($_REQUEST['query'] && strlen($_REQUEST['query'])<3) 
      || (!$_REQUEST['query'] && isset($_REQUEST['basic_search'])) ){ //Why do I care about this crap...
      $search=false; //Instead of an error page...default back to regular query..with no search.
      $errors['err']='Search term must be more than 3 chars';
      $searchTerm='';
  }
}
$showoverdue=$showanswered=false;
$staffId=0; //Nothing for now...TODO: Allow admin and manager to limit tickets to single staff level.
//Get status we are actually going to use on the query...making sure it is clean!
$status=null;
switch(strtolower($_REQUEST['status'])){ //Status is overloaded
    case 'open':
        $status='open';
        break;
    case 'closed':
        $status='closed';
        break;
    case 'overdue':
        $status='open';
        $showoverdue=true;
        $results_type='Overdue Tickets';
        break;
    case 'assigned':
        //$status='Open'; //
        $staffId=$thisuser->getId();
        break;
    case 'answered':
        $status='open';
        $showanswered=true;
        $results_type='Answered Tickets';
        break;
    default:
        if(!$search)
            $status='open';
}

// This sucks but we need to switch queues on the fly! depending on stats fetched on the parent.
if($stats) { 
    if(!$stats['open'] && (!$status || $status=='open')){
        if(!$cfg->showAnsweredTickets() && $stats['answered']) {
             $status='open';
             $showanswered=true;
             $results_type='Answered Tickets';
        }elseif(!$stats['answered']) { //no open or answered tickets (+-queue?) - show closed tickets.???
            $status='closed';
            $results_type='Closed Tickets';
        }
    }
}

$qwhere ='';
/* DEPTS
   STRICT DEPARTMENTS BASED (a.k.a Categories) PERM. starts the where 
   if dept returns nothing...show only tickets without dept which could mean..none?
   Note that dept selected on search has nothing to do with departments allowed.
   User can also see tickets assigned to them regardless of the ticket's dept.
*/
$depts=$thisuser->getDepts(); //if dept returns nothing...show only tickets without dept which could mean..none...and display an error. huh?
if(!$depts or !is_array($depts) or !count($depts)){
    //if dept returns nothing...show only orphaned tickets (without dept) which could mean..none...and display an error.
    $qwhere =' WHERE ticket.dept_id IN ( 0 ) ';
}else if($thisuser->isadmin()){
    //user allowed acess to all departments.
    $qwhere =' WHERE 1'; // Brain fart...can not thing of a better way other than selecting all depts + 0 ..wasted query in my book?
}else{
    //limited depts....user can access tickets assigned to them regardless of the dept.
    $qwhere =' WHERE (ticket.dept_id IN ('.implode(',',$depts).') OR ticket.staff_id='.$thisuser->getId().')';
}


//STATUS
if($status){
    $qwhere.=' AND ticket.status='.db_input(strtolower($status));    
}

//Sub-statuses Trust me!
if($staffId && ($staffId==$thisuser->getId())) { //Staff's assigned tickets.
    $results_type='Assigned Tickets';
    $qwhere.=' AND ticket.staff_id='.db_input($staffId);    
}elseif($showoverdue) { //overdue
    $qwhere.=' AND isoverdue=1 ';
}elseif($showanswered) { ////Answered
    $qwhere.=' AND isanswered=1 ';
}elseif(!$search && !$cfg->showAnsweredTickets() && !strcasecmp($status,'open')) {
    $qwhere.=' AND isanswered=0 ';
}
 

//Show assigned?? Admin can not be limited. Dept managers see all tickets within the dept.
if(!$cfg->showAssignedTickets() && !$thisuser->isadmin()) {
    $qwhere.=' AND (ticket.staff_id=0 OR ticket.staff_id='.db_input($thisuser->getId()).' OR dept.manager_id='.db_input($thisuser->getId()).') ';
}


//Search?? Somebody...get me some coffee 
$deep_search=false;
if($search):
    $qstr.='&a='.urlencode($_REQUEST['a']);
    $qstr.='&t='.urlencode($_REQUEST['t']);
    if(isset($_REQUEST['advance_search'])){ //advance search box!
        $qstr.='&advance_search=Search';
    }

    //query
    if($searchTerm){
        $qstr.='&query='.urlencode($searchTerm);
        $queryterm=db_real_escape($searchTerm,false); //escape the term ONLY...no quotes.
        if(is_numeric($searchTerm)){
            $qwhere.=" AND ticket.ticketID LIKE '$queryterm%'";
        }elseif(strpos($searchTerm,'@') && Validator::is_email($searchTerm)){ //pulling all tricks!
            $qwhere.=" AND ticket.email='$queryterm'";
        }else{//Deep search!
            //This sucks..mass scan! search anything that moves! 
            
            $deep_search=true;
            if($_REQUEST['stype'] && $_REQUEST['stype']=='FT') { //Using full text on big fields.               
                $qwhere.=" AND ( ticket.email LIKE '%$queryterm%'".
                            " OR ticket.name LIKE '%$queryterm%'".
                            " OR ticket.subject LIKE '%$queryterm%'".
                            " OR note.title LIKE '%$queryterm%'".
                            " OR MATCH(message.message)   AGAINST('$queryterm')".
                            " OR MATCH(response.response) AGAINST('$queryterm')".
                            " OR MATCH(note.note) AGAINST('$queryterm')".
                            ' ) ';
                
            }else{               
                $qwhere.=" AND ( ticket.email LIKE '%$queryterm%'".
                            " OR ticket.name LIKE '%$queryterm%'".
                            " OR ticket.subject LIKE '%$queryterm%'".
                            " OR message.message LIKE '%$queryterm%'".
                            " OR response.response LIKE '%$queryterm%'".
                            " OR note.note LIKE '%$queryterm%'".
                            " OR note.title LIKE '%$queryterm%'".
                            ' ) ';
                
            }
        }
    }
    
    //department
    if($_REQUEST['dept'] && ($thisuser->isadmin() || in_array($_REQUEST['dept'],$thisuser->getDepts()))) {
    //This is dept based search..perm taken care above..put the sucker in.
        $qwhere.=' AND ticket.dept_id='.db_input($_REQUEST['dept']);
        $qstr.='&dept='.urlencode($_REQUEST['dept']);
    }
     //sport
    if($_REQUEST['sport_type'] && $thisuser->isadmin()) {
    //This is league based search..perm taken care above..put the sucker in.
        $qwhere.=' AND ticket.sport_type='.db_input($_REQUEST['sport_type']);
        $qstr.='&sport_type='.urlencode($_REQUEST['sport_type']);
    }
    //league
    if($_REQUEST['league_id'] && $thisuser->isadmin()) {
    //This is league based search..perm taken care above..put the sucker in.
        $qwhere.=' AND ticket.league='.db_input($_REQUEST['league_id']);
        $qstr.='&league='.urlencode($_REQUEST['league_id']);
    }
    //match
    if($_REQUEST['match'] && $thisuser->isadmin()) {
    //This is match based search..perm taken care above..put the sucker in.
        $qwhere.=' AND ticket.match='.db_input($_REQUEST['match']);
        $qstr.='&match='.urlencode($_REQUEST['match']);
    }
    //trader
    if($_REQUEST['trader'] && $thisuser->isadmin()) {
    //This is trader based search..perm taken care above..put the sucker in.
        $qwhere.=' AND ticket.trader='.db_input($_REQUEST['trader']);
        $qstr.='&trader='.urlencode($_REQUEST['trader']);
    }
    //suspender    
    if($_REQUEST['suspender'] && $thisuser->isadmin()) {
    //This is suspender based search..perm taken care above..put the sucker in.
        $qwhere.=' AND ticket.suspender='.db_input($_REQUEST['suspender']);
        $qstr.='&suspender='.urlencode($_REQUEST['suspender']);
    }
    //csd
    if($_REQUEST['csd'] && $thisuser->isadmin()) {
    //This is csd based search..perm taken care above..put the sucker in.
        $qwhere.=' AND ticket.csd='.db_input($_REQUEST['csd']);
        $qstr.='&csd='.urlencode($_REQUEST['csd']);
    }
    //dates of ticket
    $startTime  =($_REQUEST['startDate_ticket'] && (strlen($_REQUEST['startDate_ticket'])>=8))?Misc::dbtime($_REQUEST['startDate_ticket']):0;
    $endTime    =($_REQUEST['endDate_ticket'] && (strlen($_REQUEST['endDate_ticket'])>=8))?Misc::dbtime($_REQUEST['endDate_ticket'].' 23:59'):0;
    if( ($startTime && $startTime>time()) or ($startTime>$endTime && $endTime>0)){
        $errors['err']='Entered date span is invalid. Selection ignored.';
        $startTime=$endTime=0;
    }else{
        //Have fun with dates.
        if($startTime){
            $qwhere.=' AND ticket.created>=FROM_UNIXTIME('.$startTime.')';
            $qstr.='&startDate_ticket='.urlencode($_REQUEST['startDate_ticket']);
                        
        }
        if($endTime){
            $qwhere.=' AND ticket.created<=FROM_UNIXTIME('.$endTime.')';
            $qstr.='&endDate_ticket='.urlencode($_REQUEST['endDate_ticket']);
        }
    }
    //dates of event date for Match
    $startTime_event  =($_REQUEST['startDate_event'] && (strlen($_REQUEST['startDate_event'])>=8))?Misc::dbtime($_REQUEST['startDate_event']):0;
    $endTime_event    =($_REQUEST['endDate_event'] && (strlen($_REQUEST['endDate_event'])>=8))?Misc::dbtime($_REQUEST['endDate_event'].' 23:59'):0;
    
    if($startTime_event>$endTime_event && $endTime_event>0){
        $errors['err']='Entered date span is invalid. Selection ignored.';
        $startTime_event=$endTime_event=0;
    }else{
        //Have fun with dates.
        if($startTime_event){
            $qwhere.=' AND `ma`.`event_date`>=FROM_UNIXTIME('.$startTime_event.')';
            $qstr.='&startDate_event='.urlencode($_REQUEST['startDate_event']);
                        
        }
        if($endTime_event){
            $qwhere.=' AND `ma`.`event_date`<=FROM_UNIXTIME('.$endTime_event.')';
            $qstr.='&endDate_event='.urlencode($_REQUEST['endDate_event']);
        }
}

endif;

//I admit this crap sucks...but who cares??
$sortOptions=array('date'=>'ticket.created','ID'=>'ticketID','pri'=>'priority_urgency','dept'=>'dept_name');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');

//Sorting options...
if($_REQUEST['sort']) {
    $order_by =$sortOptions[$_REQUEST['sort']];
}
if($_REQUEST['order']) {
    $order=$orderWays[$_REQUEST['order']];
}
if($_GET['limit']){
    $qstr.='&limit='.urlencode($_GET['limit']);
}
if(!$order_by && $showanswered) {
    $order_by='ticket.lastresponse DESC, ticket.created'; //No priority sorting for answered tickets.
}elseif(!$order_by && !strcasecmp($status,'closed')){
    $order_by='ticket.closed DESC, ticket.created'; //No priority sorting for closed tickets.
}


$order_by =$order_by?$order_by:'priority_urgency,effective_date DESC ,ticket.created';
$order=$order?$order:'DESC';
$pagelimit=$_GET['limit']?$_GET['limit']:$thisuser->getPageLimit();
$pagelimit=$pagelimit?$pagelimit:PAGE_LIMIT; //true default...if all fails.
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;

// customized osticket
/*
$qselect = 'SELECT DISTINCT ticket.ticket_id,lock_id,ticketID,ticket.dept_id,ticket.staff_id,subject,name,email,dept_name '.
           ',ticket.status,ticket.source,isoverdue,isanswered,ticket.created,pri.* ,count(attach.attach_id) as attachments ';
 */
$qselect = "SELECT DISTINCT ticket.ticket_id,lock_id,ticketID,ticket.dept_id,ticket.staff_id,subject,league,`match`,trader,suspender,csd,name,email,dept_name ".
           ",ticket.status,ticket.source,isoverdue,isanswered,ticket.created,pri.* ,count(attach.attachment_id) as attachments" .
            ", CASE ticket.staff_id
                WHEN 0
                THEN ('')
                ELSE (
                SELECT CONCAT(firstname,' ',lastname) AS staff_name FROM " . STAFF_TABLE . " AS staff WHERE staff.staff_id=ticket.staff_id
                )
               END AS staff_name";
 // end
$qfrom=' FROM '.TICKET_TABLE.' ticket '.
       ' LEFT JOIN '.DEPT_TABLE.' `dept` ON `ticket`.`dept_id`=`dept`.`dept_id` '.
       ' LEFT JOIN '.MATCH_TABLE.' `ma` ON ticket.match=`ma`.`match_id` '.
       ' LEFT JOIN '.ATTACHMENT_TABLE.' `attach` ON `ticket`.`ticket_id`=`attach`.`ticket_id` ';

if($search && $deep_search) {
    $qfrom.=' LEFT JOIN '.TICKET_MESSAGE_TABLE.' message ON (ticket.ticket_id=message.ticket_id )';
    $qfrom.=' LEFT JOIN '.TICKET_RESPONSE_TABLE.' response ON (ticket.ticket_id=response.ticket_id )';
    $qfrom.=' LEFT JOIN '.TICKET_NOTE_TABLE.' note ON (ticket.ticket_id=note.ticket_id )';
}

$qgroup=' GROUP BY ticket.ticket_id';
//get ticket count based on the query so far..
$total=db_count("SELECT count(DISTINCT ticket.ticket_id) $qfrom $qwhere");
//pagenate
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('tickets.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));
//
//Ok..lets roll...create the actual query
//ADD attachment,priorities and lock crap
$qselect.=' , IF(ticket.reopened is NULL,ticket.created,ticket.reopened) as effective_date';
$qfrom.=' LEFT JOIN '.TICKET_PRIORITY_TABLE.' pri ON ticket.priority_id=pri.priority_id '.
        ' LEFT JOIN '.TICKET_LOCK_TABLE.' tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW() ';
        //' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  ticket.ticket_id=attach.ticket_id ';

$query="$qselect $qfrom $qwhere $qgroup ORDER BY $order_by $order LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
$tickets_res = db_query($query);
$showing=db_num_rows($tickets_res)?$pageNav->showing():"";
if(!$results_type) {
    $results_type=($search)?'Search Results':ucfirst($status).' Tickets';
}
$negorder=$order=='DESC'?'ASC':'DESC'; //Negate the sorting..

//Permission  setting we are going to reuse.
$canDelete=$canClose=false;
$canDelete=$thisuser->canDeleteTickets();
$canClose=$thisuser->canCloseTickets();
$basic_display=!isset($_REQUEST['advance_search'])?true:false;

//YOU BREAK IT YOU FIX IT.
?>
<div>
    <?if($errors['err']) {?>
        <p align="center" id="errormessage"><?=$errors['err']?></p>
    <?}elseif($msg) {?>
        <p align="center" id="infomessage"><?=$msg?></p>
    <?}elseif($warn) {?>
        <p id="warnmessage"><?=$warn?></p>
     <?}?>
</div>
<!-- SEARCH FORM START -->
<div id='basic' style="display:<?=$basic_display?'block':'none'?>">
    <form action="tickets.php" method="get">
    <input type="hidden" name="a" value="search">
    <table>
        <tr>
            <td>Query: </td>
            <td><input type="text" id="query" class="txt_keyword" name="query" size=30 value="<?=Format::htmlchars($_REQUEST['query'])?>"></td>
            <td><input type="submit" name="basic_search" class="button" value="Search">
             &nbsp;[<a href="#" onClick="showHide('basic','advance'); return false;">Advanced</a> ] </td>
        </tr>
    </table>
    </form>
</div>
<div id='advance' style="display:<?=$basic_display?'none':'block'?>">
 <form action="tickets.php" method="get">
 <input type="hidden" name="a" value="search">
  <table>
    <tr>
        <td>Query: </td><td><input type="text" id="query" class="txt_keyword" name="query" value="<?=Format::htmlchars($_REQUEST['query'])?>"></td>
        <td>Dept:</td>
        <td><select name="dept" class="select_search"><option value=0>All Departments</option>
            <?
                //Showing only departments the user has access to...
                $sql='SELECT dept_id,dept_name FROM '.DEPT_TABLE;
                if(!$thisuser->isadmin())
                    $sql.=' WHERE dept_id IN ('.implode(',',$thisuser->getDepts()).')';
                
                $depts= db_query($sql);
                while (list($deptId,$deptName) = db_fetch_row($depts)){
                $selected = ($_GET['dept']==$deptId)?'selected':''; ?>
                <option value="<?=$deptId?>"<?=$selected?>><?=$deptName?></option>
            <?
            }?>
            </select>
        </td>
        <td>Status is:</td><td>
    
        <select name="status" class="select_search">
            <option value='any' selected >Any status</option>
            <option value="open" <?=!strcasecmp($_REQUEST['status'],'Open')?'selected':''?>>Open</option>
            <option value="overdue" <?=!strcasecmp($_REQUEST['status'],'overdue')?'selected':''?>>Overdue</option>
            <option value="closed" <?=!strcasecmp($_REQUEST['status'],'Closed')?'selected':''?>>Closed</option>
        </select>
        </td>
     </tr>
     
     <tr>
        <td>Sport: </td>
        <td>
            <select name="sport_type" id="sport_type" data-placeholder="Select Sport" class="sport-select select_search"> 
                <option value=0>All Sports</option>
                <?php
                $query="SELECT sport_type,sport_name FROM " . SPORT_TABLE ." WHERE status=1 ORDER BY updated DESC, sport_name ASC";                
                $result_sport = db_query($query);
                 if($result_sport && db_num_rows($result_sport)) {            
                    while ($row_sport = db_fetch_array($result_sport)) {
                        $selected = ($_GET['sport_type']==$row_sport['sport_type'])?'selected':'';
                ?>
                <option value="<?=$row_sport['sport_type']?>"<?=$selected?>><?=$row_sport['sport_name']?></option>
                <?php
                    }
                 }
                ?>                
            </select>
        </td>
        <td>League: </td>
        <td>
            <select name="league_id" id="league_id" data-placeholder="Select League" class="league-select select_search">                
                    <option value=0>All Leagues</option>
                    <?php
                    $query="SELECT league_id,league_name FROM " . LEAGUE_TABLE ." WHERE status=1 AND sport_type='" . $_GET['sport_type'] . "' ORDER BY updated DESC, league_name ASC";                
                    $result_league = db_query($query);
                     if($result_league && db_num_rows($result_league)) {            
                        while ($row_league = db_fetch_array($result_league)) {
                            $selected = ($_GET['league_id']==$row_league['league_id'])?'selected':'';
                    ?>
                    <option value="<?=$row_league['league_id']?>"<?=$selected?>><?=$row_league['league_name']?></option>
                    <?php
                        }
                     }
                    ?>     
            </select>
            
        </td>
        <td>Match:</td>
        <td>
            <select name="match" id="match" data-placeholder="Select Match" class="match-select select_search">
                <option value=0>All Matches</option>
                <?
                //Showing matches
                $sql= "SELECT match_id,event_date ".
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
                        "FROM " . MATCH_TABLE ." AS `ma` WHERE status=1 AND `ma`.sport_type='" . $_GET['sport_type'] . "' AND `ma`.league_id='" . $_GET['league_id'] . "' ORDER BY updated DESC";               
                $matches= db_query($sql);
                while (list($matchId,$event_date,$home_team,$away_team) = db_fetch_row($matches)){
                    $matchName = $home_team . ' - vs - ' . $away_team . ' ( ' . Format::db_date($event_date) . ' )';;
                        $selected = ($_GET['match']==$matchId)?'selected':'';
                ?>
                <option value="<?=$matchId?>"<?=$selected?>><?=$matchName?></option>
                <?
                }
                ?>
            </select>
        </td>
        
     </tr>
     <tr>
        <td>Trader:</td>
        <td>    
            <select name="trader" class="select_search">
                <option value=0>All Traders</option>
            <?
                //Showing trader
                $sql='SELECT actor_id,actor_name FROM '.ACTOR_TABLE.' WHERE status=1 and type=1';                
                $traders= db_query($sql);
                while (list($traderId,$traderName) = db_fetch_row($traders)){
                $selected = ($_GET['trader']==$traderId)?'selected':'';
            ?>
                <option value="<?=$traderId?>"<?=$selected?>><?=$traderName?></option>
            <?
            }
            ?>
            </select>
        </td>
        <td>Suspender: </td>
        <td>
            <select name="suspender" class="select_search"><option value=0>All Suspender</option>
            <?
                //Showing Suspender
                $sql='SELECT actor_id,actor_name FROM '.ACTOR_TABLE.' WHERE status=1 and type=2';                
                $suspenders= db_query($sql);
                while (list($suspenderId,$suspenderName) = db_fetch_row($suspenders)){
                $selected = ($_GET['suspender']==$suspenderId)?'selected':'';
            ?>
                <option value="<?=$suspenderId?>"<?=$selected?>><?=$suspenderName?></option>
            <?
            }
            ?>
            </select>
        </td>
        <td>CSD:</td>
        <td>
           <select name="csd" class="select_search"><option value=0>All CSD</option>
           <?
                //Showing CSD
                $sql='SELECT actor_id,actor_name FROM '.ACTOR_TABLE.' WHERE status=1 and type=3';                
                $csds= db_query($sql);
                while (list($csdId,$csdName) = db_fetch_row($csds)){
                $selected = ($_GET['csd']==$csdId)?'selected':'';
            ?>
                <option value="<?=$csdId?>"<?=$selected?>><?=$csdName?></option>
            <?
            }
            ?>
            </select>
        </td>       
     </tr>
    </table>
    <table>
        <tr>
            <td>
        Event Date:
        &nbsp;From&nbsp;&nbsp;<input id="sd_event" name="startDate_event" class="txt_date" value="<?=Format::htmlchars($_REQUEST['startDate_event'])?>" 
                onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF>
            <a href="#" onclick="event.cancelBubble=true;calendar(getObj('sd_event')); return false;"><img src='images/cal.png'border=0 alt=""></a>
            &nbsp;&nbsp; to &nbsp;&nbsp;
            <input id="ed_event" name="endDate_event" class="txt_date" value="<?=Format::htmlchars($_REQUEST['endDate_event'])?>" 
                onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF >
                <a href="#" onclick="event.cancelBubble=true;calendar(getObj('ed_event')); return false;"><img src='images/cal.png'border=0 alt=""></a>
            &nbsp;&nbsp;
            </td>
            
            <td>
        Date Span:
        &nbsp;From&nbsp;&nbsp;<input id="sd" name="startDate_ticket" class="txt_date" value="<?=Format::htmlchars($_REQUEST['startDate_ticket'])?>" 
                onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF>
            <a href="#" onclick="event.cancelBubble=true;calendar(getObj('sd')); return false;"><img src='images/cal.png'border=0 alt=""></a>
            &nbsp;&nbsp; to &nbsp;&nbsp;
            <input id="ed" name="endDate_ticket" class="txt_date" value="<?=Format::htmlchars($_REQUEST['endDate_ticket'])?>" 
                onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF >
                <a href="#" onclick="event.cancelBubble=true;calendar(getObj('ed')); return false;"><img src='images/cal.png'border=0 alt=""></a>
            &nbsp;&nbsp;
            </td>
        </tr>        
    </table>
    <table>
    <tr>
       <td>Type:</td>
       <td>       
        <select name="stype" class="select_search short">
            <option value="LIKE" <?=(!$_REQUEST['stype'] || $_REQUEST['stype'] == 'LIKE') ?'selected':''?>>Scan (%)</option>
            <option value="FT"<?= $_REQUEST['stype'] == 'FT'?'selected':''?>>Fulltext</option>
        </select>
 

       </td>
       <td>Sort by:</td><td>
        <? 
         $sort=$_GET['sort']?$_GET['sort']:'date';
        ?>
        <select name="sort" class="select_search short">
    	    <option value="ID" <?= $sort== 'ID' ?'selected':''?>>Ticket #</option>
            <option value="pri" <?= $sort == 'pri' ?'selected':''?>>Priority</option>
            <option value="date" <?= $sort == 'date' ?'selected':''?>>Date</option>
            <option value="dept" <?= $sort == 'dept' ?'selected':''?>>Dept.</option>
        </select>
        <select name="order" class="select_search short">
            <option value="DESC"<?= $_REQUEST['order'] == 'DESC' ?'selected':''?>>Descending</option>
            <option value="ASC"<?= $_REQUEST['order'] == 'ASC'?'selected':''?>>Ascending</option>
        </select>
       </td>
        <td>Results Per Page:</td><td>
        <select name="limit">
        <?
         $sel=$_REQUEST['limit']?$_REQUEST['limit']:15;
         for ($x = 5; $x <= 25; $x += 5) {?>
            <option  value="<?=$x?>" <?=($sel==$x )?'selected':''?>><?=$x?></option>
        <?}?>
        </select>
     </td>
     <td>
     <input type="submit" name="advance_search" class="button" value="Search">
       &nbsp;[ <a href="#" onClick="showHide('advance','basic'); return false;" >Basic</a> ]
    </td>
  </tr>
 </table>
 </form>
</div>
<script type="text/javascript">

    var options = {
        script:"ajax.php?api=tickets&f=search&limit=10&",
        varname:"input",
        shownoresults:false,
        maxresults:10,
        callback: function (obj) { document.getElementById('query').value = obj.id; document.forms[0].submit();}
    };
    var autosug = new bsn.AutoSuggest('query', options);
</script>
<!-- SEARCH FORM END -->
<div style="margin-bottom:20px">
    <table width="100%" border="0" cellspacing=0 cellpadding=0 align="center" class="div_templates_showing">
    <tr>
        <td width="80%" class="msg" ><b><?=$showing?>&nbsp;<?=$results_type?></b></td>
        <td nowrap style="text-align:right;padding-right:20px;">
            <a href=""><img src="images/refresh.gif" alt="Refresh" border=0></a>
        </td>
    </tr>
 </table>
 <table width="100%" border="0" cellspacing=1 cellpadding=2>
    <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
    <input type="hidden" name="a" value="mass_process" >
    <input type="hidden" name="status" value="<?=$statusss?>" >
    <tr><td>
       <table width="100%" border="0" cellspacing=0 cellpadding=2 class="dtable" align="center">
        <tr>
            <?if($canDelete || $canClose) {?>
	        <th width="8px">&nbsp;</th>
            <?}?>
	        <th width="70" >
                <a href="tickets.php?sort=ID&order=<?=$negorder?><?=$qstr?>" title="Sort By Ticket ID <?=$negorder?>">Ticket</a></th>
	        <th width="70">
                <a href="tickets.php?sort=date&order=<?=$negorder?><?=$qstr?>" title="Sort By Date <?=$negorder?>">Date</a></th>
	        <th width="280">Subject</th>  
	        <th width="120">
                <a href="tickets.php?sort=dept&order=<?=$negorder?><?=$qstr?>" title="Sort By Category <?=$negorder?>">Department</a></th>
	        <th width="70">
                <a href="tickets.php?sort=pri&order=<?=$negorder?><?=$qstr?>" title="Sort By Priority <?=$negorder?>">Priority</a></th>
                <th width="180" >From</th>
                <th width="180" >Assign To</th>
        </tr>
        <?
        $class = "row1";
        $total=0;
        if($tickets_res && ($num=db_num_rows($tickets_res))):
            while ($row = db_fetch_array($tickets_res)) {
           
                $tag=$row['staff_id']?'assigned':'openticket';
                $flag=null;
                if($row['lock_id'])
                    $flag='locked';
                elseif($row['staff_id'])
                    $flag='assigned';
                elseif($row['isoverdue'])
                    $flag='overdue';

                $tid=$row['ticketID'];
                $subject = Format::truncate($row['subject'],40);
                if(!strcasecmp($row['status'],'open') && !$row['isanswered'] && !$row['lock_id']) {
                    $tid=sprintf('<b>%s</b>',$tid);
                    //$subject=sprintf('<b>%s</b>',Format::truncate($row['subject'],40)); // Making the subject bold is too much for the eye
                }
                ?>
            <tr class="<?=$class?> " id="<?=$row['ticket_id']?>">
                <?if($canDelete || $canClose) {?>
                <td align="center" class="nohover" width="7px">
                    <input type="checkbox" name="tids[]" value="<?=$row['ticket_id']?>" onClick="highLight(this.value,this.checked);">
                </td>
                <?}?>
                <td align="center" title="<?=$row['email']?>" nowrap>
                  <a class="Icon <?=strtolower($row['source'])?>Ticket" title="<?=$row['source']?> Ticket: <?=$row['email']?>" 
                    href="tickets.php?id=<?=$row['ticket_id']?>"><?=$tid?></a></td>
                <td align="center" nowrap><?=Format::db_date($row['created'])?></td>
                <td><a <?if($flag) { ?> class="Icon <?=$flag?>Ticket" title="<?=ucfirst($flag)?> Ticket" <?}?> 
                    href="tickets.php?id=<?=$row['ticket_id']?>"><?=$subject?></a>
                    &nbsp;<?=$row['attachments']?"<span class='Icon file'>&nbsp;</span>":''?></td>                
                
                <td nowrap><?=Format::truncate($row['dept_name'],30)?></td>
                <td class="nohover" align="center" style="background-color:<?=$row['priority_color']?>;"><?=$row['priority_desc']?></td>
                <td nowrap><?=Format::truncate($row['name'],22,strpos($row['name'],'@'))?>&nbsp;</td>
                <td nowrap align="center"><?=$row['staff_name']?></td>
                
            </tr>
            <?
            $class = ($class =='row2') ?'row1':'row2';
            } //end of while.
        else: //not tickets found!! ?> 
            <tr class="<?=$class?>"><td colspan=8><b>Query returned 0 results.</b></td></tr>
        <?
        endif; ?>
       </table>
    </td></tr>
    <?
    if($num>0){ //if we actually had any tickets returned.
    ?>
        <tr><td class="div_select">
            <?if($canDelete || $canClose) { ?>
            Select:
                <a href="#" onclick="return select_all(document.forms['tickets'],true)">All</a>&nbsp;
                <a href="#" onclick="return reset_all(document.forms['tickets'])">None</a>&nbsp;
                <a href="#" onclick="return toogle_all(document.forms['tickets'],true)">Toggle</a>&nbsp;
            <?}?>
            
        </td></tr>
        <tr><td>Page:<?=$pageNav->getPageLinks()?></td></tr>
        <? if($canClose or $canDelete) { ?>
        <tr><td align="center"> <br>
            <?
            $status=$_REQUEST['status']?$_REQUEST['status']:$status;

            //If the user can close the ticket...mass reopen is allowed.
            //If they can delete tickets...they are allowed to close--reopen..etc.
            switch (strtolower($status)) {
                case 'closed': ?>
                    <input class="button" type="submit" name="reopen" value="Reopen"
                        onClick=' return confirm("Are you sure you want to reopen selected tickets?");'>
                    <?
                    break;
                case 'open':
                case 'answered':
                case 'assigned':
                    ?>
                    <input class="button" type="submit" name="overdue" value="Overdue"
                        onClick=' return confirm("Are you sure you want to mark selected tickets overdue/stale?");'>
                    <input class="button" type="submit" name="close" value="Close"
                        onClick=' return confirm("Are you sure you want to close selected tickets?");'>
                    <?
                    break;
                default: //search??
                    ?>
                    <input class="button" type="submit" name="close" value="Close"
                        onClick=' return confirm("Are you sure you want to close selected tickets?");'>
                    <input class="button" type="submit" name="reopen" value="Reopen"
                        onClick=' return confirm("Are you sure you want to reopen selected tickets?");'>
            <?
            }
            if($canDelete) {?>
                <input class="button" type="submit" name="delete" value="Delete" 
                    onClick=' return confirm("Are you sure you want to DELETE selected tickets?");'>
            <?}?>
        </td></tr>
        <? }
    } ?>
    </form>
 </table>
</div>
<script>
    $(function(){
        // auto complate word for selectbox 'sport'
        $(".sport-select").chosen();
        $(".sport-select-deselect").chosen({allow_single_deselect:true});
        
        // auto complate word for selectbox 'league'
        $(".league-select").chosen();
        $(".league-select-deselect").chosen({allow_single_deselect:true});
        
         // auto complate word for selectbox 'match'
        $(".match-select").chosen();
        $(".match-select-deselect").chosen({allow_single_deselect:true});
        
        $("#sport_type").bind("change", function() { 
            // clear all leagues, matches
            $('#league_id').find('option').remove();            
            $("#league_id").append('<option value=0>All Leagues</option>');
            $('#match').find('option').remove(); 
            $('#match').append('<option value=0>All Matches</option>');
            var sport_type = $('#sport_type').val();
            $.ajax({
                type:"GET",
                url: "../loadAjax.php",
                data: {sport_type:sport_type, type:'sport_league'},
                dataType: "json",
                success: function(data){
                    if(data.length > 0) {
                        $.each(data, function(key, value){ 
                            $("#league_id").append('<option value="' + value.league_id + '">' + value.league_name + '</option>');                                      
                        });
                       $(".league-select").trigger("liszt:updated");
                    } 
                    $(".league-select").trigger("liszt:updated");
                    $(".match-select").trigger("liszt:updated");
                },  
                error: function(){ 
                    $(".league-select").trigger("liszt:updated");
                    $(".match-select").trigger("liszt:updated");
                }  
            });
        })
        $("#league_id").bind("change", function() { 
            // clear all leagues
            $('#match').find('option').remove(); 
            // clear all matches
            $('#match').find('option').remove();
            $("#match").append('<option value=0>All Matches</option>');
            var league_id = $('#league_id').val();
            var sport_type = $('#sport_type').val();
            $.ajax({
                type:"GET",
                url: "../loadAjax.php",
                data: {league_id:league_id, sport_type:sport_type, type:'change_league'},
                dataType: "json",
                success: function(data){
                    if(data.length > 0) {
                        $.each(data, function(key, value){
                            $("#match").append('<option value="' + value.match_id + '">' + value.match_name + '</option>');   
                        });
                    }
                    $(".match-select").trigger("liszt:updated");
                },  
                error: function(){  
                    $(".match-select").trigger("liszt:updated");
                }  
            });
        })
        
    })
</script>
<?
