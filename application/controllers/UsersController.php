<?php

class UsersController extends Zend_Controller_Action
{

    private $update_id = null;

    public $user_name="";

    public function init()
    {

         $authorization =Zend_Auth::getInstance();
       
        
        if($user=isset($_COOKIE['user_id'])||$user=$authorization->getIdentity()->user_id){
                
                $user_model = new Application_Model_DbTable_User();
              
               if(isset($_COOKIE['user_id'])){
                    
              

              $user=$user_model->getUserById($_COOKIE['user_id']); 



              if($user[0]['banned']=='t'){
                   
                   Zend_Auth::getInstance()->clearIdentity();
                   Zend_Session::destroy( true );       
                   
                  
                   setcookie("user_id","",time()-60*60*24,'/');
                   
                    
                   $this->redirect('/index/index/msg/ban');




                  }
                     
                  if($user[0]['admin']=='f'){
                         
                           $this->redirect('/index/index/msg/unauth');
                               

                    }
                 


                 }else{

                

                $user=$user_model->getUserByEmail($authorization->getIdentity()->email);


              if($user[0]['banned']=='t'){
                   
                   Zend_Auth::getInstance()->clearIdentity();
                
                   Zend_Session::destroy( true );       
                   
                  
                   setcookie("user_id","",time()-60*60*24,'/');
                   
                    
                   $this->redirect('/index/index/msg/ban');


                   }
                     
                    if($user[0]['admin']=='f'){
                         
                           $this->redirect('/index/index/msg/unauth');
                               

                    }
                 



                 }
                 

                  
               # echo "<span class='text-primary'style='background-color:black;'>".$user[0]['name']."</span>";
               
               }else{


            $this->redirect('/index');
               
               }
  
  }

    public function indexAction()
    {
           
             $this->redirect("users/list/");
            
            
    }

    public function addAction()
    {
        $reg_form=new Application_Form_Register();
        $this->view->reg_form=$reg_form;
        $p=$this->getRequest()->getParams();
           

              

        if($this->getRequest()->isPost()){

            if($reg_form->isvalid($_POST)){
            $user_model = new Application_Model_DbTable_User();
           
            $originalFilename = pathinfo($reg_form->photo->getFileName());
            #var_dump($originalFilename);
            
            $newFilename = $originalFilename['filename'] .date('h:i:s').'.' . $originalFilename['extension'];
            $reg_form->photo->addFilter('Rename', $newFilename);
            #var_dump($newFilename);
                 try {
            
                        $reg_form->photo->receive();
                        
                        $p["photo"]=$newFilename;
                         
                         $user_model->addUser($p);

                         $this->register_vertify($p);



                         $this->redirect('users/list/msg/add');
                  } catch (Exception $e) {
                          echo "can not upload this file!";
        
               }
                     
                   
             }
          
          }    
        
    }

    public function listAction()
    {

          $ban = $this->getRequest()->getParam('ban');
         
              $msg=$this->getRequest()->getParam('msg');
               $this->view->msg=$msg;
          $authorization =Zend_Auth::getInstance();

          $logged_user=isset($_COOKIE['user_id'])?$_COOKIE['user_id']:$authorization->getIdentity()->user_id;
         
          $this->view->logged_user=$logged_user;
         
          if($ban){
          $this->view->ban=$ban; 
          $this->readallAction($ban);
           }else{

            $this->view->ban='f'; 
            $this->readallAction('f');

           }

          
    /*
         
      $ban = $this->getRequest()->getParam('ban');
     
       if($ban){
        $user_model = new Application_Model_DbTable_User();
        $this->view->users = $user_model->listUsers($ban);   
        $this->view->ban=$ban; 
      
       }else{
         $user_model = new Application_Model_DbTable_User();
         $this->view->users = $user_model->listUsers('f'); 
         $this->view->ban='f'; 

        }
    */
    }

    public function deleteAction()
    {

          $this->_helper->viewRenderer->setNoRender();
          $id = $this->getRequest()->getParam('id');
          $ban = $this->getRequest()->getParam('ban');

          if($id && $ban && $id != 1){
             $user_model = new Application_Model_DbTable_User();
             $user_model->deleteUser($id);
             $this->redirect("users/list/ban/$ban");
        }else{

            $this->redirect("users/list/ban/$ban");
        }
    }

    public function updateAction()
    {

          $id=$this->getRequest()->getParam('id');
          $ban=$this->getRequest()->getParam('ban');
          $reg_form=new Application_Form_Register();
          
          $submit=$reg_form->getElement('submit');
          
          $pass=$reg_form->getElement('password');
           
            $pass1=$reg_form->getElement('password1');

         $pass1->setRequired(false);

         $pass->setLabel('Change password');
         
         $pass->setRequired(false);
         $reg_form->removeElement('email');
         
         $photo=$reg_form->getElement('photo');
         $submit->setLabel('edit user');
         $sig=$reg_form->getElement('signature');
         $sig->setLabel('Signature ');
         $photo->setLabel('Upload new image');
         $photo->setRequired(false);

           if($id&&$ban){
             
              $this->view->reg_form=$reg_form;  
              $user_model = new Application_Model_DbTable_User();
                  
                   $authorization =Zend_Auth::getInstance();
                   $user=isset($_COOKIE['user_id'])?$_COOKIE['user_id']:$authorization->getIdentity()->user_id; 
               if($id==1&&$user!=1){

                $this->redirect('/users/list');
               }
              $user=$user_model->getUserById($id);
          

              $this->view->photo=$user[0]['photo'];

              $this->view->email=$user[0]['email'];

              
              $this->view->id=$user[0]['user_id'];
              $this->update_id=$id;
              $reg_form->populate($user[0]);
               
            
          }else{
                  
                  $this->redirect("users/list/");
                 
          }

        
        if($this->getRequest()->isPost()){
             


          if($reg_form->isvalid($_POST)){
            $p=$this->getRequest()->getParams();
            $originalFilename = pathinfo($reg_form->photo->getFileName());
           
            if($originalFilename){

            $newFilename = $originalFilename['filename'] .date('h:i:s').'.' . $originalFilename['extension'];
            $reg_form->photo->addFilter('Rename', $newFilename);
            try {
            
                         $reg_form->photo->receive();
  
                         $p["photo"]=$newFilename;
                         $user_model->editUser($p);
                         $this->redirect("users/list/ban/$ban");
                  } catch (Exception $e) {
                          echo "can not upload this file!";
                          
               }

            
            }else{
            
            $user_model->editUser($p);
            $this->redirect("users/list/ban/$ban");
            
            }


            }

          }
            
           
        // action body
    }

