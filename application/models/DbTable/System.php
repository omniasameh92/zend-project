<?php

class Application_Model_DbTable_System extends Zend_Db_Table_Abstract
{

    protected $_name = 'sys_state';


 public function changeState($c){

        $row=array('state'=>$c);
        return $this->update($row);

   }

  public function getState(){
      
      $select  = $this->select();
        
      return $this->fetchAll($select)->toArray();
        
 }


}

