<?php
/*********************************************************************
    class.team.php
    
    team class

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2010 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
    $Id: $
**********************************************************************/
class Team extends IBC_Team{    
    var $ibc_team_id;
    var $status;
    var $updated;
    
    var $row;
    function Team($id = 0){
        $this->id = 0;
        if($id && ($info = $this->getInfoById($id))){ 
            $this->row = $info;
            $this->id = $info['team_id'];
            $this->name = $info['team_name'];
            $this->sport_type = $info['port_type'];
            $this->ibc_team_id = $info['ibc_team_id'];
            $this->status = $info['status']; 
            $this->updated = $info['updated']; 
        }
    }
    
    function getIbcTeamId(){
        return $this->ibc_team_id;
    }
    function getStatus(){
        return $this->status;
    }
    
    function getUpdated(){
        return $this->updated;
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
        $sql = 'SELECT * FROM ' . TEAM_TABLE . ' WHERE status=1 AND team_id=' . db_input($id);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            return db_fetch_array($res);
        }
        return null;
    }
    
    function getInfoByIbcId($ibc_id) {
        $sql = 'SELECT * FROM ' . TEAM_TABLE . ' WHERE status=1 AND ibc_team_id=' . db_input($ibc_id);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            return db_fetch_array($res);
        }
        return null;
    }

    function getIdByName($name) {
        $id = 0;
        $sql = 'SELECT team_id FROM ' . TEAM_TABLE . ' WHERE team_name=' . db_input($name);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            list($id) = db_fetch_row($res);
        }
        return $id;
    }

    function getNameById($id) {
        $sql = 'SELECT team_name FROM ' . TEAM_TABLE . ' WHERE team_id=' . db_input($id);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            list($name) = db_fetch_row($res);
        }
        return $name;
    }

    function create($vars, &$errors) {
        return Team::save(0, $vars, $errors);
    }

    function delete($id) {
        global $cfg;        
        $sql = 'UPDATE ' . TEAM_TABLE . ' SET status=0 WHERE team_id=' . db_input($id);
        if(db_query($sql) && ($num = db_affected_rows())){           
            return $num;
        }
        return 0;
    }

    function save($id, $vars, &$errors) {
        global $cfg;
                
        if($id && $id != $vars['team_id']) {
            $errors['err'] = 'Missing or invalid team ID';
        }
        
        if($vars['ibc_team_id'] == '') {
            $vars['ibc_team_id'] = 0;            
        }
        
        if(!$vars['team_name']) {
            $errors['team_name'] = 'Team name required';
        }elseif(strlen($vars['team_name']) < 4) {
            $errors['team_name'] = 'Team name must be at least 4 chars.';
        }else{            
            $sql = "SELECT team_id FROM " . TEAM_TABLE .
                    " WHERE status=1 AND team_name=" . db_input($vars['team_name']) .
                    " AND sport_type=" . db_input($vars['sport_type']) .
                    " AND ibc_team_id=" . db_input($vars['ibc_team_id']);
            if($id) {
                $sql .= ' AND team_id!=' . db_input($id);
            }           
            if(db_num_rows(db_query($sql))) {
                $errors['team_name'] = 'Team already exist';
            }
        }
        
        if($vars['ibc_team_id'] != 0 && is_numeric($vars['ibc_team_id']) == false) {
            $errors['ibc_team_id'] = 'IBC team ID required number';
        }
       	
        if(!$errors){        
            $sql = " SET team_name=" . db_input($vars['team_name']) .
                 ",sport_type=" . db_input($vars['sport_type']) .
				 ",ibc_team_id=" . db_input($vars['ibc_team_id']).
                 ",updated=NOW()";
                                   
            if($id) {
                if(isset($vars['delete_field']) == true && $vars['delete_field'] == 1) {
                    $sql = 'UPDATE ' . TEAM_TABLE . ' SET status=0,updated=NOW() WHERE team_id=' . db_input($id);                     
                } else {
                    $sql = 'UPDATE ' . TEAM_TABLE . ' ' . $sql . ' WHERE team_id=' . db_input($id);
                }                
                if(!db_query($sql) || !db_affected_rows()) {
                   $errors['err'] = 'Unable to update ' . Format::input($vars['team_name']) . ' team. Error occured';
                }
            }else{				
                $sql = "INSERT INTO " . TEAM_TABLE . " " . $sql;
                if(db_query($sql) && ($teamId = db_insert_id())) {
                    return $teamId;
                }
                $errors['err'] = 'Unable to create team. Internal error';
            }
        }
        return $errors ? false : true;
    }
}
?>
