<?php

class Application_Model_DbTable_Countries extends Zend_Db_Table_Abstract
{

    protected $_name = 'countries';



    function listCountries(){

         $select  = $this->select()->order('id');
        
         return $this->fetchAll($select)->toArray();

    }



    
    function getCountryById($id){


        
       $select  = $this->select()->where('id='.$id);
        
       return $this->fetchAll($select)->toArray();


    }




}

