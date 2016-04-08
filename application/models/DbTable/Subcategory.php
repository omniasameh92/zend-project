<?php

class Application_Model_DbTable_Subcategory extends Zend_Db_Table_Abstract
{

    protected $_name = 'sub_categories';



    public function listSubCategory($id){

        
         $select = $this->select()->where('cat_id=?',$id);
        
         return $select;
         #$this->fetchAll($select)->toArray();

   
   }



   public function addSub($data,$id){

        $row = $this->createRow();
        $row->sub_category = $data['sub_category'];
        $row->description = $data['description'];
        $row->cat_id=$data['id'];
        return $row->save();
      
  }

    public function editSub($data){
          
        $row=array('sub_category'=>$data['sub_category'],'description'=>$data['description']);
        return $this->update($row,"id=".$data['id']);

       
       }


   public function deleteSub($id){
     

     return $this->delete("id=$id");


  }

   public function getSubById($id){

        
        $select  = $this->select()->where('id=?',$id);
        
         return $this->fetchAll($select)->toArray();

  }


     function locPost($id){

         $row=array('post_allow'=>'f');
        return $this->update($row,"id=".$id);

      }

        
        function allowPost($id){
           
         $row=array('post_allow'=>'t');
        return $this->update($row,"id=".$id);

      }   


}

