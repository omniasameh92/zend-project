<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {

        $this->setMethod('post');
          $email=$this->createElement('text', 'email')
                 ->setLabel('email')
                 ->setRequired(true)
                 ->addFilters(array('StringTrim', 'StripTags'))
                 ->addValidator(new Zend_Validate_EmailAddress  )
                 ->setValue(isset($_POST['email']) ? $_POST['email'] : '')
                 ;   
          $this->addElement($email);  
          $this->addElement('password', 'password',
            array(
                'label' => 'Password',
                'required' => true,
                 'value'=>isset($_POST['password']) ? $_POST['password']:''
                    ));
       

  

                 
           $this->addElement('checkbox','remember',array('value'=>'1'));
                        
           $this->addElement('submit', 'submit', array(
               'ignore' => true, //???????
               'label' => 'login',
                ));

           

    }


}

