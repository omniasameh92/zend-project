<?php

class Application_Form_Addcomment extends Zend_Form
{

    public function init()
    {

         $this->setMethod('post');  
         $namevalid= new Zend_Validate_Regex(array('pattern' => '/^(\w+\s?)*\s*$/'));
         
         $namevalid->setMessage('Please, enter only numbers and characters it should be from 10-50 characters');
          
        $length = new Zend_Validate_StringLength();
        $length->setMessage('it should be from 10 to 50 characters spaces allowed');
        $length->setMin(10);
        $length->setMax(30);


         $this->addElement('text','title',array('value'=>isset($_POST['title']) ? $_POST['title'] :'','label'=>'title ','required'=>true,'filters'=>array('StringTrim'),'validators'=>array($namevalid,$length)));

       

         $this->addElement('textarea','input',array('cols'=>20,'rows'=>10,'value'=>isset($_POST['input']) ? $_POST['input'] :'','label'=>'content ','id'=>'input','required'=>true,'filters'=>array('StringTrim')));
         $this->addElement('submit', 'submit', array(
               'ignore' => true, //???????,
               'label' => 'Add reply',

         ));
        /* Form Elements & Other Definitions Here ... */
    }


}

