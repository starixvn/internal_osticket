<?php
if(!defined('OSTADMININC') || !$thisuser->isadmin()) die('Access Denied');
$info = null;
if($team && $_REQUEST['a'] != 'new'){
    //Editing Department.
    $title = 'Update Team';
    $action = 'update';
    $info = $team->getInfo();
}else{
    $title = 'New Team';
    $action = 'create';  
}
$info = ($errors && $_POST) ? Format::input($_POST) : Format::htmlchars($info);
?>

<div class="msg"><?=$title?></div>
<form action="admin.php?t=team&id=<?=$info['team_id']?>" method="POST" name="team">
    <table width="100%" border="0" cellspacing=0 cellpadding=0>
     <input type="hidden" name="do" value="<?=$action?>">
     <input type="hidden" name="a" value="<?=Format::htmlchars($_REQUEST['a'])?>">
     <input type="hidden" name="t" value="team">
     <input type="hidden" name="team_id" value="<?=$info['team_id']?>">
     <tr>
         <td>
            <table width="100%" border="0" cellspacing=0 cellpadding=2 class="tform">  
                <tr class="header">
                    <td colspan="2">Team</td>
                </tr>
                <tr class="subheader">
                    <td colspan="2"><i>Team information</i></td>
                </tr>
                <tr>
                    <th>Team Name:</th>
                    <td>
                        <input type="text" name="team_name" maxlength="255" value="<?=$info['team_name']?>" maxlength="255">
                        &nbsp;<font class="error">*&nbsp;<?=$errors['team_name']?></font>
                    </td>
                </tr>
                <tr>
                    <th>Sport:</th>
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
                 <tr>
                     <th>IBC Team ID:</th>
                     <td>
                        <input type="text" name="ibc_team_id" maxlength="11" value="<?=$info['ibc_team_id']?>" maxlength="13" style="width: 200px">
                        &nbsp;<font class="error"><?=$errors['ibc_team_id']?></font>
                    </td>
                </tr>
            </table>
        </td>
     </tr>
     <tr>
        <td style="padding:10px 0 10px 200px;">
            <input class="button" type="submit" name="submit" value="Submit" id="submit" maxlength="255">        
            <input class="button" type="button" name="cancel" value="Cancel" onClick='window.location.href="admin.php?t=teams"'>
            <?php            
            if($info['team_id'] != null) {
            ?>
            <input type="hidden" value="0" name="delete_field" id="delete_field"/>
            <input class="button" type="submit" name="delete" id="delete" value="Delete" 
                    onClick=' return confirm("Are you sure you want to DELETE team?");'>
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