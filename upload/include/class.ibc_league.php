<?php
/*********************************************************************
    class.league.php
    
    league class

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2010 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
    $Id: $
**********************************************************************/
class IBC_League {
    var $id;
    var $name;
    var $sport_type; 
    var $row;
    
    function IBC_League($id = 0){
        $this->id = 0;
        if($id && ($info = $this->getInfoById($id))){ 
            $this->row = $info;
            $this->id = $info['league_id'];
            $this->name = $info['league_name'];
            $this->sport_type = $info['port_type'];             
        }
    }

    function getId(){
        return $this->id;
    }
    
    function getName(){
        return $this->name;
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
        $sql = 'SELECT * FROM ' . IBC_LEAGUE_TABLE . ' WHERE league_id=' . db_input($id);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            return db_fetch_array($res);
        }
        return null;
    }
    
    function getIdByName($name) {
        $id = 0;
        $sql = 'SELECT league_id FROM ' . IBC_LEAGUE_TABLE . ' WHERE league_name=' . db_input($name);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            list($id) = db_fetch_row($res);
        }
        return $id;
    }

    function getNameById($id) {
        $sql = 'SELECT league_name FROM ' . IBC_LEAGUE_TABLE . ' WHERE league_id=' . db_input($id);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            list($name) = db_fetch_row($res);
        }
        return $name;
    }

    function create($vars, &$errors) {
        return IBC_League::save(0, $vars, $errors);
    }
    
    function delete($id) {
        global $cfg;        
        $sql = 'DELETE FROM ' . IBC_LEAGUE_TABLE . ' WHERE league_id=' . db_input($id);
        if(db_query($sql) && ($num = db_affected_rows())){           
            return $num;
        }
        return 0;
    }

    function save($id, $vars, &$errors) {
        global $cfg;
                
        if($id && $id != $vars['league_id'])
            $errors['err'] = 'Missing or invalid league ID';
        
        if(!$vars['league_name']) {
            $errors['league_name'] = 'League name required';
        }elseif(strlen($vars['league_name']) < 4) {
            $errors['league_name'] = 'League name must be at least 4 chars.';
        }else{
            $sql = 'SELECT league_id FROM ' . IBC_LEAGUE_TABLE . ' WHERE league_name=' . db_input($vars['league_name']) . 'AND sport_type=' . db_input($vars['sport_type']);
            if($id) {
                $sql .= ' AND league_id!=' . db_input($id);
            }
            if(db_num_rows(db_query($sql))) {
                $errors['league_name'] = 'League already exist';
            }
        }
               
        if(!$errors){        
            $sql = " SET league_id=" . db_input($vars['league_id']) .
                 ",league_name=" . db_input($vars['league_name']) .
                 ",sport_type=" . db_input($vars['sport_type']);         
            if($id) {
                $sql = 'UPDATE ' . IBC_LEAGUE_TABLE . ' ' . $sql . ' WHERE league_id=' . db_input($id);
                if(!db_query($sql) || !db_affected_rows())
                    $errors['err'] = 'Unable to update ' . Format::input($vars['league_name']) . ' league. Error occured';
            }else{
                $sql = 'INSERT INTO ' . IBC_LEAGUE_TABLE . ' ' . $sql;
                if(db_query($sql) && ($leagueID = db_insert_id())) {
                    return $leagueID;
                }
                $errors['err'] = 'Unable to create league. Internal error';
            }
        }

        return $errors ? false : true;
    }
}
?>
