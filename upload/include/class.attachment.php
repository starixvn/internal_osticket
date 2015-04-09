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
class Attachment {
    var $id;
    var $name;
    var $ticket_id;
    var $updated;
    var $row;
  
    function Attachment($id=0){
        $this->id=0;
        if($id && ($info=$this->getInfoById($id))){  
            $this->row=$info;
            $this->id=$info['attachment_id'];
            $this->name=$info['attachment_name'];
            $this->ticket_id=$info['ticket_id'];
            $this->updated=$info['updated'];           
        }
    }

    function getId(){
        return $this->id;
    }
    
    function getName(){
        return $this->deptname;
    }
    
    function getTicketId(){
        return $this->ticket_id;
    }
    
    function getUpdated(){
        return $this->updated;
    }    
    
    function getInfo() {
        return $this->row;
    }

    function update($vars,&$errors) {
        if($this->save($this->getId(),$vars,$errors)){
            return true;
        }
        return false;
    }

    function getInfoById($id) {
        $sql='SELECT * FROM '.ATTACHMENT_TABLE.' WHERE attachment_id='.db_input($id);
        if(($res=db_query($sql)) && db_num_rows($res))
        return db_fetch_array($res);

        return null;
    }

    
    function getIdByName($name) {
        $id=0;
        $sql ='SELECT attchment_id FROM '.ATTACHMENT_TABLE.' WHERE attachment_name='.db_input($name);
        if(($res=db_query($sql)) && db_num_rows($res))
            list($id)=db_fetch_row($res);

        return $id;
    }

    function getNameById($id) {
        $sql ='SELECT attachment_name FROM '.ATTACHMENT_TABLE.' WHERE attchment_id='.db_input($id);
        if(($res=db_query($sql)) && db_num_rows($res))
            list($name)=db_fetch_row($res);
        return $name;
    }

    function create($vars,&$errors) {
        return Attachment::save(0,$vars,$errors);
    }

    function delete($id) {
        global $cfg;        
        $sql='DELETE FROM '.ATTACHMENT_TABLE.' WHERE attchment_id='.db_input($id);
        if(db_query($sql) && ($num=db_affected_rows())){           
            return $num;
        }        
        return 0;
        
    }

    function save($id,$vars,&$errors) {
             
        if($id && $id!=$vars['attachment_id'])
            $errors['err']='Missing or invalid attachment ID';
        
        if(!$vars['attachment_name']) {
            $errors['attachment_name']='Attachment name required';
        }else{
            $sql='SELECT attachment_id FROM '.ATTACHMENT_TABLE.' WHERE attachment_name='.db_input($vars['attachment_name']);
            if($id)
                $sql.=' AND attachment_id!='.db_input($id);
                
            if(db_num_rows(db_query($sql)))
                $errors['attachment_name']='Attachment already exist';
        }
       
        if(!$errors){        
            $sql=' SET attachment_name='.db_input($vars['attachment_name']).
                 ',ticket_id='.db_input($vars['ticket_id']).
                 ',updated=NOW(), type='.db_input($vars['type']).', response_id='.db_input($vars['response_id']).', note_id='.db_input($vars['note_id']);          
            if($id) {                
                $sql='UPDATE '.ATTACHMENT_TABLE.' '.$sql.' WHERE attachment_id='.db_input($id);
                if(!db_query($sql) || !db_affected_rows())
                    $errors['err']='Unable to update '.Format::input($vars['attachment_name']).' attachment. Error occured';
            }else{
                $sql='INSERT INTO '.ATTACHMENT_TABLE.' '.$sql;
                if(db_query($sql) && ($attachment_id=db_insert_id()))
                    return $attachment_id;

                $errors['err']='Unable to create attachment. Internal error';
            }
        }

        return $errors?false:true;
    }
    
    // get attachment list by ticket_id
    function getAttachmentByTicketId($intTicketId)
    {
        $sql='SELECT attachment_name FROM '.ATTACHMENT_TABLE.' WHERE ticket_id='.db_input($intTicketId).' AND type=0';
        $arrAttachment = array();       
        if(($res=db_query($sql)) && db_num_rows($res)) {
            while ($row = db_fetch_array($res)) {
                $arrAttachment[] = $row['attachment_name'];
            }
            return $arrAttachment;
        }
        return null;
    }
    
    // delete attachment by attachment_name
    function deleteAttachmentString($strAttachment_name) {              
        $sql='DELETE FROM '.ATTACHMENT_TABLE.' WHERE attachment_name IN (' . $strAttachment_name . ') AND type=0';
        if(db_query($sql) && ($num=db_affected_rows())){           
            return $num;
        }        
        return 0;
        
    }
}
?>
