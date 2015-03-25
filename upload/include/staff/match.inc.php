<?php
if(!defined('OSTADMININC') || !$thisuser->isadmin()) die('Access Denied');
$info = null;
if($match && $_REQUEST['a'] != 'new'){
    //Editing Department.
    $title = 'Update Match';
    $action = 'update';
    $info = $match->getInfo();
}else{
    $title = 'New Match';
    $action = 'create'; 
}
$info = ($errors && $_POST) ? Format::input($_POST) : Format::htmlchars($info);
?>
<div class="msg"><?=$title?></div>
<form action="admin.php?t=match&id=<?=$info['match_id']?>" method="POST" name="match">
    <table width="100%" border="0" cellspacing=0 cellpadding=0> 
        <input type="hidden" name="do" value="<?=$action?>">
        <input type="hidden" name="a" value="<?=Format::htmlchars($_REQUEST['a'])?>">
        <input type="hidden" name="t" value="match">
        <input type="hidden" name="match_id" value="<?=$info['match_id']?>">
        <tr>
            <td>
                <table width="100%" border="0" cellspacing=0 cellpadding=2 class="tform">  
                    <tr class="header">
                        <td colspan="2">Match</td>
                    </tr>
                    <tr class="subheader">
                        <td colspan="2"><i>Match information</i></td>
                    </tr>
                    <tr>
                        <th>Match Code:</th>
                        <td>
                            <input type="text" name="match_code" size=25 maxlength="255" value="<?=$info['match_code']?>" maxlength="255" class="phone2"
            >                
                            &nbsp;<font class="error">*&nbsp;<?=$errors['match_code']?></font>
                        </td>
                    </tr>
                    <tr>
                        <th>Sport:</th>
                        <td>
                            <select name="sport_type" id="sport_type" data-placeholder="Select Sport" class="sport-select select_search" tabindex="2">
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
                            &nbsp;<font class="error">*&nbsp;<?= $errors['sport_type'] ?></font>
                        </td>
                    </tr>
                    <tr>
                        <th>League:</th>
                        <td>
                            <select name="league_id" id="league_id" data-placeholder="Select League" class="league-select" style="width:450px;" tabindex="2">
                                <option value="">Select league</option>
                                <?php
                                    $query = "SELECT league_id,league_name FROM " . LEAGUE_TABLE . " WHERE status=1 and sport_type='" . $info['sport_type'] . "' ORDER BY updated DESC, league_name ASC";
                                    $result_league = db_query($query);
                                    if ($result_league && db_num_rows($result_league)) {
                                        while ($row_league = db_fetch_array($result_league)) {
                                            ?>
                                            <option value="<?= $row_league['league_id'] ?>" <?php if ($info['league_id'] == $row_league['league_id']) echo 'selected="selected"'; ?>><?= $row_league['league_name'] ?></option>
                                            <?php
                                        }
                                    }
                                ?>
                            </select>                            
                            &nbsp;<font class="error">*&nbsp;<?= $errors['league_id'] ?></font>
                        </td>
                    </tr>
                    <tr>
                        <th>Home:</th>
                        <td>
                            <select name="home_id" id="home_id" data-placeholder="Select home team" class="home-select" style="width:450px;" tabindex="2">                
                                <option value="">Select home team</option>
                                <?php
                                $query = "SELECT team_id,team_name FROM " . TEAM_TABLE ." WHERE status=1 AND sport_type='" . $info['sport_type'] . "' ORDER BY updated DESC, team_name ASC";                
                                $result_team = db_query($query);
                                 if($result_league && db_num_rows($result_team)) {            
                                    while ($row_team = db_fetch_array($result_team)) {     
                                ?>
                                <option value="<?=$row_team['team_id']?>" <?php if($info['home_id'] == $row_team['team_id']) echo 'selected="selected"';?>><?=$row_team['team_name']?></option>
                                <?php
                                    }
                                 }
                                ?>   
                            </select>
                            &nbsp;<font class="error">*&nbsp;<?= $errors['home_id'] ?></font>
                        </td>
                    </tr>
                    <tr>
                        <th>Away:</th>
                        <td>
                            <select name="away_id" id="away_id" data-placeholder="Select away team" class="away-select" style="width:450px;" tabindex="2">                
                                <option value="">Select away team</option> 
                                <?php
                                $query="SELECT team_id,team_name FROM " . TEAM_TABLE ." WHERE status=1 AND sport_type='" . $info['sport_type'] . "' ORDER BY updated DESC, team_name ASC";                
                                $result_team = db_query($query);
                                 if($result_league && db_num_rows($result_team)) {            
                                    while ($row_team = db_fetch_array($result_team)) {     
                                ?>
                                <option value="<?=$row_team['team_id']?>" <?php if($info['away_id'] == $row_team['team_id']) echo 'selected="selected"';?>><?=$row_team['team_name']?></option>
                                <?php
                                    }
                                 }
                                ?>
                            </select>
                             &nbsp;<font class="error">*&nbsp;<?= $errors['away_id'] ?></font>
                        </td>
                    </tr>       
                    <tr>
                        <th align="left" valign="top">Event Date:</th>
                        <td>
                            <?php                            
                            if(isset($info['event_date']) == true && isset($info['duedate']) == false) {
                                $strTime = $info['event_date'];
                            }
                            if(isset($info['event_date']) == false && isset($info['duedate']) == true) {
                                $strTime = $info['duedate'];
                            }
                            $duedate = $strTime ? (Format::userdate('m/d/Y',Misc::db2gmtime($strTime))) : '';
                            ?>
                            <i>Time is based on your time zone (GM <?= $thisuser->getTZoffset() ?>)</i>&nbsp;<font class="error">&nbsp;<?= $errors['time'] ?></font><br>
                            <input id="duedate" name="duedate" value="<?= Format::htmlchars($duedate) ?>"
                                   onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF class="phone2">
                            <a href="#" onclick="event.cancelBubble=true;calendar(getObj('duedate')); return false;"><img src='images/cal.png'border=0 alt=""></a>

                            &nbsp;<font class="error">*&nbsp;<?= $errors['duedate'] ?></font>
                        </td>
                    </tr>
                    <tr>
                        <th align="left" valign="top">Kick Off Time:</th>
                        <td>
                            <?php
                            $objKickofftime = new DateTime($info['kickofftime']);
                            $kickofftime = $objKickofftime->format('m/d/Y');
                            ?>
                            <input id="kickofftime" name="kickofftime" value="<?= Format::htmlchars($kickofftime) ?>"
                                    onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF style="width: 100px">
                             <a href="#" onclick="event.cancelBubble=true;calendar(getObj('kickofftime')); return false;"><img src='images/cal.png'border=0 alt=""></a>
                             &nbsp;&nbsp;
                             <?php
                             $min = $hr = null;
                             $time = $objKickofftime->format('G:i');
                             if ($time)
                                 list($hr, $min) = explode(':', $time);
                             echo Misc::timeDropdown($hr, $min, 'time');
                             ?>
                             &nbsp;<label class="error">&nbsp;<?= $errors['kickofftime'] ?></label>                            
                        </td>
                    </tr>
                    <tr>
                        <th>IBC Match ID:</th>
                        <td>
                            <?php
                            if($info['ibc_match_id'] == 0 || $info['ibc_match_id'] == "") {
                                $ibc_match_id = "";
                            } else {
                                $ibc_match_id = $info['ibc_match_id'];
                            }
                            ?>
                            <input type="text" name="ibc_match_id" size=25 maxlength="11" value="<?=$ibc_match_id?>" maxlength="255" class="phone1">
                            &nbsp;<font class="error">&nbsp;<?= $errors['ibc_match_id'] ?></font>
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
                if($info['match_id'] != null) {
                ?>
                <input type="hidden" value="0" name="delete_field" id="delete_field"/>
                <input class="button" type="submit" name="delete" id="delete" value="Delete" 
                        onClick=' return confirm("Are you sure you want to DELETE match?");'>
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
        
        // auto complate word for selectbox 'league'
        $(".league-select").chosen({allow_single_deselect:true});
        
        // auto complate word for selectbox 'home team'
        $(".home-select").chosen({allow_single_deselect:true});
        
        // auto complate word for selectbox 'away team'
        $(".away-select").chosen({allow_single_deselect:true});
        
        // load league when changing sport option
        $('#sport_type').change(function(){ 
           var sport_type = $('#sport_type').val();
            
           // default value for league           
           $("#league_id").html('');
           $("#league_id").append('<option value="">Select league</option>');
           
           // default value for home team and away team
           $("#home_id").html('');
           $("#home_id").append('<option value="">Select home team</option>');  
           $("#away_id").html('');
           $("#away_id").append('<option value="">Select away team</option>');  
           $.ajax({
                type:"GET",
                url: "loadAjax.php",
                data: {sport_type:sport_type, type:'change_sport'},
                dataType: "json",
                success: function(data){console.log(data);                   
                    $.each(data, function(key_list, value_list){
                        //load league
                        if(key_list == 'league') {                                
                            $.each(value_list, function(key, value){
                                $("#league_id").append('<option value="' + value.league_id + '">' + value.league_name + '</option>');
                            });                            
                        } 
                        // load team list
                        if(key_list == 'team') {
                            $.each(value_list, function(key, value){
                                $("#home_id").append('<option value="' + value.team_id + '">' + value.team_name + '</option>');
                                $("#away_id").append('<option value="' + value.team_id + '">' + value.team_name + '</option>');
                            });
                        }                                                                  
                    });
                    $(".league-select").trigger("liszt:updated");
                    $(".home-select").trigger("liszt:updated");
                    $(".away-select").trigger("liszt:updated");
                },  
                error: function(){}  
            });           
        })
        
        // if this team is chosen at home team, this team will not be chosen at away team
        $('#home_id').change(function(){
            home_id = $('#home_id').val();
            $("#away_id option[value='" + home_id + "']").remove();
             $(".away-select").trigger("liszt:updated");                    
        });
        
        // if this team is chosen at away team, this team will not be chosen at home team
        $('#away_id').change(function(){
            away_id = $('#away_id').val();
            $("#home_id option[value='" + away_id + "']").remove();
            $(".home-select").trigger("liszt:updated");
        });            
    })
</script>