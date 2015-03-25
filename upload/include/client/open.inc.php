<?php
if(!defined('OSTCLIENTINC')) die('Kwaheri rafiki!'); //Say bye to our friend..

$info=($_POST && $errors)?Format::input($_POST):array(); //on error...use the post data
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
<div>Please fill in the form below to open a new ticket.</div><br>
<form action="open.php" method="POST" enctype="multipart/form-data">
<table align="left" cellpadding=2 cellspacing=1 width="90%">
    <tr>
        <th width="20%">Full Name:</th>
        <td>
            <?if ($thisclient && ($name=$thisclient->getName())) {
                ?>
                <input type="hidden" name="name" value="<?=$name?>"><?=$name?>
            <?}else {?>
                <input type="text" name="name" size="25" value="<?=$info['name']?>">
	        <?}?>
            &nbsp;<font class="error">*&nbsp;<?=$errors['name']?></font>
        </td>
    </tr>
    <tr>
        <th nowrap >Email Address:</th>
        <td>
            <?if ($thisclient && ($email=$thisclient->getEmail())) {
                ?>
                <input type="hidden" name="email" size="25" value="<?=$email?>"><?=$email?>
            <?}else {?>             
                <input type="text" name="email" size="25" value="<?=$info['email']?>">
            <?}?>
            &nbsp;<font class="error">*&nbsp;<?=$errors['email']?></font>
        </td>
    </tr>
    <tr>
        <td>Telephone:</td>
        <td><input type="text" name="phone" size="25" value="<?=$info['phone']?>">
             &nbsp;Ext&nbsp;<input type="text" name="phone_ext" size="6" value="<?=$info['phone_ext']?>">
            &nbsp;<font class="error">&nbsp;<?=$errors['phone']?></font></td>
    </tr>
    <tr height=2px><td align="left" colspan=2 >&nbsp;</td></tr>
    <tr>
        <th>Help Topic:</th>
        <td>
            <select name="topicId">
                <option value="" selected >Select One</option>
                <?
                 $services= db_query('SELECT topic_id,topic FROM '.TOPIC_TABLE.' WHERE isactive=1 ORDER BY topic');
                 if($services && db_num_rows($services)) {
                     while (list($topicId,$topic) = db_fetch_row($services)){
                        $selected = ($info['topicId']==$topicId)?'selected':''; ?>
                        <option value="<?=$topicId?>"<?=$selected?>><?=$topic?></option>
                        <?
                     }
                 }else{?>
                    <option value="0" >General Inquiry</option>
                <?}?>
            </select>
            &nbsp;<font class="error">*&nbsp;<?=$errors['topicId']?></font>
        </td>
    </tr>
    <tr>
        <th>Subject:</th>
        <td>
            <input type="text" name="subject" size="35" value="<?=$info['subject']?>">
            &nbsp;<font class="error">*&nbsp;<?=$errors['subject']?></font>
        </td>
    </tr>
    <!--Custumized osticket-->
    <tr>
        <td align="left"><b>League:</b></td>
        <td>
             <select name="league">                
                <?php
                $query="SELECT league_id,league_name FROM " . LEAGUE_TABLE ." WHERE status=1 ORDER BY updated DESC, league_name ASC";                
                $result_league = db_query($query);
                 if($result_league && db_num_rows($result_league)) {            
                    while ($row_league = db_fetch_array($result_league)) {     
                ?>
                <option value="<?=$row_league['league_id']?>" <?php if($info['league'] == $row_league['league_id']) echo 'selected="selected"';?>><?=$row_league['league_name']?></option>
                <?php
                    }
                 }
                ?>                
            </select>           
            &nbsp;<font class="error">*&nbsp;<?=$errors['league']?></font>            
        </td>
    </tr>
    <tr>
        <td align="left"><b>Match:</b></td>
        <td>
            <select name="match">                
                <?php
                //$query="SELECT match_id,match_name FROM " . MATCH_TABLE ." WHERE status=1 ORDER BY updated DESC, match_name ASC"; 
				$query = "SELECT `match_id`, `match_code`, `event_date`, `kickofftime`" .
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
                                " FROM " . MATCH_TABLE ." AS `ma` WHERE status=1 ORDER BY updated DESC";
                $result_match = db_query($query);
                 if($result_match && db_num_rows($result_match)) {            
                    while ($row_match = db_fetch_array($result_match)) {    
					$match_name = $row_match['home_team'] . ' - vs - ' . $row_match['away_team'] . ' [' . $row_match['match_code'] . ']  ( ' . Format::db_date($row_match['event_date']) . ' )';
                ?>
                <option value="<?=$row_match['match_id']?>" <?php if($info['match'] == $row_match['match_id']) echo 'selected="selected"';?>><?=$match_name?></option>
                <?php
                    }
                 }
                ?>                
            </select>            
            &nbsp;<font class="error">*&nbsp;<?=$errors['match']?></font>
        </td>
    </tr>
    <tr>
        <td align="left"><b>Trader:</b></td>
        <td>
            <select name="trader">   
                <option value="">Select Trader</option>
                <?php
                $query="SELECT actor_id,actor_name FROM " . ACTOR_TABLE ." WHERE status=1 AND type=1 ORDER BY updated DESC, actor_name ASC";                
                $result_trader = db_query($query);
                 if($result_trader && db_num_rows($result_trader)) {            
                    while ($row_trader = db_fetch_array($result_trader)) {     
                ?>
                <option value="<?=$row_trader['actor_id']?>" <?php if($info['trader'] == $row_trader['actor_id']) echo 'selected="selected"';?>><?=$row_trader['actor_name']?></option>
                <?php
                    }
                 }
                ?>                
            </select>             
            &nbsp;<font class="error">*&nbsp;<?=$errors['trader']?></font>
        </td>
    </tr>
    <tr>
        <td align="left"><b>Suspender:</b></td>
        <td>
            <select name="suspender">
                <option value="">Select Suspender</option>
                <?php
                $query="SELECT actor_id,actor_name FROM " . ACTOR_TABLE ." WHERE status=1 AND type=2 ORDER BY updated DESC, actor_name ASC";                
                $result_suspender = db_query($query);
                 if($result_suspender && db_num_rows($result_suspender)) {            
                    while ($row_suspender = db_fetch_array($result_suspender)) {     
                ?>
                <option value="<?=$row_suspender['actor_id']?>" <?php if($info['suspender'] == $row_suspender['actor_id']) echo 'selected="selected"';?>><?=$row_suspender['actor_name']?></option>
                <?php
                    }
                 }
                ?>                
            </select> 
            &nbsp;<font class="error">*&nbsp;<?=$errors['suspender']?></font>
        </td>
    </tr>
    <tr>
        <td align="left"><b>CSD:</b></td>
        <td>
            <select name="csd">
                <option value="">Select CSD</option>
                <?php
                $query="SELECT actor_id,actor_name FROM " . ACTOR_TABLE ." WHERE status=1 AND type=3 ORDER BY updated DESC, actor_name ASC";                
                $result_csd = db_query($query);
                 if($result_csd && db_num_rows($result_csd)) {            
                    while ($row_csd = db_fetch_array($result_csd)) {     
                ?>
                <option value="<?=$row_csd['actor_id']?>" <?php if($info['csd'] == $row_csd['actor_id']) echo 'selected="selected"';?>><?=$row_csd['actor_name']?></option>
                <?php
                    }
                 }
                ?>                
            </select>             
            &nbsp;<font class="error">*&nbsp;<?=$errors['csd']?></font>
        </td>
    </tr>
    <!-- the end -->
    <tr>
        <th valign="top">Message:</th>
        <td>
            <? if($errors['message']) {?> <font class="error"><b>&nbsp;<?=$errors['message']?></b></font><br/><?}?>
            <textarea name="message" cols="35" rows="8" wrap="soft" style="width:85%"><?=$info['message']?></textarea></td>
    </tr>
    <?
    if($cfg->allowPriorityChange() ) {
      $sql='SELECT priority_id,priority_desc FROM '.TICKET_PRIORITY_TABLE.' WHERE ispublic=1 ORDER BY priority_urgency DESC';
      if(($priorities=db_query($sql)) && db_num_rows($priorities)){ ?>
      <tr>
        <td>Priority:</td>
        <td>
            <select name="pri">
              <?
                $info['pri']=$info['pri']?$info['pri']:$cfg->getDefaultPriorityId(); //use system's default priority.
                while($row=db_fetch_array($priorities)){ ?>
                    <option value="<?=$row['priority_id']?>" <?=$info['pri']==$row['priority_id']?'selected':''?> ><?=$row['priority_desc']?></option>
              <?}?>
            </select>
        </td>
       </tr>
    <? }
    }?>

    <?if(($cfg->allowOnlineAttachments() && !$cfg->allowAttachmentsOnlogin())  
                || ($cfg->allowAttachmentsOnlogin() && ($thisclient && $thisclient->isValid()))){
        
        ?>
    <tr>
        <td>Attachment:</td>
        <td>
            <input type="file" name="attachment"><font class="error">&nbsp;<?=$errors['attachment']?></font>
        </td>
    </tr>
    <?}?>
    <?if($cfg && $cfg->enableCaptcha() && (!$thisclient || !$thisclient->isValid())) {
        if($_POST && $errors && !$errors['captcha'])
            $errors['captcha']='Please re-enter the text again';
        ?>
    <tr>
        <th valign="top">Captcha Text:</th>
        <td><img src="captcha.php" border="0" align="left">
        <span>&nbsp;&nbsp;<input type="text" name="captcha" size="7" value="">&nbsp;<i>Enter the text shown on the image.</i></span><br/>
                <font class="error">&nbsp;<?=$errors['captcha']?></font>
        </td>
    </tr>
    <?}?>
    <tr height=2px><td align="left" colspan=2 >&nbsp;</td></tr>
    <tr>
        <td></td>
        <td>
            <input class="button" type="submit" name="submit_x" value="Submit Ticket">
            <input class="button" type="reset" value="Reset">
            <input class="button" type="button" name="cancel" value="Cancel" onClick='window.location.href="index.php"'>    
        </td>
    </tr>
</table>
</form>
