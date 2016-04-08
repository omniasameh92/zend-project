<?php

class Application_Form_Resetpass extends Zend_Form
{

    public function init()
    {

    	$this->setMethod('post');
          $email=$this->createElement('text', 'email')
                 ->setLabel('Enter your email:')
                 ->setAttrib('placeholder','Enter your email')
                 ->addFilters(array('StringTrim', 'StripTags'))
                 ->addValidator(new Zend_Validate_EmailAddress  )
                 ->setValue(isset($_POST['email']) ? $_POST['email'] : '')
                 ;   

         $email->addValidator(new Zend_Validate_Db_RecordExists(array(  

                        'table'=>'users',
                        'field'=>'email'
                        
                        ) ));

         $this->addElement($email);  
                   
            $passvalid= new Zend_Validate_Regex(array('pattern' => '/^[a-zA-Z0-9]{8,15}$/'));

            $passvalid->setMessage('Please, enter only numbers and characters it should be from 8-15 characters');

           $this->addElement('password', 'password',
           
            array(
                'label' => 'Password',
                'placeholder'=>'Enter password',
                'required' => true,
                'value'=>isset($_POST['password']) ? $_POST['password']:'',
                'filters'=>array('StringTrim','StripTags'),
                'validators'=>array($passvalid)
              )

            );



           $this->addElement('password', 'password1',
            array(
                'label' => 'Confirm password',
                'placeholder'=>'Confirm password',
                'required' => true,
                'value'=>isset($_POST['password1']) ? $_POST['password1']:'',
                'filters'=>array('StringTrim','StripTags'),
                'validators'=>array($passvalid,array('identical', true, array('token' =>

                     'password', 'messages' =>
                     array(Zend_Validate_Identical::NOT_SAME =>

                     'Passwords do not match')))))                   
            );                      
           
           $this->addElement('submit', 'submit', array(
               'ignore' => true, //???????
               'label' => 'Submit',
                ));
        /* Form Elements & Other Definitions Here ... */
    }


}

