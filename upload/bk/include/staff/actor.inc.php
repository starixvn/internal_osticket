<?php
if(!defined('OSTADMININC') || !$thisuser->isadmin()) die('Access Denied');
$info = null;
if($actor && $_REQUEST['a'] != 'new'){
    //Editing Department.
    $title = 'Update Actor';
    $action = 'update';
    $info = $actor->getInfo();
}else{
    $title = 'New Actor';
    $action = 'create';  
}
$info = ($errors && $_POST) ? Format::input($_POST) : Format::htmlchars($info);

if($info['type'] != '') {
    $type = $info['type'];
} else {
    $type = 1;
}
?>
<div class="msg"><?=$title?></div>
<form action="admin.php?t=actor&id=<?=$info['actor_id']?>" method="POST" name="actor">
    <table width="100%" border="0" cellspacing=0 cellpadding=0>
        <input type="hidden" name="do" value="<?=$action?>">
        <input type="hidden" name="a" value="<?=Format::htmlchars($_REQUEST['a'])?>">
        <input type="hidden" name="t" value="actor">
        <input type="hidden" name="actor_id" value="<?=$info['actor_id']?>">
        <tr>
            <td>
                <table width="100%" border="0" cellspacing=0 cellpadding=2 class="tform">
                    <tr class="header"><td colspan="2">Actor</td></tr>
                    <tr class="subheader"><td colspan="2"><i>Actor information</i></td></tr>
                    <tr>
                        <th>Name:</th>
                        <td>
                            <input type="text" name="actor_name" size=25 maxlength="255" value="<?=$info['actor_name']?>" maxlength="255">
                            &nbsp;<font class="error">*&nbsp;<?=$errors['actor_name']?></font>
                        </td>
                    </tr>
                    <tr>
                        <th>Type:</th>
                        <td>
                            <input type="radio" name="type" value="1" <?php if($type == 1) echo 'checked="checked"'; ?> /> Trader &nbsp;&nbsp;               
                            <input type="radio" name="type" value="2" <?php if($type == 2) echo 'checked="checked"'; ?>/> Suspender &nbsp;&nbsp;                
                            <input type="radio" name="type" value="3" <?php if($type == 3) echo 'checked="checked"'; ?>/> CSD
                            <input type="radio" name="type" value="4" <?php if($type == 4) echo 'checked="checked"'; ?>/> Provider
                         &nbsp;<font class="error">&nbsp;<?=$errors['type']?></font>
                        </td>
                    </tr>
                    <tr>
                        <th>Contact Information:</th>
                        <td>
                            <textarea cols="20" rows="3" name="contact_info"><?=$info['contact_info']?></textarea>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding:10px 0 10px 200px;">
                <input class="button" type="submit" name="submit" value="Submit" id="submit">        
                <input class="button" type="button" name="cancel" value="Cancel" onClick='window.location.href="admin.php?t=matches"'>
                <?php
                if($info['actor_id'] != null) {
                ?>
                <input type="hidden" value="0" name="delete_field" id="delete_field"/>
                <input class="button" type="submit" name="delete" id="delete" value="Delete" 
                        onClick=' return confirm("Are you sure you want to DELETE actor?");'>
                <?php
                }
                ?>
            </td>
        </tr>
    </table>
</form>
<script>
    $(function(){
        $('#submit').click(function(){            
            $('#delete_field').val('0');
        })
        $('#delete').click(function(){            
            $('#delete_field').val('1');
        })
    })
</script>