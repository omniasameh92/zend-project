<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {


       $authorization =Zend_Auth::getInstance();
       
        
       if(isset($_COOKIE['user_id'])){
             
     
                      
            
              $user_model = new Application_Model_DbTable_User();
           
              $user=$user_model->getUserById($_COOKIE['user_id']);  

              if($user[0]['banned']=='t'){
                   
                   Zend_Auth::getInstance()->clearIdentity();
                   Zend_Session::destroy( true );       
                   
                  
                   setcookie("user_id","",time()-60*60*24,'/');
                   
                    
                   $this->redirect('/index/index/msg/ban');


              }

                $sys_state = new Application_Model_DbTable_System();
                            
               $state=$sys_state->getState();

              if($state[0]['state']=='f'&&$user[0]['admin']=='f'){

                   Zend_Auth::getInstance()->clearIdentity();
                   Zend_Session::destroy( true );       
                   
                   setcookie("user_id","",time()-60*60*24,'/');
                   $this->redirect('/index/index/msg/close');



              }
          
       

       }     

       if($authorization->hasIdentity()){
       
        
                                            
                 
            # var_dump($authorization->getIdentity());
              $user_model = new Application_Model_DbTable_User();
           
              $user=$user_model->getUserByEmail($authorization->getIdentity()->email);  

              if($user[0]['banned']=='t'){
                   
                   Zend_Auth::getInstance()->clearIdentity();
                   Zend_Session::destroy( true );       
                    
                   setcookie("user_id","",time()-60*60*24,'/');
                   $this->redirect('/index/index/msg/ban');


              } 


               $sys_state = new Application_Model_DbTable_System();
                            
               $state=$sys_state->getState();

              if($state[0]['state']=='f'&&$user[0]['admin']=='f'){

                   Zend_Auth::getInstance()->clearIdentity();
                   Zend_Session::destroy( true );       
                   
                   setcookie("user_id","",time()-60*60*24,'/');
                   $this->redirect('/index/index/msg/close');



              }
          

                           
   
   
       }

         
             
       
        /* Initialize action controller here */


    }

    public function indexAction()
    {


          # echo"here";
         $this->_helper->layout()->disableLayout();

         $authorization =Zend_Auth::getInstance();
            
            $this->view->msg=$this->getRequest()->getParam('msg');;
              
          if(!$authorization->hasIdentity()&&!isset($_COOKIE['user_id'])){

            $login_form= new Application_Form_Login(); 
            $this->view->login_form=$login_form;
              if($this->getRequest()->isPost()){
           
                  if($login_form->isvalid($_POST)){
                   #    echo"here";
                        $email= $this->getRequest()->getParam('email');
                        $password= $this->getRequest()->getParam('password');
                        $remember= $this->getRequest()->getParam('remember');
                        
                        $db =Zend_Db_Table::getDefaultAdapter();

                        $authAdapter = new Zend_Auth_Adapter_DbTable($db,'users','email','password');
                       
                        $authAdapter->setIdentity($email);
                        $authAdapter->setCredential(md5($password));
                       
                        $result = $authAdapter->authenticate();

                        if ($result->isValid()){

                         
                            $auth =Zend_Auth::getInstance();
                            
                            $storage = $auth->getStorage();
                            
                            $storage->write($authAdapter->getResultRowObject(array('email','user_id','name','banned','admin'))); 
                         
                           


                            $admin=$storage->read()->admin;
                            $ban=$storage->read()->banned;
                            
                            $sys_state = new Application_Model_DbTable_System();
                            
                            $state=$sys_state->getState();

                        #    print_r($state);
                             #echo $storage->read()->user_id;
                         #   echo "admin:$admin";
                          #  echo "ban :$ban";
                            if($state[0]['state']=='f'&&$admin=='f'){

                                   Zend_Auth::getInstance()->clearIdentity();
                                   Zend_Session::destroy( true );       
                                   
                                   $this->redirect('/index/index/msg/close');
                             
                             }else{

                                    if($ban=='t'){
                                         
                                      Zend_Auth::getInstance()->clearIdentity();
                                        Zend_Session::destroy( true );        
                                        $this->redirect('/index/index/msg/ban');  
                                          
                                    }
                                    
                                     if($remember){
                                          
                                             #setcookie("email",$storage->read()->email,time()+60*60*24);
                                            
                                           
                                             setcookie("user_id",$storage->read()->user_id,time()+60*60*24,'/');
                                             
                                              
                                          
                                          }

                                    if($admin=='t'){


                                        
                                         $this->redirect('/users/list');
                                         
                                    }else{
                                       

                                       $this->redirect('/index');
                                        

                                    }

                                   
                             }
                              

                        }else{
                             
                             $this->view->log_error="Invalid username or password";  
                         }

                }
        // action body
        
           }


      
          }

    }

    public function logoutAction()
    {
        
                   # echo"here";
                   $this->_helper->viewRenderer->setNoRender();
                   $this->_helper->layout()->disableLayout();
                  
                   $authorization =Zend_Auth::getInstance();
                 
                   #echo $authorization->getIdentity()->user_id;

                   Zend_Auth::getInstance()->clearIdentity();
                   Zend_Session::destroy( true );   

                   
                   setcookie("user_id","",time()-60*60*24,'/');
                   $this->redirect('/index');

        // action body
    }

    public function forgetpassAction()
    {

          $this->_helper->layout()->disableLayout();
          $p_form= new Application_Form_Forgetpass();
          $this->view->p_form=$p_form; 
          $msg= $this->getRequest()->getParam('msg');
          $this->view->msg=$msg;
              
          if($this->getRequest()->isPost()){
           
                  if($p_form->isvalid($_POST)){
                       
                        $p= $this->getRequest()->getParams();
                          
                        
                         $user_model = new Application_Model_DbTable_User();
                        
                         $user=$user_model->getUserByEmail($p['email']);
                         
                       #  var_dump($user[0]);
                         $this->sendemail($user[0]);
                         $this->redirect('/index/forgetpass/msg/1');                     
                  }

              }
        // action body
    }

    public function sendemail($p)
    {

      
       
      $to =$p['email'];

      $subject = "Reset password forum";

      $message = "go to the this link to reset your password ,thanks http://cafeteriaproj-cafa.rhcloud.com/index/resetpass/id/".$p['user_id'];

    
  
      $headers = "MIME-Version: 1.0" . "\r\n";
      
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

      // More headers
      //$headers .= 'From: <webmaster@example.com>' . "\r\n";
        
      mail($to,$subject,$message);
 
      
    }

    public function resetpassAction()
    {


          
          $this->_helper->layout()->disableLayout();
          
          $p_form= new Application_Form_Resetpass();

          $msg= $this->getRequest()->getParam('msg');
          $this->view->msg=$msg;
          
          $this->view->p_form=$p_form;  

          if($this->getRequest()->isPost()){
           
                  if($p_form->isvalid($_POST)){
                       
                         $p= $this->getRequest()->getParams();
                          
                         $user_model = new Application_Model_DbTable_User();
                        
                         $user=$user_model->getUserByEmail($p['email']);
                         
                         if($p['id']&&$p['id']==$user[0]['user_id']){

                               $user_model->resetpass($p['password'],$p['email']);
                               
                               $this->redirect('index/index/msg/passreset');
                      
                         }else{
                                             
                             $this->redirect('index/resetpass/msg/invalid');
                         }
                     
                  }

              }

        // action body
    }


}







