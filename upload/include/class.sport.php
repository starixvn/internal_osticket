<?php
/*********************************************************************
    class.sport.php
    
    sport class

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2010 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
    $Id: $
**********************************************************************/
class Sport {
    var $sport_type;
    var $name; // sport_name
    var $status;
    var $updated;
    
    var $row;
    function Sport($id = 0){
        $this->sport_type = 0;
        if($id && ($info = $this->getInfoBySportType($id))){ 
            $this->row = $info;
            $this->sport_type = $info['sport_type'];
            $this->name = $info['sport_name'];
            $this->status = $info['status']; 
            $this->updated = $info['updated']; 
        }
    }

    function getSportType(){
        return $this->sport_type;
    }
    
    function getName(){
        return $this->name;
    }
    
    function getStatus(){
        return $this->deptname;
    }
    
    function getUpdated(){
        return $this->updated;
    } 
    
    function getInfo() {
        return $this->row;
    }

    function update($vars,&$errors) {
        if($this->save($this->getSportType(),$vars,$errors)){
            return true;
        }
        return false;
    }

    function getInfoBySportType($sport_type) {
        $sql = 'SELECT * FROM ' . SPORT_TABLE . ' WHERE sport_type = ' . db_input($sport_type);        
        if(($res = db_query($sql)) && db_num_rows($res)) {
            return db_fetch_array($res);
        }
        return null;
    }
    
    function getSportTypeByName($name) {
        $sport_type = 0;
        $sql = 'SELECT sport_type FROM ' . SPORT_TABLE . ' WHERE sport_name=' . db_input($name);
        if(($res = db_query($sql)) && db_num_rows($res))
            list($sport_type) = db_fetch_row($res);

        return $sport_type;
    }

    function getNameBySportType($sport_type) {
        $sql = 'SELECT sport_name FROM ' . SPORT_TABLE . ' WHERE sport_type=' . db_input($sport_type);
        if(($res = db_query($sql)) && db_num_rows($res))
            list($name) = db_fetch_row($res);
        return $name;
    }

    function create($vars,&$errors) {
        return Sport::save(0, $vars, $errors);
    }
    
    function delete($sport_type) {
        global $cfg;        
        $sql = 'UPDATE ' . SPORT_TABLE . ' SET status=0 WHERE sport_type=' . db_input($sport_type);
        if(db_query($sql) && ($num = db_affected_rows())){           
            return $num;
        }
        return 0;        
    }

    function save($id, $vars, &$errors) {
        global $cfg;
                
        if($id && $id != $_POST['sport_type']) {
            $errors['err'] = 'Missing or invalid sport type';
        }
        
        if(!$_POST['sport_name']) {
            $errors['sport_name'] = 'Sport name required';
        }elseif(strlen($_POST['sport_name']) < 4) {
            $errors['sport_name'] = 'Sport name must be at least 4 chars.';
        }else{
            $sql = 'SELECT sport_type FROM ' . SPORT_TABLE . ' WHERE status=1 AND sport_name=' . db_input($_POST['sport_name']);
            if($id) {
                $sql .= ' AND sport_type!=' . db_input($id);
            }
            if(db_num_rows(db_query($sql))) {
                $errors['sport_name'] = 'Sport already exist';
            }
        }
      
        if(!$errors){        
            $sql = " SET sport_name=" . db_input($_POST['sport_name']) .
                 ",updated=NOW()";         
            if($id) {
                 if(isset($_POST['delete_field']) == true && $_POST['delete_field'] == 1) {
                     $sql = 'UPDATE ' . SPORT_TABLE . ' SET status=0,updated=NOW() WHERE sport_type=' . db_input($id);                     
                 } else {
                     $sql = 'UPDATE ' . SPORT_TABLE . ' ' . $sql . ' WHERE sport_type=' . db_input($id);
                 }                
                if(!db_query($sql) || !db_affected_rows()) {
                    $errors['err'] = 'Unable to update ' . Format::input($_POST['sport_name']) . ' sport. Error occured';
                }
            }else{
                $sql = 'INSERT INTO ' . SPORT_TABLE . ' ' . $sql;
                if(db_query($sql) && ($sport_type = db_insert_id()))
                    return $sport_type;
                $errors['err'] = 'Unable to create sport. Internal error';
            }
        }
        return $errors ? false : true;
    }
}
?>
