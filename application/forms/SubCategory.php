<?php

class Application_Form_SubCategory extends Zend_Form
{

    public function init()
    {
           

       
         $this->setMethod('post');  
         $namevalid= new Zend_Validate_Regex(array('pattern' => '/^(\w+\s?)*\s*$/'));
         
         $namevalid->setMessage('Please, enter only numbers and characters it should be from 10-50 characters');

         $length = new Zend_Validate_StringLength();
         $length->setMessage('it should be from 10 to 50 characters spaces allowed');
         $length->setMin(10);
         $length->setMax(50);

          
         $this->addElement('text','sub_category',array('value'=>isset($_POST['sub_category']) ? $_POST['sub_category'] :'','label'=>'Forum','required'=>true,'filters'=>array('StringTrim'),'validators'=>array($namevalid,$length)));

         $textvalid= new Zend_Validate_Regex(array('pattern' => '/^([a-zA-Z0-9 ]{1,255})$/'));
         $textvalid->setMessage('Please, enter only numbers and characters spaces allowed maximum 255 character');
           
         $length1 = new Zend_Validate_StringLength();
         $length1->setMessage('it should be from 1 to 255 characters spaces allowed');
         $length1->setMin(1);
         $length1->setMax(255); 

         $this->addElement('textarea','description',array('cols'=>20,'rows'=>10,'value'=>isset($_POST['description']) ? $_POST['description'] :'','label'=>'description ','required'=>true,'filters'=>array('StringTrim'),'validators'=>array($textvalid,$length1)));
         $this->addElement('submit', 'submit', array(
               'ignore' => true, //???????,
               'label' => 'Add Forum',

         ));


       
    }


}

