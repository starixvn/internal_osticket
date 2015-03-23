<?php
/*********************************************************************
    class.actor.php
    
    Actor class

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2010 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
    $Id: $
**********************************************************************/
class Actor {
    var $id;
    var $name;
    var $contact_info;
    var $type;
    var $status;
    var $row;    
  
    function Actor($id = 0){
        $this->id = 0;
        if($id && ($info = $this->getInfoById($id))){
            $this->row = $info;
            $this->id = $info['actor_id'];
            $this->name = $info['actor_name'];
            $this->contact_info = $info['contact_info'];
            $this->type = $info['type'];
            $this->status = $info['status'];           
        }
    }

    function getId(){
        return $this->id;
    }
    
    function getName(){
        return $this->name;
    }
    
    function getContactInfo(){
        return $this->contact_info;
    }
    
    function getType(){
        return $this->type;
    }
    
    function getStatus(){
        return $this->status;
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
        $sql = 'SELECT * FROM ' . ACTOR_TABLE . ' WHERE actor_id=' . db_input($id);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            return db_fetch_array($res);
        }
        return null;
    }
    
    function getIdByName($name) {
        $id = 0;
        $sql = 'SELECT actor_id FROM ' . ACTOR_TABLE . ' WHERE actor_name=' . db_input($name);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            list($id) = db_fetch_row($res);
        }
        return $id;
    }

    function getNameById($id) {
        $sql = 'SELECT actor_name FROM ' . ACTOR_TABLE . ' WHERE actor_id=' . db_input($id);
        if(($res = db_query($sql)) && db_num_rows($res)) {
            list($name) = db_fetch_row($res);
        }
        return $name;
    }

    function create($vars, &$errors) {
        return Actor::save(0, $vars, $errors);
    }

    function delete($id) {
        global $cfg;         
        $sql = 'UPDATE ' . ACTOR_TABLE . ' SET status=0 WHERE actor_id=' . db_input($id);
         if(db_query($sql) && ($num = db_affected_rows())){           
            return $num;
        }  
        return 0;
    }

    function save($id, $vars, &$errors) {
        global $cfg;                
        if($id && $id != $_POST['actor_id']) {
            $errors['err'] = 'Missing or invalid actor ID';
        }
        
        if(!$_POST['type']) {
            $errors['type'] = 'Type of actor required';
        }
        
        if(!$_POST['actor_name']) {
            $errors['actor_name'] = 'Actor name required';
        }elseif(strlen($_POST['actor_name']) < 4) {
            $errors['actor_name'] = 'Actor name must be at least 4 chars.';
        }else{
            $sql = 'SELECT actor_id FROM ' . ACTOR_TABLE . ' WHERE status=1 AND actor_name=' . db_input($_POST['actor_name']);
            if($id)
                $sql .= ' AND actor_id!=' . db_input($id);
            if(db_num_rows(db_query($sql)))
                $errors['actor_name'] = 'Actor already exist';
        }
       
        if(!$errors){        
            $sql = ' SET actor_name=' . db_input($_POST['actor_name']) .
                 ',contact_info=' . db_input($_POST['contact_info']) .
                 ',type=' . db_input($_POST['type']) .
                 ',updated=NOW()';               
            if($id) {
                // check delete or update
                 if(isset($_POST['delete_field']) == true && $_POST['delete_field'] == 1) {
                     $sql = 'UPDATE ' . ACTOR_TABLE . ' SET status=0,updated=NOW() WHERE actor_id=' . db_input($id);                     
                 } else {
                     $sql = 'UPDATE ' . ACTOR_TABLE . ' ' . $sql . ' WHERE actor_id=' . db_input($id);
                 }                
                if(!db_query($sql) || !db_affected_rows())
                    $errors['err'] = 'Unable to update ' . Format::input($_POST['actor_name']) . ' actor. Error occured';
            }else{
                $sql = 'INSERT INTO ' . ACTOR_TABLE . ' ' . $sql;
                if(db_query($sql) && ($leagueID = db_insert_id())) {
                    return $leagueID;
                }
                $errors['err'] = 'Unable to create actor. Internal error';
            }
        }
        return $errors ? false : true;
    }
}
?>
