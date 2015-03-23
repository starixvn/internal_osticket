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
<table width="80%" border="0" cellspacing=1 cellpadding=2 class="front_layer">
    <form action="tickets.php" method="post" enctype="multipart/form-data" id="frm_ticket">
        <input type='hidden' name='a' value='open'>
        <tr><td align="left" colspan=2>Please fill in the form below to open a new ticket.</td></tr>       
        <tr>
            <td align="left" ><b>Full Name:</b></td>
            <td>
                <input type="text" id="name" name="name" size="25" value="<?= $info['name'] ?>">
                &nbsp;<font class="error"><b>*</b>&nbsp;<?= $errors['name'] ?></font>
            </td>
        </tr>
        <tr>
            <td align="left" nowrap width="20%">Email Address:</td>
            <td>
                <input type="text" id="email" name="email" size="25" value="<?= $info['email'] ?>">
                &nbsp;<font class="error"><?= $errors['email'] ?></font>
<? if ($cfg->notifyONNewStaffTicket()) { ?>
                    &nbsp;&nbsp;&nbsp;
                    <input type="checkbox" name="alertuser" <?= (!$errors || $info['alertuser']) ? 'checked' : '' ?>>Send alert to user.
<? } ?>
            </td>
        </tr>
        <tr>
            <td align="left">Telephone:</td>
            <td><input type="text" name="phone" size="25" value="<?= $info['phone'] ?>" style="width: 200px">
                &nbsp;Ext&nbsp;&nbsp;<input type="text" name="phone_ext" size="6" value="<?= $info['phone_ext'] ?>" style="width: 100px">
                <font class="error">&nbsp;<?= $errors['phone'] ?></font></td>
        </tr>
        <!--<tr height=2px><td align="left" colspan=2 >&nbsp;</td</tr>-->
        <tr>
            <td align="left"><b>Ticket Source:</b></td>
            <td>
                <select name="source">
                    <option value="" selected >Select Source</option>
                    <option value="Phone" <?= ($info['source'] == 'Phone') ? 'selected' : '' ?>>Phone</option>
                    <option value="Email" <?= ($info['source'] == 'Email') ? 'selected' : '' ?>>Email</option>
                    <option value="Other" <?= ($info['source'] == 'Other') ? 'selected' : '' ?>>Other</option>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?= $errors['source'] ?></font>
            </td>
        </tr>
        <tr>
            <td align="left"><b>Department:</b></td>
            <td>
                <select name="deptId">
                    <option value="" selected >Select Department</option>
                    <?
                    $services = db_query('SELECT dept_id,dept_name FROM ' . DEPT_TABLE . ' ORDER BY dept_name');
                    while (list($deptId, $dept) = db_fetch_row($services)) {
                        $selected = ($info['deptId'] == $deptId) ? 'selected' : '';
                        ?>
                        <option value="<?= $deptId ?>"<?= $selected ?>><?= $dept ?></option>
    <? }
