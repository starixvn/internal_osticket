<link href="css/jquery-ui-1.9.2.custom.css" rel="stylesheet">
<!-- attachment -->
<link href="css/fineuploader.css" rel="stylesheet">

<script src="js/header.js"></script>
<script src="js/util.js"></script>
<script src="js/button.js"></script>
<script src="js/handler.base.js"></script>
<script src="js/handler.form.js"></script>
<script src="js/handler.xhr.js"></script>
<script src="js/uploader.basic.js"></script>
<script src="js/dnd.js"></script>
<script src="js/uploader.js"></script>
<script type="text/javascript" src="js/jquery.validate.js" charset="utf-8"></script>
<?php
if(!defined('OSTSCPINC') || !is_object($ticket) || !is_object($thisuser) || !$thisuser->isStaff()) die('Access Denied');

if(!($thisuser->canEditTickets() || ($thisuser->isManager() && $ticket->getDeptId()==$thisuser->getDeptId()))) die('Access Denied. Perm error.');

if($_POST && $errors){
    $info=Format::input($_POST);
}else{
    // customized osticket
    /*
    $info=array('email'=>$ticket->getEmail(),
                'name' =>$ticket->getName(),
                'phone'=>$ticket->getPhone(),
                'phone_ext'=>$ticket->getPhoneExt(),
                'pri'=>$ticket->getPriorityId(),
                'topicId'=>$ticket->getTopicId(),
                'topic'=>$ticket->getHelpTopic(),
                'subject' =>$ticket->getSubject(),
                'duedate' =>$ticket->getDueDate()?(Format::userdate('m/d/Y',Misc::db2gmtime($ticket->getDueDate()))):'',
                'time'=>$ticket->getDueDate()?(Format::userdate('G:i',Misc::db2gmtime($ticket->getDueDate()))):'',
                );    
     */
    $info=array('email'=>$ticket->getEmail(),
                'name' =>$ticket->getName(),
                'phone'=>$ticket->getPhone(),
                'phone_ext'=>$ticket->getPhoneExt(),
                'pri'=>$ticket->getPriorityId(),
                'topicId'=>$ticket->getTopicId(),
                'topic'=>$ticket->getHelpTopic(),
                'subject' =>$ticket->getSubject(),
                'sport_type' =>$ticket->getSportType(),
                'league' =>$ticket->getLeague(),
                'match' =>$ticket->getMatch(),
                'trader' =>$ticket->getTrader(),
                'suspender' =>$ticket->getSuspender(),
                'csd' =>$ticket->getCsd(),  
                'provider' =>$ticket->getProvider(),
                'duedate' =>$ticket->getDueDate()?(Format::userdate('m/d/Y',Misc::db2gmtime($ticket->getDueDate()))):'',
                'time'=>$ticket->getDueDate()?(Format::userdate('G:i',Misc::db2gmtime($ticket->getDueDate()))):'',
                );
    
    // end
    /*Note: Please don't make me explain how dates work - it is torture. Trust me! */
}

?>
<div width="100%">
    <?if($errors['err']) {?>
        <p align="center" id="errormessage"><?=$errors['err']?></p>
    <?}elseif($msg) {?>
        <p align="center" class="infomessage"><?=$msg?></p>
    <?}elseif($warn) {?>
        <p class="warnmessage"><?=$warn?></p>
    <?}?>
