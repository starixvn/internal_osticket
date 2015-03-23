<table class="td_txt_left">
   
    <tr><th>Sport:</th>
        <td>
            <span id="sport_type_name"></span>
            <input type="hidden" id="sport_type_popup" value=""/>            
        </td>
    </tr>
    <tr><th>League:</th>
        <td>
            <span id="selected_league_name"></span>
            <input type="hidden" id="league_id_popup" value=""/>             
        </td>
    </tr>
    <tr><th>Match Code:</th>
        <td><input type="text" id="match_code_popup" name="match_code_popup"  size=25 maxlength="255"
    >                &nbsp;<font class="error">*</font>
        </td>
    </tr>    
    <tr><th>Home:</th>
        <td>
            <select name="home_id_popup" id="home_id_popup" data-placeholder="Select home team" class="home-popup-select" style="width:450px;" tabindex="2">                
                <option value="">Select home team</option>                
            </select>            
            &nbsp;<font class="error">*&nbsp;</font>
        </td>
    </tr>
    <tr><th>Away:</th>
        <td>
            <select name="away_id_popup" id="away_id_popup" data-placeholder="Select away team" class="away-popup-select" style="width:450px;" tabindex="2">                
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
             &nbsp;<font class="error">*</font>
        </td>
    </tr>       
    <tr>
        <th align="left" valign="top">Event Date:</th>
        <td>           
            <i>Time is based on your time zone (GM <?= $thisuser->getTZoffset() ?>)</i>&nbsp;<font class="error">&nbsp;<?= $errors['time'] ?></font><br>
            <input id="duedate_popup" name="duedate" 
                   onclick="event.cancelBubble=true;calendar(this);" autocomplete=OFF>
            <a href="#" onclick="event.cancelBubble=true;calendar(getObj('duedate_popup')); return false;"><img src='images/cal.png'border=0 alt=""></a>

            &nbsp;<font class="error">*</font>
        </td>
    </tr>
    <tr><th>ICB Match ID:</th>
        <td><input type="text" name="ibc_match_id" id="ibc_match_id" size=25  maxlength="255" style="width: 200px">

        </td>
    </tr>
    
</table>
