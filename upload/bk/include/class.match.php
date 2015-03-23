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
class Match extends IBC_Match{        
    var $ibc_match_id;    
    var $status;
    var $row;
  
    function Match($id = 0){
        $this->id = 0;
        if($id && ($info = $this->getInfoById($id))){  
            $this->row = $info;
            $this->id = $info['match_id'];            
            $this->league_id = $info['league_id'];
            $this->home_id = $info['home_id'];
            $this->away_id = $info['away_id'];
            $this->ibc_match_id = $info['ibc_match_id'];
            $this->event_date = $info['event_date'];
            $this->kickofftime = $info['kickofftime'];
            $this->match_code = $info['match_code'];
            $this->sport_type = $info['sport_type'];            
            $this->status = $info['status'];           
        }
    }
    
    function getIbcMatchId(){
        return $this->ibc_match_id;
    }
    
    function getStatus(){
        return $this->status;
    }
    
    function getKickofftime(){
        return $this->kickofftime;
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
        $sql = 'SELECT * FROM ' . MATCH_TABLE . ' WHERE status=1 AND match_id=' . db_input($id);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            return db_fetch_array($res);
        }
        return null;
    }
    
    function getInfoByIbcId($ibc_id) {
        $sql = 'SELECT * FROM ' . MATCH_TABLE . ' WHERE status=1 AND ibc_match_id=' . db_input($ibc_id);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            return db_fetch_array($res);
        }
        return null;
    }
    
    function getIdByName($name) {
        $id = 0;
        $sql = 'SELECT match_id FROM ' . MATCH_TABLE . ' WHERE match_name=' . db_input($name);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            list($id) = db_fetch_row($res);
        }
        return $id;
    }

    function getNameById($id) {
        $sql = 'SELECT match_name FROM ' . MATCH_TABLE . ' WHERE match_id=' . db_input($id);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            list($name) = db_fetch_row($res);
        }
        return $name;
    }

    function create($vars, &$errors) {
        return Match::save(0, $vars, $errors);
    }

    function delete($id) {
        global $cfg;        
        $sql = 'UPDATE ' . MATCH_TABLE . ' SET status=0 WHERE match_id=' . db_input($id);
        if(db_query($sql) && ($num = db_affected_rows())){           
            return $num;
        }        
        return 0;
    }

    function save($id, $vars, &$errors) {
        global $cfg; 
        // get event date for match
        $event_date = ($vars['duedate'] ? db_input(date('Y-m-d', strtotime($vars['duedate']))) : 'NULL');
        
        // validate value 
        if($id && $id != $vars['match_id'])
            $errors['err'] = 'Missing or invalid match ID';
        
        if(!$vars['match_code']) {
            $errors['match_code'] = 'Match code required';
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
            $sql='SELECT match_id FROM ' . MATCH_TABLE .
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
        if($vars['duedate']){           
            if(strtotime($vars['duedate']) === false) {
                $errors['duedate'] = 'Invalid event date';
            }
        }
        
        if ($vars['ibc_match_id'] == '') {
            $vars['ibc_match_id'] = 0;        
        }
        if($vars['ibc_match_id'] != 0 && is_numeric($vars['ibc_match_id']) == false) {
            $errors['ibc_match_id'] = 'IBC match ID required number';
        }
        
        if($vars['time'] != '') {
            $kickofftime = date('Y-m-d G:i', Misc::dbtime($vars['kickofftime'] . ' ' . $vars['time']));
        } else {
            $kickofftime = $vars['kickofftime'];
        }
        if(!$errors){        
            $sql = ' SET `league_id`=' . db_input($vars['league_id']) .
                 ',`home_id`=' . db_input($vars['home_id']) .                 
                 ',`away_id`=' . db_input($vars['away_id']) .
                 ',`ibc_match_id`='.db_input($vars['ibc_match_id']) .
                 ',`event_date`=' . $event_date .
                 ",`kickofftime`='" . $kickofftime .
                 "',`match_code`=" . db_input($vars['match_code']) .
                 ',`sport_type`=' . db_input($vars['sport_type']) .
                 ',`updated`=NOW()';               
            if($id) {
                if(isset($_POST['delete_field']) == true && $_POST['delete_field'] == 1) {
                     $sql = 'UPDATE ' . MATCH_TABLE . ' SET status=0,updated=NOW() WHERE match_id=' . db_input($id);                     
                 } else {
                     $sql = 'UPDATE ' . MATCH_TABLE . ' ' . $sql . ' WHERE match_id=' .db_input($id);
                 }                 
                if(!db_query($sql) || !db_affected_rows()) {
                    $errors['err'] = 'Unable to update ' . Format::input($vars['match_name']) . ' match. Error occured';
                }
            }else{                
                $sql = 'INSERT INTO ' . MATCH_TABLE . ' ' . $sql;
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
