<?php
 #use Zend\Form\Form;


class Application_Form_Register extends Zend_Form
{


	public function getCountries(){

             $country_model = new Application_Model_DbTable_Countries();
             $countries=$country_model->listCountries();  
              $arr=array();
            foreach ($countries as  $con) {
              $key=$con['id'];
              $arr[$key]=$con['country'];	
           
           }
                return $arr;
          }

    public function init()
    {

     
        $this->setMethod('post');   
        $this->setEnctype('multipart/form-data');
       
       $namevalid= new Zend_Validate_Regex(array('pattern' => '/^(\w+\s?)*\s*$/'));



       $namevalid->setMessage('Please, enter only numbers and characters it should be from 10 to 30 characters spaces allowed');
       
       $passvalid= new Zend_Validate_Regex(array('pattern' => '/^[a-zA-Z0-9]{8,15}$/'));

       $passvalid->setMessage('Please, enter only numbers and characters it should be from 8-15 characters');

        $length = new Zend_Validate_StringLength();
        $length->setMessage('it should be from 10 to 30 characters spaces allowed');
        $length->setMin(10);
        $length->setMax(30);


        $this->addElement('text','name',array('value'=>isset($_POST['name']) ? $_POST['name'] :'','label'=>'username ','required'=>true,'placeholder'=>'Enter username','filters'=>array('StringTrim','StripTags'),'validators'=>array(
          $namevalid,$length)));
       

 

        $email=$this->createElement('text', 'email')
                 ->setLabel('email')
                 ->setAttrib('placeholder','enter email address')
                 ->addFilters(array('StringTrim', 'StripTags'))
                 ->addValidator(new Zend_Validate_EmailAddress  )
                  ->setRequired(true)
                 ->setValue(isset($_POST['email']) ? $_POST['email'] : '');   
                

         $email->addValidator(new Zend_Validate_Db_NoRecordExists(array(  

                        'table'=>'users',
                        'field'=>'email'
                        
                        ) ));
                        
        $this->addElement($email);   

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


        

       # $matchvalid= new Zend_Validate_Identical(array('pattern' => '/^([a-zA-Z0-9]+|([a-zA-Z0-9]+\ [a-zA-Z0-9]+)+)$/'));



      # $matchvalid->setMessage('passwords don\'t match');

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
        

            #   $this->getElement('password1')->addErrorMessage('');
           

         $this->addElement('radio', 'gender', array(
           'label'=>'gender ',
           'multiOptions'=>array(
           'male' => 'male',
           'female' =>'female',

             ),
            'value'=>"male"
           ));

           

            $arr=$this->getCountries();
    
           $this->addElement('select', 'country', array(
           'label'=>'country ',
            'multiOptions'=>$arr
             
             ,'value'=>isset($_POST['country']) ? $_POST['country'] :'1'

           ));
            
      
       $regxvalid= new Zend_Validate_Regex(array('pattern' => '/^[0-9]{11}$/'));
       $regxvalid->setMessage('Please, enter only numbers it should be 11 numbers');
      
              $phone=$this->createElement('text', 'phone_number')
                  ->setLabel('Phone number ')
                  ->setAttrib('placeholder','enter phone number')
                  ->addFilters(array('StringTrim', 'StripTags'))
                  ->setValue(isset($_POST['phone_number']) ? $_POST['phone_number'] : '')
                  ->setRequired(true)
                  ->addValidator('Digits')
                  ->addValidator($regxvalid);
              
                   $this->addElement($phone);   

                    $image = new Zend_Form_Element_File('photo');
					$image->setLabel('Upload an image')
					      ->setRequired(true)
					      ->setDestination(APPLICATION_UPLOADS_DIR)
					      ->setDescription('Click Choose file  and click on the image file you would like to upload');
					$image->addValidator('Count', false, 1);                // ensure only 1 file
					          
					$imgvalid = new Zend_Validate_File_Extension('jpg,jpeg,png,gif');
					$imgvalid->setMessage('Choose an image please');
					$image->addValidator($imgvalid);
   				    $this->addElement($image);  



               $this->addElement('text','signature',array('value'=>isset($_POST['signature']) ? $_POST['signature'] :'','label'=>'Add signature ','placeholder'=>'Enter signature','required'=>true,'filters'=>array('StringTrim','StripTags'),'validators'=>array($namevalid, $length)));
                  


        $this->addElement('submit', 'submit', array(
               'ignore' => true, //???????
               'label' => 'register',

                ));
       


        }

         
        
       
    }




