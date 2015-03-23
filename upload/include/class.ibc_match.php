<?php
/*********************************************************************
    class.dept.php
    
    Department class

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2010 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
    $Id: $
**********************************************************************/
class IBC_Match {
    var $id;    
    var $league_id;
    var $home_id;
    var $away_id;
    var $event_date;
    var $match_code;
    var $sport_type;    
    var $row;
  
    function IBC_Match($id = 0){
        $this->id = 0;
        if($id && ($info = $this->getInfoById($id))){  
            $this->row = $info;
            $this->id = $info['match_id'];            
            $this->league_id = $info['league_id'];
            $this->home_id = $info['home_id'];
            $this->away_id = $info['away_id'];           
            $this->event_date = $info['event_date'];
            $this->kickofftime = $info['kickofftime'];
            $this->match_code = $info['match_code'];
            $this->sport_type = $info['sport_type'];
        }
    }

    function getId(){
        return $this->id;
    }
    
    function getLeagueId(){
        return $this->league_id;
    }
    
    function getHomeId(){
        return $this->home_id;
    }
    function getAwayId(){
        return $this->away_id;
    }    
    function getEventDate(){
        return $this->event_date;
    }
    function getKickofftime(){
        return $this->kickofftime;
    }
    function getMatchCode(){
        return $this->match_code;
    }
    function getSportType(){
        return $this->sport_type;
    }
    
    function getInfo() {
        return $this->row;
    }

    function update($vars, &$errors) {
        if($this->save($this->getId(), $vars, $errors)){
            return true;
        }
        return false;
    }

    function getInfoById($id) {
        $sql = 'SELECT * FROM ' . IBC_MATCH_TABLE . ' WHERE match_id=' . db_input($id);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            return db_fetch_array($res);
        }
        return null;
    }    
        
    function getIdByName($name) {
        $id = 0;
        $sql = 'SELECT match_id FROM ' . IBC_MATCH_TABLE . ' WHERE match_name=' . db_input($name);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            list($id) = db_fetch_row($res);
        }
        return $id;
    }

    function getNameById($id) {
        $sql = 'SELECT match_name FROM ' . IBC_MATCH_TABLE . ' WHERE match_id=' . db_input($id);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            list($name) = db_fetch_row($res);
        }
        return $name;
    }

    function create($vars, &$errors) {
        return IBC_Match::save(0, $vars, $errors);
    }

    function delete($id) {
        global $cfg;        
        $sql = 'DELETE FROM ' . IBC_MATCH_TABLE . ' WHERE match_id=' . db_input($id);
        if(db_query($sql) && ($num = db_affected_rows())){           
            return $num;
        }        
        return 0;
    }

    function save($id, $vars, &$errors) {
        global $cfg; 
        // get event date for match
        $event_date = ($vars['event_date'] ? db_input(date('Y-m-d', strtotime($vars['event_date']))) : 'NULL');
        
        // validate value 
        if($id && $id != $vars['match_id'])
            $errors['err'] = 'Missing or invalid match ID';
        
        if(!$vars['match_code']) {
            $errors['match_code'] = 'Match code required';
        } else {
            if(!$id) {
                $sql = 'SELECT match_id FROM ' . IBC_MATCH_TABLE . ' WHERE match_code=' . db_input($vars['match_code']);           
            } else {
                $sql = "SELECT match_id FROM " . IBC_MATCH_TABLE .
                       " WHERE match_code " .
                       " IN (SELECT match_code FROM " . IBC_MATCH_TABLE .
                       " WHERE match_code='" . db_input($vars['match_code']) .
                       "' AND match_id!='" . $id . "')"; 
            }  
            // check match code already existed
            if(db_num_rows(db_query($sql))) {
                $errors['match_code'] = 'Match code already exist';
            }            
        }
        
        if(!$vars['sport_type']) {
            $errors['sport_type'] = 'Sport name required';
        }
        
        if(!$vars['league_id']) {
            $errors['league_id'] = 'League required';
        }
        
        if(!$vars['home_id']) {
            $errors['home_id'] = 'Home team required';
        }
        
        if(!$vars['away_id']) {
            $errors['away_id'] = 'Aqay team required';
        }
        
        if(!$vars['duedate']) {
            $errors['duedate'] = 'Event date required';
        } else{
            $sql='SELECT match_id FROM ' . IBC_MATCH_TABLE .
                    ' WHERE home_id=' . db_input($_POST['home_id']) . 
                    ' AND away_id='.db_input($_POST['away_id']) .
                    ' AND event_date=' . $event_date . 
                    ' AND sport_type=' . db_input($_POST['sport_type']);
            if($id) {
                $sql .= ' AND match_id!=' . db_input($id);
            }                
            if(db_num_rows(db_query($sql))) {
                $errors['duedate'] = 'Match already exist';
            }
        }
        
       //Make sure the due date is valid
        if($vars['event_date']){           
            if(strtotime($vars['event_date']) === false)
                $errors['event_date'] = 'Invalid event date';
            elseif(strtotime($vars['duedate']) <= time())
                $errors['event_date'] = 'Event date must be in the future';
        }        
        
        if(!$errors){        
            $sql = ' SET `match_id`=' . db_input($vars['match_id']) .
                 '`league_id`=' . db_input($vars['league_id']) .
                 ',`home_id`=' . db_input($vars['home_id']) .                 
                 ',`away_id`=' . db_input($vars['away_id']) .
                 ',`event_date`=' . $event_date .
                 ',`match_code`=' . db_input($vars['match_code']) .
                 ',`sport_type`=' . db_input($vars['sport_type']);               
            if($id) {
                $sql = 'UPDATE ' . IBC_MATCH_TABLE . ' ' . $sql . ' WHERE match_id=' .db_input($id);                
                if(!db_query($sql) || !db_affected_rows()) {
                    $errors['err'] = 'Unable to update ' . Format::input($_POST['match_name']) . ' match. Error occured';
                }
            }else{
                $sql = 'INSERT INTO ' . IBC_MATCH_TABLE . ' ' . $sql;
                if(db_query($sql) && ($leagueID = db_insert_id())) {
                    return $leagueID;
                }
                $errors['err'] = 'Unable to create match. Internal error';
            }
        }
        return $errors ? false : true;
    }
}
?>
