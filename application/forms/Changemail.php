<?php

class Application_Form_Changemail extends Zend_Form
{

    public function init()
    {


         $this->setMethod('post');   
          /*
                  $email=$this->createElement('text', 'email')
                 ->setLabel('Current email')
                 ->addFilters(array('StringTrim', 'StripTags'))
                 ->addValidator(new Zend_Validate_EmailAddress  )
                  ->setRequired(true)
                 ->setValue(isset($_POST['email']) ? $_POST['email'] : '');   
                

         $email->addValidator(new Zend_Validate_Db_RecordExists(array(  

                        'table'=>'users',
                        'field'=>'email'
                        
                        ) ));

                        
        $this->addElement($email);   
*/
                $email1=$this->createElement('text', 'email1')
                 ->setLabel('New email ')
                 ->addFilters(array('StringTrim', 'StripTags'))
                 ->addValidator(new Zend_Validate_EmailAddress  )
                  ->setRequired(true)
                  ->setAttrib('placeholder','Enter new email')
                 ->setValue(isset($_POST['email1']) ? $_POST['email1'] : '');   
                  

         $email1->addValidator(new Zend_Validate_Db_NoRecordExists(array(  

                        'table'=>'users',
                        'field'=>'email'
                    
                        ) ));
                        
        $this->addElement($email1);  


            $this->addElement('text', 'email2',
            array(
                'label' => 'Confirm email',
                'placeholder'=>'Confirm email',
                'required' => true,
                'value'=>isset($_POST['email2']) ? $_POST['email2']:'',
                'filters'=>array('StringTrim','StripTags'),
                'validators'=>array(new Zend_Validate_EmailAddress,new Zend_Validate_Db_NoRecordExists(array(  

                        'table'=>'users',
                        'field'=>'email'
                        
                        ) ),array('identical', true, array('token' =>

                     'email1', 'messages' =>
                     array(Zend_Validate_Identical::NOT_SAME =>

                     'emails do not match')))))                   
            );
               
       
        $this->addElement('submit', 'submit', array(
               'ignore' => true, //???????
               'label' => 'Change email',

                ));

        /* Form Elements & Other Definitions Here ... */
    }


}

