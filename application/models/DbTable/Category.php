<?php

class Application_Model_DbTable_Category extends Zend_Db_Table_Abstract
{

    protected $_name = 'categories';




  public function listCategories(){
          
        $select  = $this->select()->order('id');
        
         #return $this->fetchAll($select)->toArray();
            
         return $select;   


  }

   public function addCategory($data){

        $row = $this->createRow();
        $row->category = $data['category'];
        $row->description = $data['description'];
        return $row->save();
      
  }


   public function editCategory($data){
          
        $row=array('category'=>$data['category'],'description'=>$data['description']);
        return $this->update($row,"id=".$data['id']);

  }


 public function deleteCategory($id){
     

     return $this->delete("id=$id");


  }


 public function getCategoryById($id){

        
        $select  = $this->select()->where('id=?',$id);
        
         return $this->fetchAll($select)->toArray();

  }



}

