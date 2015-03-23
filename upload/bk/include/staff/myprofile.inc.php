<?php
if(!defined('OSTSCPINC') || !is_object($thisuser)) die('Kwaheri');

?>
<div class="msg" style="margin-left: 6px;">My Profile Info</div>
<table width="100%" border="0" cellspacing=0 cellpadding=2 class="front_layer">
 <form action="profile.php" method="post">
 <input type="hidden" name="t" value="info">
 <input type="hidden" name="id" value="<?=$thisuser->getId()?>">
    <tr>
        <td width="110"><b>Username:</b></td>
        <td>&nbsp;<?=$thisuser->getUserName()?></td>
    </tr>
    <tr>
        <td>First Name:</td>
        <td><input type="text" name="firstname" maxlength="32" value="<?=$rep['firstname']?>">
            &nbsp;<font class="error">*&nbsp;<?=$errors['firstname']?></font></td>
    </tr>
    <tr>
        <td>Last Name:</td>
        <td><input type="text" name="lastname" maxlength="32" value="<?=$rep['lastname']?>">
            &nbsp;<font class="error">*&nbsp;<?=$errors['lastname']?></font></td>
    </tr>
    <tr>
        <td>Email Address:</td>
        <td><input type="text" name="email" size=25 maxlength="128" value="<?=$rep['email']?>">
            &nbsp;<font class="error">*&nbsp;<?=$errors['email']?></font></td>
    </tr>
    <tr>
        <td>Office Phone:</td>
        <td>
            <input type="text" name="phone" maxlength="24" value="<?=$rep['phone']?>" class="phone1"><font class="error">&nbsp;<?=$errors['phone']?></font>&nbsp;Ext&nbsp;
            <input type="text" name="phone_ext" size=6 maxlength="6" value="<?=$rep['phone_ext']?>" class="phone2">
            <font class="error">&nbsp;<?=$errors['phone_ext']?></font>
        </td>
    </tr>
    <tr>
        <td>Cell Phone:</td>
        <td><input type="text" name="mobile" maxlength="24" value="<?=$rep['mobile']?>" class="phone1">
            &nbsp;<font class="error">&nbsp;<?=$errors['mobile']?></font></td>
    </tr>
    <tr>
        <td valign="top">Signature:</td>
        <td><textarea name="signature" cols="21" rows="5"><?=$rep['signature']?></textarea></td>
    </tr>
    <tr><td>&nbsp;</td>
        <td> <br/>
            <input class="button" type="submit" name="submit" value="Save">
            <input class="button" type="reset" name="reset" value="Reset">
            <input class="button" type="button" name="cancel" value="Cancel" onClick='window.location.href="index.php"'>
        </td>
    </tr>
 </form>
</table> 
