<?php
if(!defined('OSTADMININC') || !$thisuser->isadmin()) die('Access Denied');
$info = null;
if($sport && $_REQUEST['a'] != 'new'){
    //Editing Department.
    $title = 'Update Sport';
    $action = 'update';
    $info = $sport->getInfo();
}else{
    $title = 'New Sport';
    $action = 'create';
}
$info=($errors && $_POST) ? Format::input($_POST) : Format::htmlchars($info);
?>
<div class="msg"><?=$title?></div>
<form action="admin.php?t=sport&id=<?=$info['sport_type']?>" method="POST" name="sport">
    <table width="100%" border="0" cellspacing=0 cellpadding=0>
        <input type="hidden" name="do" value="<?=$action?>">
        <input type="hidden" name="a" value="<?=Format::htmlchars($_REQUEST['a'])?>">
        <input type="hidden" name="t" value="sport">
        <input type="hidden" name="sport_type" value="<?=$info['sport_type']?>">
        <tr>
            <td>
                <table width="100%" border="0" cellspacing=0 cellpadding=2 class="tform">  
                    <tr class="header">
                        <td colspan="2">Sport</td>
                    </tr>
                    <tr class="subheader">
                        <td colspan="2"><i>Sport information</i></td>
                    </tr>
                    <tr>
                        <th>Name:</th>
                        <td>
                            <input type="text" name="sport_name" size=25 maxlength="255" value="<?=$info['sport_name']?>" maxlength="255">
                            &nbsp;<font class="error">*&nbsp;<?=$errors['sport_name']?></font>
                        </td>
                    </tr>
                </table>
           </td>
        </tr>
        <tr>
            <td style="padding:10px 0 10px 200px;">
                <input class="button" type="submit" name="submit" value="Submit" id="submit" maxlength="255">        
                <input class="button" type="button" name="cancel" value="Cancel" onClick='window.location.href="admin.php?t=sports"'>
                <?php                 
                if($info['sport_type'] != null) {
                ?>
                <input type="hidden" value="0" name="delete_field" id="delete_field"/>
                <input class="button" type="submit" name="delete" id="delete" value="Delete" 
                        onClick=' return confirm("Are you sure you want to DELETE sport?");'>
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