</div>
<form action="tickets.php?id=<?=$ticket->getId()?>" method="post" id="frm_ticket">
    <input type='hidden' name='id' value='<?=$ticket->getId()?>'>
    <input type='hidden' name='a' value='update'>
    <table width="100%" border="0" cellspacing=1 cellpadding=2 class="front_layer">
        <tr><td align="left" colspan=2 class="msg">
            Update Ticket #<?=$ticket->getExtId()?>&nbsp;&nbsp;(<a href="tickets.php?id=<?=$ticket->getId()?>" style="color:black;">View Ticket</a>)<br></td></tr>

        <tr>
            <td align="left" class="sync_data"><b>Full Name:</b></td>
            <td>
                <input type="text" id="name" class="required" name="name" maxlength="32" size="25" value="<?=$info['name']?>">
                &nbsp;<label class="error"><b>*</b>&nbsp;<?=$errors['name']?></label>
            </td>
        </tr>
        <tr>
            <td align="left" nowrap width="120">Email Address:</td>
            <td>
                <input type="text" id="email" name="email" size="25" maxlength="120" value="<?=$info['email']?>">
                &nbsp;<label class="error"><?=$errors['email']?></label>
            </td>
        </tr>
        <tr>
            <td align="left"><b>Subject:</b></td>
            <td>
                <input type="text" name="subject" id="subject" class="required" size="35" maxlength="64" value="<?=$info['subject']?>">
                &nbsp;<label class="error">*&nbsp;<?=$errors['subject']?></label>
            </td>
        </tr>
        <!--Custumized osticket-->
        <tr>
            <td colspan="2" style="padding: 0px;">
                <table class="sync_data_select" style="width:656px">
                    <tr>
                        <td class="sync_data">Update Latest Data of IBC Server:</td>
                        <td class="select_sync">
                            <select id="sync" name="sync">                               
                                <option value="0">All</option>
                                <option value="1">Sports</option>
                                <option value="2">Leagues</option>
                                <option value="3">Teams</option>
                                <option value="4">Matches</option>
                             </select>                        
                        </td>
                        <td>
                            <a href="javascript:void(0)" id="syncData"><img src="images/icons/sync_1.png" width="16" height="16" alt="Udate" title="Update" id="img_sync"/></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left"><b>Sport:</b></td>
            <td>
                <select name="sport_type" id="sport" data-placeholder="Select Sport" class="sport-select" style="width:450px;" tabindex="2">
                    <option value="0"></option>
                    <?php
                    $query="SELECT sport_type,sport_name FROM " . SPORT_TABLE ." WHERE status=1 ORDER BY updated DESC, sport_name ASC";                
                    $result_sport = db_query($query);
                     if($result_sport && db_num_rows($result_sport)) {            
                        while ($row_sport = db_fetch_array($result_sport)) {     
                    ?>
                    <option value="<?=$row_sport['sport_type']?>" <?php if($info['sport_type'] == $row_sport['sport_type']) echo 'selected="selected"';?>><?=$row_sport['sport_name'];?></option>
                    <?php
                        }
                     }
                    ?>                     
                </select>    
                <label class="error_js" id="mess_sport"></label>
                &nbsp;<label class="error">*&nbsp;&nbsp;<?= $errors['sport_type'] ?></label>

                <!-- ui-dialog -->
                <!--<div id="dialog_sport" title="Add New Sport">
                    <table class="td_txt_left">
                        <tr>
                            <td>Name:</td>
                            <td><input type="text" name="sport_name" size=25 value="" id="sport_name" maxlength="255"></td>
                        </tr>
                    </table>
                </div>-->
            </td>
        </tr>
        <tr>
            <td align="left"><b>League:</b></td>
            <td>
                <select name="league" id="league" data-placeholder="Select League" class="league-select" style="width:450px;" tabindex="2">
                    <option value="0"></option>
                    <?php
                        $objLeague = new League();
                        if(isset($_POST['league']) == false) {
                            $arrLeague = $objLeague->getInfoById($info['league']);
                            $intIbcLeagueId = $arrLeague['ibc_league_id'];
                        } else {
                            $intIbcLeagueId = $info['league'];
                        }
                        $objIbcLeague = new IBC_League();
                        $arrIbcLeague = $objIbcLeague->getInfoById($intIbcLeagueId);                 
                        if($arrIbcLeague == null) {
                            $strSelected = '';
                            if($intIbcLeagueId == $arrLeague['ibc_league_id']) {
                                 $strSelected = 'selected="selected"';
                            }
                            echo '<option value="' . $arrLeague['ibc_league_id'] . '" ' . $strSelected . '>' . $arrLeague['league_name'] . '</option>';
                        }
                        $query = "SELECT league_id,league_name FROM " . IBC_LEAGUE_TABLE . " WHERE sport_type='" . $info['sport_type'] . "' ORDER BY league_name ASC";
                        $result_league = db_query($query);
                        if ($result_league && db_num_rows($result_league)) {
                            while ($row_league = db_fetch_array($result_league)) {
                                ?>
                                <option value="<?= $row_league['league_id'] ?>" <?php if ($intIbcLeagueId == $row_league['league_id']) echo 'selected="selected"'; ?>><?= $row_league['league_name'] ?></option>
                                <?php
                            }
                        }

                    ?>
                </select>    
                <label class="error_js" id="mess_league"></label>
                &nbsp;<label class="error">*&nbsp;&nbsp;<?= $errors['league'] ?></label>

                <!-- ui-dialog -->
                <!--<div id="dialog_league" title="Add New League">
                    <input type="hidden" id="sport_type_select" value="" />
                    <table class="td_txt_left">
                        <tr>
                            <td>Sport:</td>
                            <td>
                                <span id="selected_sport_name"></span>
                                <input type="hidden" value="" id="selected_sport_type"/>
                            </td>
                        </tr>
                        <tr>
                            <td>League Name:</td>
                            <td><input type="text" name="league_name" size=25 value="" id="league_name" maxlength="255"></td>
                        </tr>                        
                        <tr>
                            <td>IBC League ID:</td>
                            <td><input type="text" name="ibc_league_id" size=25 value="" id="ibc_league_id" maxlength="255"></td>
                        </tr>
                    </table>
                </div>-->
            </td>
        </tr>
        <tr>
            <td align="left">Event Date:</td>
            <td>
                From&nbsp;&nbsp;
                <input id="sd" name="startDate" style="width: 100px" class="txt_keyword" value="" 
                       onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF>
                <a href="#" onclick="event.cancelBubble=true;calendar(getObj('sd')); return false;"><img src='images/cal.png'border=0 alt=""></a>
                &nbsp;&nbsp; to &nbsp;&nbsp;
                <input id="ed" name="endDate" style="width: 100px" class="txt_keyword" value="" 
                    onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF >
                    <a href="#" onclick="event.cancelBubble=true;calendar(getObj('ed')); return false;"><img src='images/cal.png'border=0 alt=""></a>
                &nbsp;&nbsp;
                <input type="button" id="filter" value="Filter Match List" name="submit_x" class="button">  
            </td>
        </tr>
        <tr>
            <td align="left"><b>Match:</b></td>
            <td> 
                <select name="match" id="match" data-placeholder="Select Match" class="match-select" style="width:450px;" tabindex="2">
                    <option value="0"></option>
                    <?php
                        $objMatch = new Match();
                        if(isset($_POST['match']) == false) {
                            $arrMatch = $objMatch->getInfoById($info['match']);
                            $intIbcMatchId = $arrMatch['ibc_match_id'];
                        } else {
                            $intIbcMatchId = $info['match'];
                        }                    
                        $objIbcMatch = new IBC_Match();
                        $arrIbcMatch = $objIbcMatch->getInfoById($intIbcMatchId);                 
                        if($arrIbcMatch == null) {
                            // get match name
                            $objTeam = new Team();
                            $arrHomeTeam = $objTeam->getInfoById($arrMatch['home_id']);
                            $strHomeTeam = $arrHomeTeam['team_name'];
                            $arrAwayTeam = $objTeam->getInfoById($arrMatch['away_id']);
                            $strAwayTeam = $arrAwayTeam['team_name'];
                            $strMatchName = $strHomeTeam . ' - vs - ' . $strAwayTeam . ' [' . $arrMatch['match_code'] . '] ( ' . Format::db_date($arrMatch['event_date']) . ' )';

                            $strSelected = '';
                            if($intIbcMatchId == $arrMatch['ibc_match_id']) {
                                 $strSelected = 'selected="selected"';
                            }
                            echo '<option value="' . $arrMatch['ibc_match_id'] . '" ' . $strSelected . '>' . $strMatchName . '</option>';
                        }
                        $query = "SELECT `match_id`, `match_code`, `event_date`" .
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
                                " FROM " . IBC_MATCH_TABLE ." AS `ma` WHERE league_id='" . $intIbcLeagueId ."' AND sport_type='" . $info['sport_type'] ."' AND (match_id='" . $intIbcMatchId . "' OR event_date = DATE_FORMAT(now(),'%Y-%m-%d'))"; 

                        $result_match = db_query($query);
                        if ($result_match && db_num_rows($result_match)) {
                            while ($row_match = db_fetch_array($result_match)) {
                                $match_name = $row_match['home_team'] . ' - vs - ' . $row_match['away_team'] . ' [' . $row_match['match_code'] . ']  ( ' . Format::db_date($row_match['event_date']) . ' )';
                                ?>
                                <option value="<?=$row_match['match_id'] ?>" <?php if ($intIbcMatchId == $row_match['match_id']) echo 'selected="selected"'; ?>><?=$match_name?></option>
                                <?php
                            }
                        }
                    ?>  
                </select>    
                <label class="error_js" id="mess_match"></label>
                &nbsp;<label class="error">*&nbsp;<?= $errors['match'] ?></label>

                <!-- ui-dialog -->
                <div id="dialog_match" title="Add New Match">
                     <?php //require_once 'popup_insert_match.inc.php';?>
                </div>
            </td>
        </tr>
        <tr>
            <td align="left">Trader:</td>
            <td> 
                <select name="trader" id="trader" data-placeholder="Select Trader" class="trader-select" style="width:450px;" tabindex="2">
                     <option value="0"></option>
                    <?php
                    $query = "SELECT actor_id,actor_name FROM " . ACTOR_TABLE . " WHERE status=1 AND type=1 ORDER BY updated DESC, actor_name ASC";
                    $result_trader = db_query($query);
                    if ($result_trader && db_num_rows($result_trader)) {
                        while ($row_trader = db_fetch_array($result_trader)) {
                            ?>
                            <option value="<?= $row_trader['actor_id'] ?>" <?php if ($info['trader'] == $row_trader['actor_id']) echo 'selected="selected"'; ?>><?= $row_trader['actor_name'] ?></option>
                            <?php
                        }
                    }
                    ?>                
                </select>           

                <!-- ui-dialog -->
                <div id="dialog_trader" title="Add New Trader">
                     <table class="td_txt_left">                        
                        <tr>
                            <td>Name</td>
                            <td><input type="text" name="trader_name" size=25 value="" id="trader_name" maxlength="255"></td>
                        </tr>
                        <tr>
                            <td>Contact information</td>
                            <td>
                                <textarea name="contact_info" id="contact_info_trader" cols="28" rows="3"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td align="left">Suspender:</td>
            <td>
                <select name="suspender" id="suspender" data-placeholder="Select Suspender" class="suspender-select" style="width:450px;" tabindex="2">
                    <option value="0"></option>
                    <?php
                    $query = "SELECT actor_id,actor_name FROM " . ACTOR_TABLE . " WHERE status=1 AND type=2 ORDER BY updated DESC, actor_name ASC";
                    $result_suspender = db_query($query);
                    if ($result_suspender && db_num_rows($result_suspender)) {
                        while ($row_suspender = db_fetch_array($result_suspender)) {
                            ?>
                            <option value="<?= $row_suspender['actor_id'] ?>" <?php if ($info['suspender'] == $row_suspender['actor_id']) echo 'selected="selected"'; ?>><?= $row_suspender['actor_name'] ?></option>
                            <?php
                        }
                    }
                    ?>                
                </select> 

                <!-- ui-dialog -->
                <div id="dialog_suspender" title="Add New Suspender">
                     <table class="td_txt_left">                         
                        <tr>
                            <td>Name</td>
                            <td><input type="text" name="suspender_name" size=25 value="" id="suspender_name" maxlength="255"></td>
                        </tr>
                        <tr>
                            <td>Contact information</td>
                            <td>
                                <textarea name="contact_info" id="contact_info_suspender" cols="28" rows="3"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td align="left">CSD:</td>
            <td>
                <select name="csd" id="csd" data-placeholder="Select CSD" class="csd-select" style="width:450px;" tabindex="2">
                     <option value="0"></option>
                    <?php
                    $query = "SELECT actor_id,actor_name FROM " . ACTOR_TABLE . " WHERE status=1 AND type=3 ORDER BY updated DESC, actor_name ASC";
                    $result_csd = db_query($query);
                    if ($result_csd && db_num_rows($result_csd)) {
                        while ($row_csd = db_fetch_array($result_csd)) {
                            ?>
                            <option value="<?= $row_csd['actor_id'] ?>" <?php if ($info['csd'] == $row_csd['actor_id']) echo 'selected="selected"'; ?>><?= $row_csd['actor_name'] ?></option>
                            <?php
                        }
                    }
                    ?>                
                </select>             

                <!-- ui-dialog -->
                <div id="dialog_csd" title="Add New CSD">
                     <table class="td_txt_left">                        
                        <tr>
                            <td>Name</td>
                            <td><input type="text" name="csd_name" size=25 value="" id="csd_name" maxlength="255"></td>
                        </tr>
                        <tr>
                            <td>Contact information</td>
                            <td>
                                <textarea name="contact_info" id="contact_info_csd" cols="28" rows="3"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <!-- the end -->
        <tr>
            <td align="left">Provider:</td>
            <td>
                <select name="provider" id="provider" data-placeholder="Select Provider" class="provider-select" style="width:450px;" tabindex="2">
                     <option value="0"></option>
                    <?php
                    $query = "SELECT actor_id,actor_name FROM " . ACTOR_TABLE . " WHERE status=1 AND type=4 ORDER BY updated DESC, actor_name ASC";
                    $result_prov= db_query($query);
                    if ($result_prov && db_num_rows($result_prov)) {
                        while ($row_prov = db_fetch_array($result_prov)) {
                            ?>
                            <option value="<?= $row_prov['actor_id'] ?>" <?php if ($info['provider'] == $row_prov['actor_id']) echo 'selected="selected"'; ?>><?= $row_prov['actor_name'] ?></option>
                            <?php
                        }
                    }
                    ?>                
                </select>             

                <!-- ui-dialog -->
                <div id="dialog_provider" title="Add New Provider">
                     <table class="td_txt_left">                        
                        <tr>
                            <td>Name</td>
                            <td><input type="text" name="provider_name" size=25 value="" id="provider_name" maxlength="255"></td>
                        </tr>
                        <tr>
                            <td>Contact information</td>
                            <td>
                                <textarea name="contact_info" id="contact_info_provider" cols="28" rows="3"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td align="left">Telephone:</td>
            <td><input type="text" name="phone" size="25" maxlength="16" value="<?=$info['phone']?>" style="width: 200px">
                 &nbsp;Ext&nbsp;<input type="text" name="phone_ext" size="6" maxlength="8" value="<?=$info['phone_ext']?>" style="width: 100px">
                &nbsp;<font class="error">&nbsp;<?=$errors['phone']?></font></td>
        </tr>
        <tr height=1px><td align="left" colspan=2 >&nbsp;</td></tr>
        <tr>
            <td align="left" valign="top">Due Date:</td>
            <td>
                <i>Time is based on your time zone (GM <?=$thisuser->getTZoffset()?>)</i>&nbsp;<font class="error">&nbsp;<?=$errors['time']?></font><br>
                <input id="duedate" name="duedate" value="<?=Format::htmlchars($info['duedate'])?>"
                    onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF style="width: 100px">
                <a href="#" onclick="event.cancelBubble=true;calendar(getObj('duedate')); return false;"><img src='images/cal.png'border=0 alt=""></a>
                &nbsp;&nbsp;
                <?php
                 $min=$hr=null;
                 if($info['time'])
                    list($hr,$min)=explode(':',$info['time']);
                    echo Misc::timeDropdown($hr,$min,'time');
                ?>
                &nbsp;<label class="error">&nbsp;<?=$errors['duedate']?></label>
            </td>
        </tr>
        <?
          $sql='SELECT priority_id,priority_desc FROM '.TICKET_PRIORITY_TABLE.' ORDER BY priority_urgency DESC';
          if(($priorities=db_query($sql)) && db_num_rows($priorities)){ ?>
          <tr>
            <td align="left">Priority:</td>
            <td>
                <select name="pri">
                  <?
                    while($row=db_fetch_array($priorities)){ ?>
                        <option value="<?=$row['priority_id']?>" <?=$info['pri']==$row['priority_id']?'selected':''?> ><?=$row['priority_desc']?></option>
                  <?}?>
                </select>
            </td>
           </tr>
        <? }?>

        <?php
        $services= db_query('SELECT topic_id,topic,isactive FROM '.TOPIC_TABLE.' ORDER BY topic');
        if($services && db_num_rows($services)){ ?>
        <tr>
            <td align="left" valign="top">Help Topic:</td>
            <td>
                <select name="topicId">    
                    <option value="0" selected >None</option>
                    <?if(!$info['topicId'] && $info['topic']){ //old helptopic?>
                    <option value="0" selected ><?=$info['topic']?> (deleted)</option>
                    <?
                    }
                     while (list($topicId,$topic,$active) = db_fetch_row($services)){
                        $selected = ($info['topicId']==$topicId)?'selected':'';
                        $status=$active?'Active':'Inactive';
                        ?>
                        <option value="<?=$topicId?>"<?=$selected?>><?=$topic?>&nbsp;&nbsp;&nbsp;(<?=$status?>)</option>
                    <?
                     }?>
                </select>
                &nbsp;(optional)<label class="error">&nbsp;<?=$errors['topicId']?></label>
            </td>
        </tr>
        <?
        }?>
        <tr>
            <td align="left" valign="top">Attachment(s):</td>
            <td>
                <ul class="list_attachment">
               <?php
               $attachments = db_query("SELECT attachment_id,attachment_name FROM ".ATTACHMENT_TABLE." WHERE ticket_id='" . $ticket->getId() ."' ORDER BY attachment_name");
               $strAttachmentName = '';
               if($attachments && db_num_rows($attachments)) { 
                   while (list($attachment_id, $attachment_name) = db_fetch_row($attachments)) {
                       $strAttachmentName = $strAttachmentName.$attachment_name .',';
               ?>
                    <li class="file_<?php echo $attachment_id;?>"><?php echo $attachment_name;?> <a href="download_file.php?filename=<?php echo $attachment_name;?>" class="qq-file-cancel" >Download</a>|<a href="javascript:void(0)" class="qq-file-cancel" onclick="delete_file(<?php echo $attachment_id;?>,'<?php echo $attachment_name;?>')" style="margin-left: 5px">Remove</a></li><br>
               <?php
                   }
               }
               ?>
                </ul>
                <ul id="manualUploadModeExample" class="unstyled"></ul>
                <input type="hidden" value="<?php echo $strAttachmentName;?>" id="attachment_name" name="attachment_name"/>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top"><b>Internal Note:</b></td>
            <td>
                <i>Reasons for the edit.</i><label class="error"><b>*&nbsp;<?=$errors['note']?></b></label><br/>
                <textarea name="note" id="note" class="required" cols="45" rows="5" wrap="soft" style="width: 450px"><?=$info['note']?></textarea>
            </td>
        </tr>
        <tr height=2px><td align="left" colspan=2 >&nbsp;</td></tr>
        <tr>
            <td></td>
            <td>
                <input class="button" type="button" name="submit_x" value="Update Ticket" id="insert_ticket">
                <input class="button" type="reset" value="Reset">
                <input class="button" type="button" name="cancel" value="Cancel" onClick='window.location.href="tickets.php?id=<?=$ticket->getId()?>"'>    
            </td>
        </tr>
    </table>
</form>
<!--Validate ticket-->
<script type="text/javascript" src="js/validateTicket.js" charset="utf-8"></script>
<!--Add javascript for select option-->
<script type="text/javascript" src="js/select_option_for_ticket.js" charset="utf-8"></script>
<!--Javascript for upload file-->
<script type="text/javascript" src="js/upload_file_ticket_customize.js" charset="utf-8"></script>