
<!-- attachment -->
<link href="css/fineuploader.css" rel="stylesheet">
<link href="css/chosen.css" rel="stylesheet">
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
<script type="text/javascript" src="js/chosen.jquery.js" charset="utf-8"></script>
<script type="text/javascript">
	$(function(){
		$(".chosen-select").chosen();
	});
</script>

<?php
if (!defined('OSTSCPINC') || !is_object($thisuser) || !$thisuser->isStaff())
    die('Access Denied');
$info = ($_POST && $errors) ? Format::input($_POST) : array(); //on error...use the post data
?>
<div width="100%">
    <? if ($errors['err']) { ?>
        <p align="center" id="errormessage"><?= $errors['err'] ?></p>
    <? } elseif ($msg) { ?>
        <p align="center" class="infomessage"><?= $msg ?></p>
    <? } elseif ($warn) { ?>
        <p class="warnmessage"><?= $warn ?></p>
<? } ?>
</div>
<div class="layer_poup_display"></div>
<form action="tickets.php" method="post" enctype="multipart/form-data" id="frm_ticket">    
<input type='hidden' name='a' value='open'>
<input type="hidden" value="" id="attachment_name" name="attachment_name"/>  
<table width="80%" border="0" cellspacing=1 cellpadding=2 class="front_layer">
    <tr><td align="left" colspan=2>Please fill in the form below to open a new ticket.</td></tr>        
    <tr>
        <td align="left" ><b>Full Name:</b></td>
        <td>
            <input type="text" class="required" id="name" name="name" size="25" maxlength="32" value="<?= $info['name'] ?>">
            &nbsp;<label  class="error"><b>*</b>&nbsp;<?= $errors['name'] ?></label>
        </td>
    </tr>
    <tr>
        <td align="left" nowrap width="20%">Email Address:</td>
        <td>
            <input type="text" id="email" name="email" size="25" maxlength="120" value="<?= $info['email'] ?>">
            &nbsp;<label class="error"><?= $errors['email'] ?></label>
			<? if ($cfg->notifyONNewStaffTicket()) { ?>
                &nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="alertuser" <?= (!$errors || $info['alertuser']) ? 'checked' : '' ?>>Send alert to user.
			<? } ?>
        </td>
    </tr>
    <tr>
        <td align="left">Telephone:</td>
        <td><input type="text" name="phone" size="25" maxlength="16" value="<?= $info['phone'] ?>" class="phone1">
            &nbsp;Ext&nbsp;&nbsp;<input type="text" name="phone_ext" size="6" maxlength="8" value="<?= $info['phone_ext'] ?>" class="phone2">
            <label class="error">&nbsp;<?= $errors['phone'] ?></label></td>
    </tr>
    <!--<tr height=2px><td align="left" colspan=2 >&nbsp;</td</tr>-->
    <tr>
        <td align="left"><b>Ticket Source:</b></td>
        <td>
            <select name="source" class="select_choose required" id="source">
                <option value="" selected >Select Source</option>
                <option value="Phone" <?= ($info['source'] == 'Phone') ? 'selected' : '' ?>>Phone</option>
                <option value="Email" <?= ($info['source'] == 'Email') ? 'selected' : '' ?>>Email</option>
                <option value="Other" <?= ($info['source'] == 'Other') ? 'selected' : '' ?>>Other</option>
            </select>
            &nbsp;<label class="error"><b>*</b>&nbsp;<?= $errors['source'] ?></label>
        </td>
    </tr>
    <tr>
        <td align="left"><b>Department:</b></td>
        <td class="ticket_department">
            <select name="deptIds[]" class="chosen-select required" id="deptId" multiple="multiple">
                <?
                $services = db_query('SELECT dept_id,dept_name FROM ' . DEPT_TABLE . ' ORDER BY dept_name');
                while (list($deptId, $dept) = db_fetch_row($services)) {
                    ?>
                    <option value="<?= $deptId ?>"<?= $selected ?>><?= $dept ?></option>
					<? }
				?>
            </select>
            &nbsp;<label class="error"><b>*</b>&nbsp;<?= $errors['deptId'] ?></label>
        </td>
    </tr>
    <tr>
        <td align="left"><b>Subject:</b></td>
        <td>
            <input type="text" class="required" id="subject" name="subject" size="35" maxlength="64" value="<?= $info['subject'] ?>">
            &nbsp;<label class="error">*&nbsp;<?= $errors['subject'] ?></label>
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
            <select name="sport_type" id="sport" data-placeholder="Select Sport" class="sport-select required" style="width:450px;" tabindex="2">
                <option value="0"></option>
                <?php
                $query="SELECT sport_type,sport_name FROM " . SPORT_TABLE ." WHERE status=1 ORDER BY updated DESC, sport_name ASC";                
                $result_sport = db_query($query);
                 if($result_sport && db_num_rows($result_sport)) {         
                    while ($row_sport = db_fetch_array($result_sport)) {
                        //default choose "soccer" 
                        $select = null;
                        if($row_sport['sport_type'] == 1 && $info['sport_type'] == null) {
                            $select = 'selected="selected"';                            
                        } else {
                            if( $info['sport_type'] == $row_sport['sport_type']) {
                                $select = 'selected="selected"';
                            }
                        }
                        
                ?>
                <option value="<?=$row_sport['sport_type']?>" <?php echo $select; ?> ><?=$row_sport['sport_name']?></option>
                <?php
                    }
                 }
                ?>                     
            </select>
            <label class="error_js" id="mess_sport"></label>
            &nbsp;<label class="error">*&nbsp;<?= $errors['sport_type'] ?></label> 
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
            <input type="hidden" value="" id="league" name="league"/>
            <input type="hidden" style="width:450px" name="league_new" id="league_new" class="bigdrop select2-offscreen" tabindex="-1">
            &nbsp;<label class="error pull-left">*&nbsp;<?= $errors['league'] ?></label>
            <span id="loading_league" class="loading"></span>            
        </td>
    </tr>
    <tr>
        <td align="left">Event Date (GM -4.0):</td>
        <td>
             From&nbsp;&nbsp;
             <input id="fromDate" name="fromDate" value=""
                    onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF style="width: 100px">
             <a href="#" onclick="event.cancelBubble=true;calendar(getObj('fromDate')); return false;"><img src='images/cal.png'border=0 alt=""></a>
             &nbsp;
             <?php
             $min = $hr = null;
             $time = "";
             if ($time)
                 list($hr, $min) = explode(':', $time);
             echo Misc::timeDropdown($hr, $min, 'fromTime');
             ?>
             &nbsp;&nbsp; To &nbsp;&nbsp;
             <input id="toDate" name="toDate" value=""
                    onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF style="width: 100px">
             <a href="#" onclick="event.cancelBubble=true;calendar(getObj('toDate')); return false;"><img src='images/cal.png'border=0 alt=""></a>
             &nbsp;
             <?php
             $min = $hr = null;
             $time = "23:59";
             if ($time)
                 list($hr, $min) = explode(':', $time);
             echo Misc::timeDropdown($hr, $min, 'toTime');
             ?>
             <input type="button" id="filter" value="Filter Match List" name="submit_x" class="filter_btn">
    </tr>
    <tr>
        <td align="left"><b>Match:</b></td>
        <td>     
            <select name="match" id="match" data-placeholder="Select Match" class="match-select" style="width:450px;" tabindex="2" disabled="disabled">
                <option value="0"></option>                 
            </select>    
            <label class="error_js" id="mess_match"></label>
            &nbsp;<label class="error pull-left">*&nbsp;<?= $errors['match'] ?></label>
            <span id="loading_match" class="loading"></span>
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
                $result_prov = db_query($query);
                if ($result_prov && db_num_rows($result_prov)) {
                    while ($row_prov = db_fetch_array($result_prov)) {
                        ?>
                        <option value="<?= $row_prov['actor_id'] ?>" <?php if ($info['csd'] == $row_prov['actor_id']) echo 'selected="selected"'; ?>><?= $row_prov['actor_name'] ?></option>
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
        <td align="left" valign="top"><b>Issue Summary:</b></td>
        <td>
            <i>Visible to client/customer.</i><font class="error"><b>*&nbsp;<?= $errors['issue'] ?></b></font><br/>
            <?
            $sql = 'SELECT premade_id,title FROM ' . KB_PREMADE_TABLE . ' WHERE isenabled=1';
            $canned = db_query($sql);
            if ($canned && db_num_rows($canned)) {
                ?>
                <div style="float: left;">
                Premade:&nbsp;
                <select id="canned" name="canned"
                        onChange="getCannedResponse(this.options[this.selectedIndex].value,this.form,'issue');this.selectedIndex='0';" class="select_choose">
                    <option value="0" selected="selected">Select a premade reply/issue</option>
                    <? while (list($cannedId, $title) = db_fetch_row($canned)) { ?>
                        <option value="<?= $cannedId ?>" ><?= Format::htmlchars($title) ?></option>
                <? } ?>
                </select>
                </div>
                <div style="padding-top: 5px; width: 200px;float: left; margin-left: 10px">
                    <label><input type='checkbox' value='1' name=append checked="true" />Append</label>
                </div>
    <? } ?>
                <div style="clear: both"></div>

                <textarea style="margin-top: 5px;" id="issue" name="issue" class="required" cols="55" rows="8" wrap="soft"><?= $info['issue'] ?></textarea></td>
                </tr>
   </tr>
<? if ($cfg->canUploadFiles()) {
?>
        <tr>
            <td>Attachment:</td>
            <td>
                <input type="file" name="attachment"><font class="error">&nbsp;<?= $errors['attachment'] ?></font>
            </td>
        </tr>
<? } ?>
    <tr>
        <td align="left" valign="top">Internal Note:</td>
        <td>
            <i>Optional Internal note(s).</i><font class="error"><b>&nbsp;<?= $errors['note'] ?></b></font><br/>
            <textarea name="note" cols="55" rows="5" wrap="soft"><?= $info['note'] ?></textarea></td>
    </tr>

    <tr>
        <td align="left" valign="top">Due Date:</td>
        <td>
            <i>Time is based on your time zone (GM <?= $thisuser->getTZoffset() ?>)</i>&nbsp;<font class="error">&nbsp;<?= $errors['time'] ?></font><br>
            <input id="duedate" name="duedate" value="<?= Format::htmlchars($info['duedate']) ?>"
                   onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF style="width: 100px">
            <a href="#" onclick="event.cancelBubble=true;calendar(getObj('duedate')); return false;"><img src='images/cal.png'border=0 alt=""></a>
            &nbsp;&nbsp;
            <?php
            $min = $hr = null;
            if ($info['time'])
                list($hr, $min) = explode(':', $info['time']);
            echo Misc::timeDropdown($hr, $min, 'time');
            ?>
            &nbsp;<label class="error">&nbsp;<?= $errors['duedate'] ?></label>
        </td>
    </tr>
    <?
    $sql = 'SELECT priority_id,priority_desc FROM ' . TICKET_PRIORITY_TABLE . ' ORDER BY priority_urgency DESC';
    if (($priorities = db_query($sql)) && db_num_rows($priorities)) {
        ?>
        <tr>
            <td align="left">Priority:</td>
            <td>
                <select name="pri" class="select_choose">
                    <?
                    $info['pri'] = $info['pri'] ? $info['pri'] : $cfg->getDefaultPriorityId();
                    while ($row = db_fetch_array($priorities)) {
                        ?>
                        <option value="<?= $row['priority_id'] ?>" <?= $info['pri'] == $row['priority_id'] ? 'selected' : '' ?> ><?= $row['priority_desc'] ?></option>
        <? } ?>
                </select>
            </td>
        </tr>
<? } ?>
<?php
$services = db_query('SELECT topic_id,topic FROM ' . TOPIC_TABLE . ' WHERE isactive=1 ORDER BY topic');
if ($services && db_num_rows($services)) {
?>
        <tr>
            <td align="left" valign="top">Help Topic:</td>
            <td>
                <select name="topicId" class="select_choose">
                    <option value="" selected >Select One</option>
                    <?
                    while (list($topicId, $topic) = db_fetch_row($services)) {
                        $selected = ($info['topicId'] == $topicId) ? 'selected' : '';
                        ?>
                        <option value="<?= $topicId ?>"<?= $selected ?>><?= $topic ?></option>
            <? }
        ?>
                </select>
                &nbsp;<label class="error">&nbsp;<?= $errors['topicId'] ?></label>
            </td>
        </tr>
                    <? }
                ?>
    <tr>
        <td>Assign To:</td>
        <td>
            <select id="staffId" name="staffId" style="float: left" class="select_choose">
                <option value="0" selected="selected">-Assign To Staff-</option>
                <?
                //TODO: make sure the user's group is also active....DO a join.
                $sql = ' SELECT staff_id,CONCAT_WS(", ",lastname,firstname) as name FROM ' . STAFF_TABLE . ' WHERE isactive=1 AND onvacation=0 ';
                $depts = db_query($sql . ' ORDER BY lastname,firstname ');
                while (list($staffId, $staffName) = db_fetch_row($depts)) {
                    $selected = ($info['staffId'] == $staffId) ? 'selected' : '';
                    ?>
                    <option value="<?= $staffId ?>"<?= $selected ?>><?= $staffName ?></option>
<? }
?>
            </select><label class='error'>&nbsp;<?= $errors['staffId'] ?></label>
            &nbsp;&nbsp;&nbsp;
            <div style="padding-top: 5px; width: 200px;float: left; margin-left: 10px">
                <input type="checkbox" name="alertstaff" <?= (!$errors || $info['alertstaff']) ? 'checked' : '' ?>>Send alert to assigned staff.
            </div>
       </td>
    </tr>
    <tr>
        <td>Signature:</td>
        <td> <?php
            $appendStaffSig = $thisuser->appendMySignature();
            $info['signature'] = !$info['signature'] ? 'none' : $info['signature']; //change 'none' to 'mine' to default to staff signature.
?>
            <div style="margin-top: 2px;">
                <label><input type="radio" name="signature" value="none" checked > None</label>
<? if ($appendStaffSig) { ?>
                    <label> <input type="radio" name="signature" value="mine" <?= $info['signature'] == 'mine' ? 'checked' : '' ?> > My signature</label>
<? } ?>
                <label><input type="radio" name="signature" value="dept" <?= $info['signature'] == 'dept' ? 'checked' : '' ?> > Dept Signature (if any)</label>
            </div>
        </td>
    </tr>
    <tr>
        <td>Attachment(s):</td>
        <td>                 
            <ul id="manualUploadModeExample" class="unstyled"></ul>                
        </td>
    </tr>
    <tr height=2px><td align="left" colspan=2 >&nbsp;</td></tr>
    <tr>
        <td></td>
        <td>
            <input class="button" type="button" name="submit_x" value="Submit Ticket" id="insert_ticket">
            <input class="button" type="reset" value="Reset">
            <input class="button" type="button" name="cancel" value="Cancel" onClick='window.location.href="tickets.php"'>    
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
