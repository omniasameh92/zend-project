<?php

class RepliesController extends Zend_Controller_Action
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
        // action body
            $this->redirect('threads');
    }

    public function addAction()
    {
           
           $this->redirect('threads');
            
        // action body
    }

    public function editAction()
    {

           
        $com_model = new Application_Model_DbTable_Comment();  

        $com_form=new Application_Form_Addcomment();
        
        $submit=$com_form->getElement('submit');
      
        $submit->setLabel('edit comment');

        $this->view->com_form=$com_form;

        if($this->getRequest()->isPost()){
             if($com_form->isvalid($_POST)){
            
                 $p=$this->getRequest()->getParams();
               
        
          
                 $com_model->editComment($p);
            
              
                    
                 $com=$com_model->getCommentById($p['id']);
                #var_dump($p);
                           
                 $this->redirect("posts/show/id/".$com[0]['post_id']."/thread/".$p['thread']);
            
            }

          }
            
           $id=$this->getRequest()->getParam('id');
           
            $thread=$this->getRequest()->getParam('thread');
           
            $post_id=$this->getRequest()->getParam('post_id');
           
           if($id&&$post_id&&$thread){
            
            
            
             $comment=$com_model->getCommentById($id);
         
             $com_form->populate($comment[0]);
            
          }else{
               
            
              $this->redirect("/threads");
          }
        // action body
        // action body
    }

    public function deleteAction()
    {
         $this->_helper->viewRenderer->setNoRender();
          $id = $this->getRequest()->getParam('id');
          $post_id= $this->getRequest()->getParam('post_id');
          $thread= $this->getRequest()->getParam('thread');
        
         if($id && $post_id && $thread){
            
            $com_model = new Application_Model_DbTable_Comment();
            $com_model->deleteComment($id);
            $this->redirect("/posts/show/id/$post_id/thread/$thread");

        }else{

            $this->redirect('/threads');
        }
        // action body
    }

    public function listAction()
    {
        // action body
    }


}









