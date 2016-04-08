<?php

class SystemController extends Zend_Controller_Action
{

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
           
        /* Initialize action controller here */
    }

    public function indexAction()
    {
         $this->redirect("system/cstate/");
    }

    public function cstateAction()
    {
        
         #$this->_helper->viewRenderer->setNoRender();
          
          $c = $this->getRequest()->getParam('c');
          
         # $admin = $this->getRequest()->getParam('a');
           $sys_state = new Application_Model_DbTable_System();
           $this->view->state=$sys_state->getState();
          
          if($c){
             
             $sys_state->changeState($c);
             $this->redirect("system/cstate/");
          }      
          

    }


}



