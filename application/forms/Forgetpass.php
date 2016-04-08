<?php

class Application_Form_Forgetpass extends Zend_Form
{

    public function init()
    {       

          $this->setMethod('post');
          $email=$this->createElement('text', 'email')
                 ->setLabel('Enter your email:')
                 ->setRequired(true)
                 ->setDescription('an email will be sent to reset your password,thanks')
                 ->addFilters(array('StringTrim', 'StripTags'))
                 ->addValidator(new Zend_Validate_EmailAddress  )
                 ->setValue(isset($_POST['email']) ? $_POST['email'] : '')
                 ;   

         $email->addValidator(new Zend_Validate_Db_RecordExists(array(  

                        'table'=>'users',
                        'field'=>'email'
                        
                        ) ));
                             
          $this->addElement($email);  
                                         
           $this->addElement('submit', 'submit', array(
               'ignore' => true, //???????
               'label' => 'Submit',
                ));

        /* Form Elements & Other Definitions Here ... */
    }


}