    public function adminAction()
    {
              
          $this->_helper->viewRenderer->setNoRender();
          
          $id = $this->getRequest()->getParam('id');
          
          $admin = $this->getRequest()->getParam('a');
          
          if($id && $admin && $id != 1){
             $user_model = new Application_Model_DbTable_User();
             $user_model->adminUser($id,$admin);
             $this->redirect("users/list/");
          
          }else{

            $this->redirect("users/list/");
          
          }      
          
         
        // action body
    

    }

    public function banAction()
    {

          $this->_helper->viewRenderer->setNoRender();
          
          $id = $this->getRequest()->getParam('id');
          $post=$this->getRequest()->getParam('post');
          $thread=$this->getRequest()->getParam('thread');
          
          if($id){
               $admin=0;
              if($id!=1){

                 $user_model = new Application_Model_DbTable_User();
                 $user_model->banUser($id); 
                 $admin=2;   

              }else{
               
                $admin=1;

              }
             
             if($post&&$thread){



                 $this->redirect("posts/show/id/$post/thread/$thread/ban/$admin");
             }else{

                 $this->redirect("users/list/");
              
              }


          }else{

            $this->redirect("users/list/");
          
          } 


        // action body
    }

    public function bandelAction($id)
    {
        
          $this->_helper->viewRenderer->setNoRender();
          
          $id = $this->getRequest()->getParam('id');
          
         
          if($id && $id != 1){


             $user_model = new Application_Model_DbTable_User();
             $user_model->bandelUser($id);
             $this->redirect("users/list/ban/t");
          
          }else{

            $this->redirect("users/list/ban/t");
          
          }
            

                  
             
    }

    public function banemailAction()
    {
        

        $ban_form=new Application_Form_Ban();
        $this->view->ban_form=$ban_form;
        
        if($this->getRequest()->isPost()){
            $email = $this->getRequest()->getParam('email');
           # var_dump($email);
            if($ban_form->isvalid($_POST)){
            $user_model = new Application_Model_DbTable_User();
            
            $user=$user_model->getUserByEmail($email);


               if($user[0]['banned'] =='t'){

                  echo "this user is already in the ban list";
                  return;
               }
              if ($user[0]['user_id'] !=1){
                $user_model->banemailUser($email);
                $this->redirect('users/list/ban/t'); 
              }else{
                   echo "Can not ban this user ";
              }

            #     
          
          }        
            
      }


    }

    public function changemailAction()
    {
        // action body

       $email_form= new Application_Form_Changemail();
           
           $id = $this->getRequest()->getParam('id');
           if(!$id){
             
               $this->redirect('/users/list');

           }

       $this->view->email_form=$email_form;
      

      if($this->getRequest()->isPost()){
       
            if($email_form->isvalid($_POST)){


                $p=$this->getRequest()->getParams();

                $user_model = new Application_Model_DbTable_User();
                
               # var_dump($p);

                $user=$user_model->change_email($p); 
                   
                $user1=$user_model->getUserByEmail($p['email1']);
      
              $this->redirect("users/list/ban/".$user1[0]['banned']."");  
      

         }

       }


    }

   
   public function readallAction($ban)
   {
   
    $page = $this->_request->getParam('page',1); //get curent page param, default 1 if param not available.
    $model = new Application_Model_DbTable_User(); // get Model
    $data = $model->listUsers($ban); // call Method
    $adapter = new Zend_Paginator_Adapter_DbSelect($data); //adapter
    $paginator = new Zend_Paginator($adapter); // setup Pagination
    $paginator->setItemCountPerPage(3); // Items perpage, in this example is 10
    $paginator->setCurrentPageNumber($page); // current page
    $this->view->users = $paginator;
 }   



function register_vertify($p){

      
      
      $to =$p['email'];

      $subject = "Register vertification";

      $message = " Welcome to forum we are a memeber now of our forum's friends your registeration information<br>";
      $message.="user name :".$p['name']."<br>";
      $message.="phone number :".$p['phone_number']."<br>";
      $message.="gender :".$p['gender'].'<br>';
       
      $model= new Application_Model_DbTable_Countries();
      
      $con=$model->getCountryById($p['country']);
      
      $message.="county :".$con[0]['country'].'<br>';

      // Always set content-type when sending HTML email
      
      $headers = "MIME-Version: 1.0" . "\r\n";
      
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

      // More headers
      $headers .= 'From: <omniaawahab29@gmail.com>' . "\r\n";
        
      mail($to,$subject,$message,$headers);
 
      
 
      
      }

}
















