<?php

class Application_Form_Ban extends Zend_Form
{

    public function init()
    {
                

        $email=$this->createElement('text', 'email')
                 ->setLabel('email ')
                 ->setDescription('Enter user\'s email to add in the ban list')
                 ->addFilters(array('StringTrim', 'StripTags'))
                 ->addValidator(new Zend_Validate_EmailAddress  )
                  ->setRequired(true)
                 ->setValue(isset($_POST['email']) ? $_POST['email'] : '');   
                
         $email->addValidator(new Zend_Validate_Db_RecordExists(array(  

                        'table'=>'users',
                        'field'=>'email'
                        
                        ) ));
                        
        $this->addElement($email); 
    
           $this->addElement('submit', 'submit', array(
               'ignore' => true, //???????
               'label' => 'Add to ban list',

                ));

    }


}