?>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?= $errors['deptId'] ?></font>
            </td>
        </tr>
        <tr>
            <td align="left"><b>Subject:</b></td>
            <td>
                <input type="text" name="subject" size="35" value="<?= $info['subject'] ?>">
                &nbsp;<font class="error">*&nbsp;<?= $errors['subject'] ?></font>
            </td>
        </tr>
        <!--Custumized osticket-->
        <tr>
            <td align="left"><b>League:</b></td>
            <td>
                <select name="league" id="league" data-placeholder="Select League" class="league-select" style="width:450px;" tabindex="2">
                    <option value=""></option>
                    <?php
                        $query = "SELECT league_id,league_name FROM " . LEAGUE_TABLE . " WHERE status=1 ORDER BY updated DESC, league_name ASC";
                        $result_league = db_query($query);
                        if ($result_league && db_num_rows($result_league)) {
                            while ($row_league = db_fetch_array($result_league)) {
                                ?>
                                <option value="<?= $row_league['league_id'] ?>" <?php if ($info['league'] == $row_league['league_id']) echo 'selected="selected"'; ?>><?= $row_league['league_name'] ?></option>
                                <?php
                            }
                        }
                    ?>                
                </select>             
                &nbsp;<font class="error">*&nbsp;&nbsp;<?= $errors['league'] ?></font> 
                <!-- ui-dialog -->
                <div id="dialog_league" title="Add New League">
                    <table>
                        <tr>
                            <td>League Name</td>
                            <td><input type="text" name="league_name" size=25 value="" id="league_name" maxlength="255" class="txt_input_popup"></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td align="left"><b>Match:</b></td>
            <td>     
                <select name="match" id="match" data-placeholder="Select Match" class="match-select" style="width:450px;" tabindex="2">
                    
                    <?php
                        $query = "SELECT match_id,match_name FROM " . MATCH_TABLE . " WHERE status=1 and league_id='". $info['league'] ."' ORDER BY updated DESC, match_name ASC";
                        $result_match = db_query($query);
                        if ($result_match && db_num_rows($result_match)) {
                            while ($row_match = db_fetch_array($result_match)) {
                                ?>
                                <option value="<?=$row_match['match_id'] ?>" <?php if ($info['match'] == $row_match['match_id']) echo 'selected="selected"'; ?>><?=$row_match['match_name'] ?></option>
                                <?php
                            }
                        }
                    ?>  
                </select>            
                &nbsp;<font class="error">*&nbsp;<?= $errors['match'] ?></font>
               <!-- ui-dialog -->
                <div id="dialog_match" title="Add New Match">
                     <table>
                         <tr>
                            <td></td>
                            <td>
                                <span id="league_name"></span>
                                <input type="hidden" name="league_id" id="league_id" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td>Match Name</td>
                            <td><input type="text" name="match_name" size=25 value="" id="match_name" maxlength="255" class="txt_input_popup"></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td align="left">Trader:</td>
            <td> 
                <select name="trader" id="trader" data-placeholder="Select Trader" class="trader-select" style="width:450px;" tabindex="2">
                     <option value=""></option>
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
                     <table>                        
                        <tr>
                            <td>Name</td>
                            <td><input type="text" name="trader_name" size=25 value="" id="trader_name" maxlength="255" class="txt_input_popup"></td>
                        </tr>
                        <tr>
                            <td>Contact information</td>
                            <td>
                                <textarea name="contact_info" id="contact_info_trader" class="textarea_input_popup"></textarea>
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
                    <option value=""></option>
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
                     <table>                        
                        <tr>
                            <td>Name</td>
                            <td><input type="text" name="suspender_name" size=25 value="" id="suspender_name" maxlength="255" class="txt_input_popup"></td>
                        </tr>
                        <tr>
                            <td>Contact information</td>
                            <td>
                                <textarea name="contact_info" id="contact_info_suspender" class="textarea_input_popup"></textarea>
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
                     <option value=""></option>
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
                     <table>                        
                        <tr>
                            <td>Name</td>
                            <td><input type="text" name="csd_name" size=25 value="" id="csd_name" maxlength="255" class="txt_input_popup"></td>
                        </tr>
                        <tr>
                            <td>Contact information</td>
                            <td>
                                <textarea name="contact_info" id="contact_info_csd" class="textarea_input_popup"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <!-- the end -->
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
                            onChange="getCannedResponse(this.options[this.selectedIndex].value,this.form,'issue');this.selectedIndex='0';" >
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
                    
                        <textarea style="margin-top: 5px;" name="issue" cols="55" rows="8" wrap="soft"><?= $info['issue'] ?></textarea></td>
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
                       onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF>
                <a href="#" onclick="event.cancelBubble=true;calendar(getObj('duedate')); return false;"><img src='images/cal.png'border=0 alt=""></a>
                &nbsp;&nbsp;
                <?php
                $min = $hr = null;
                if ($info['time'])
                    list($hr, $min) = explode(':', $info['time']);
                echo Misc::timeDropdown($hr, $min, 'time');
                ?>
                &nbsp;<font class="error">&nbsp;<?= $errors['duedate'] ?></font>
            </td>
        </tr>
        <?
        $sql = 'SELECT priority_id,priority_desc FROM ' . TICKET_PRIORITY_TABLE . ' ORDER BY priority_urgency DESC';
        if (($priorities = db_query($sql)) && db_num_rows($priorities)) {
            ?>
            <tr>
                <td align="left">Priority:</td>
                <td>
                    <select name="pri">
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
                    <select name="topicId">
                        <option value="" selected >Select One</option>
                        <?
                        while (list($topicId, $topic) = db_fetch_row($services)) {
                            $selected = ($info['topicId'] == $topicId) ? 'selected' : '';
                            ?>
                            <option value="<?= $topicId ?>"<?= $selected ?>><?= $topic ?></option>
                <? }
            ?>
                    </select>
                    &nbsp;<font class="error">&nbsp;<?= $errors['topicId'] ?></font>
                </td>
            </tr>
                        <? }
                    ?>
        <tr>
            <td>Assign To:</td>
            <td>
                <select id="staffId" name="staffId" style="float: left">
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
                </select><font class='error'>&nbsp;<?= $errors['staffId'] ?></font>
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
            <td>Attachment:</td>
            <td>                 
                <ul id="manualUploadModeExample" class="unstyled"></ul>               
            </td>
        </tr>
        <tr height=2px><td align="left" colspan=2 >&nbsp;</td</tr>
        <tr>
            <td></td>
            <td>
                <input class="button" type="button" name="submit_x" value="Submit Ticket" id="insert_ticket">
                <input class="button" type="reset" value="Reset">
                <input class="button" type="button" name="cancel" value="Cancel" onClick='window.location.href="tickets.php"'>    
            </td>
        </tr>
    </form>
</table>

<!--Add javascript for select option-->
<script type="text/javascript" src="js/select_option_for_ticket.js" charset="utf-8"></script>
<script>
    $(document).ready(function() {
         var errorHandler = function(event, id, fileName, reason) {
             qq.log("id: " + id + ", fileName: " + fileName + ", reason: " + reason);
         };


         var uploader2 = new qq.FineUploader({
             element: $('#manualUploadModeExample')[0],
             autoUpload: false,
             uploadButtonText: "Select Files",
             text: {
                     uploadButton: '<i class="icon-plus icon-white"></i> Select Files'
               },
             request: {
                 endpoint: "upload_file_attachment.php",
                 inputName: 'qqfile'                 
             },
             validation: {
                 //allowedExtensions: ['jpeg', 'jpg', 'txt'],
                 sizeLimit: 2097152 // 50 kB = 50 * 1024 bytes
             },
             callbacks: {
                 onError: errorHandler

             },
              showMessage: function(message) {
                 // Using Twitter Bootstrap's classes and jQuery selector and method
                 $('#manualUploadModeExample').append('<div class="alert alert-error">' + message + '</div>');
               },
             debug: true
         });

         $('#insert_ticket').click(function() {            
             uploader2.uploadStoredFiles();
              $('#frm_ticket').submit();
         });

     });

 </script>

