<?php

class Application_Form_Getsub extends Zend_Form
{


  public function getCategories(){

             $cat_model = new Application_Model_DbTable_Category();
             $cat1=$cat_model->listCategories();  
             $cat=$cat_model->fetchAll($cat1)->toArray();
             $arr=array();
            foreach ($cat as  $c) {
              $key=$c['id'];
              $arr[$key]=$c['category'];  
           
           } 
             //      var_dump($arr);
                return $arr;
          }


      
  public function getSubs($id){

             $cat_model = new Application_Model_DbTable_Subcategory();
             $cat1=$cat_model->listSubCategory($id); 
              $cat=$cat_model->fetchAll($cat1)->toArray(); 
              $arr=array();
            foreach ($cat as  $c) {
              $key=$c['id'];
              $arr[$key]=$c['sub_category'];  
           
           }
                return $arr;
           
#            var_dump($arr);

          }


    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
           $this->setMethod('post');  
           $arr=$this->getCategories();
    
           $this->addElement('select', 'category', array(
           'label'=>'category ',
           'multiOptions'=>$arr
             
             ,'value'=>isset($_POST['category']) ? $_POST['category'] :'1'

           ));

          $temp=array_keys($arr);
          
          $arr1=$this->getSubs($temp[0]);

          $this->addElement('select', 'forum', array(
           'label'=>'Forums ',
           'multiOptions'=>$arr1
             
            ,'value'=>isset($_POST['forum']) ? $_POST['forum'] :'1'

           ));


            $this->addElement('submit', 'submit', array(
               'ignore' => true, //???????
               'label' => 'Choose Forum',

                ));


    }


}

