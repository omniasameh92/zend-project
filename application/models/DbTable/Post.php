<?php

class Application_Model_DbTable_Post extends Zend_Db_Table_Abstract
{

    protected $_name = 'posts';



    function listPosts($id){
        $select  = $this->select()->where("sub_id=$id")->order('id');
        
       return $select;
    }

    function deletePost($id){
        return $this->delete("id=$id");
    }

      
    function addPost($data,$user_id){   
        $row = $this->createRow();
        $row->input = $data['input'];
        $row->title= $data['title'];
        
        $row->user_id=$user_id;
        
        $row->sub_id=$data['id'];
        return $row->save();
      }

      function editPost($data){
  
        $row=array('input'=>$data['input'],'title'=>$data['title']);
         //$this->view->data=$row;
        return $this->update($row,"id=".$data['id']);
       // return $this->update($row)->where("us_id=".$data['id']);
           
      }

      function getPostById($id){
        
       $select  = $this->select()->where('id='.$id);
        
       return $this->fetchAll($select)->toArray();
            

      }

      function stickpost($id){

        $row=array('sticky'=>'t');
        return $this->update($row,"id=".$id);

      }

      function unstickpost($id){

        $row=array('sticky'=>'f');
        return $this->update($row,"id=".$id);


      }

      function locComment($id){

         $row=array('comment_allow'=>'f');
        return $this->update($row,"id=".$id);

      }

        
        function removeloc($id){
           
         $row=array('comment_allow'=>'t');
        return $this->update($row,"id=".$id);

      }   

}

