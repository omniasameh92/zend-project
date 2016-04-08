<?php

class Application_Model_DbTable_Comment extends Zend_Db_Table_Abstract
{

    protected $_name = 'replies';


   function getComments($id){
       



     
    $select = $this->select()
                   ->from($this)
                   ->setIntegrityCheck(false)
                   ->join(array('u'=>'users'), 'replies.user_id =u.user_id')
                   ->columns(array('u.user_id', 'u.name','u.banned'))
                   ->order('replies.created_at')
                   ->where("replies.post_id = ?", $id);     

    return $this->fetchAll($select)->toArray();    
                  
    
  }


   
    function addComment($data,$user_id){
        $row = $this->createRow();
        $row->title   = $data['title'];
        $row->input = $data['input'];
        $row->post_id = $data['id'];
        $row->user_id = $user_id;
        
        return $row->save();
    
    }

    function deleteComment($id){
        return $this->delete("id=$id");
    }

    function editComment($data){
        
        $row=array('input'=>$data['input'],'title'=>$data['title']);
        return $this->update($row,"id=".$data['id']);
      
           
    }


    function getCommentById($id){


        
       $select  = $this->select()->where('id='.$id);
        
       return $this->fetchAll($select)->toArray();


    }

     /*     
        $db = Zend_Db_Table::getDefaultAdapter(); 

        $select = $db->select()

         ->from(array('r' => 'replies'), array('r.title','r.post_id', 'r.content', 'r.created_at','r.user_id','r.id','u.user_id','u.name'))
         ->joinInner(array('u' => 'users'), 'r.user_id = u.user_id')
         ->where('r.post_id = ?', $id);

           $query= $select->query();
           
                    $this->
           
                   var_dump($res);
    }
    */

}

