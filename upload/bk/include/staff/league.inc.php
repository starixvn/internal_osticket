<?php
if(!defined('OSTADMININC') || !$thisuser->isadmin()) die('Access Denied');
$info = null;
if($league && $_REQUEST['a'] != 'new'){
    //Editing Department.
    $title = 'Update League';
    $action = 'update';
    $info = $league->getInfo();
}else{
    $title = 'New League';
    $action = 'create';  
}
$info = ($errors && $_POST) ? Format::input($_POST) : Format::htmlchars($info);
?>
<div class="msg"><?=$title?></div>
<form action="admin.php?t=league&id=<?=$info['league_id']?>" method="POST" name="league">
    <table width="100%" border="0" cellspacing=0 cellpadding=0>
    <input type="hidden" name="do" value="<?=$action?>">
    <input type="hidden" name="a" value="<?=Format::htmlchars($_REQUEST['a'])?>">
    <input type="hidden" name="t" value="league">
    <input type="hidden" name="league_id" value="<?=$info['league_id']?>">
    <tr>
        <td>
            <table width="100%" border="0" cellspacing=0 cellpadding=2 class="tform">  
                <tr class="header"><td colspan="2">League</td></tr>
                <tr class="subheader"><td colspan="2"><i>League information</i></td></tr>
                <tr><th>League Name:</th>
                    <td><input type="text" name="league_name" size=25 maxlength="255" value="<?=$info['league_name']?>">
                        &nbsp;<font class="error">*&nbsp;<?=$errors['league_name']?></font>
                    </td>
                </tr>
                <tr><th>Sport:</th>
                    <td>
                        <select name="sport_type" id="sport" data-placeholder="Select Sport" class="sport-select select_search" tabindex="2">
                            <?php
                            $query="SELECT sport_type,sport_name FROM " . SPORT_TABLE ." WHERE status=1 ORDER BY updated DESC, sport_name ASC";                
                            $result_sport = db_query($query);
                             if($result_sport && db_num_rows($result_sport)) {            
                                while ($row_sport = db_fetch_array($result_sport)) {     
                            ?>
                            <option value="<?=$row_sport['sport_type']?>" <?php if($info['sport_type'] == $row_sport['sport_type']) echo 'selected="selected"';?>><?=$row_sport['sport_name']?></option>
                            <?php
                                }
                             }
                            ?>                     
                        </select>                         
                        &nbsp;<font class="error">*&nbsp;</font>
                    </td>
                </tr>
                 <tr><th>IBC League ID:</th>
                     <td>
                         <input type="text" name="ibc_league_id" maxlength="11" value="<?=$info['ibc_league_id']?>" style="width: 200px" maxlength="13">
                        &nbsp;<font class="error"><?=$errors['ibc_league_id']?></font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:10px 0 10px 200px;">
            <input class="button" type="submit" name="submit" value="Submit" id="submit" maxlength="255">        
            <input class="button" type="button" name="cancel" value="Cancel" onClick='window.location.href="admin.php?t=leagues"'>
            <?php            
            if($info['league_id'] != null) {
            ?>
            <input type="hidden" value="0" name="delete_field" id="delete_field"/>
            <input class="button" type="submit" name="delete" id="delete" value="Delete" 
                    onClick=' return confirm("Are you sure you want to DELETE league?");'>
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
        
        // auto complate word for selectbox 'sport'
        $(".sport-select").chosen({allow_single_deselect:true});
    })
</script>