<?php

class PostsController extends Zend_Controller_Action
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
      $this->redirect('threads');       

    }

    public function listAction()
    {
          $this->redirect('threads');   
        // action body
    }

    public function addAction()
    {
          

       $authorization =Zend_Auth::getInstance();
      $user=isset($_COOKIE['user_id'])?$_COOKIE['user_id']:$authorization->getIdentity()->user_id;         

      $add_form=$this->view->add_post= new Application_Form_Addpost();
      $p=$this->getRequest()->getParams();
        if($this->getRequest()->isPost()){
             if($add_form->isvalid($_POST)){
                $post_model = new Application_Model_DbTable_Post();
                    
                $sub_model = new Application_Model_DbTable_Subcategory();     
                
              
               
                if($p['id']){
                    

                $sub=$sub_model->getSubById($p['id']);

                  if($sub[0]['post_allow']=='t'){

                     $post_model->addPost($p,$user);


                  }
                 
                      
                     # $this->redirect("/threads/list/id/".$p['id']);
                }

                     $this->redirect("/threads/list/id/".$p['id']);

                
              
               
            
            }
          } 


        // action body
    }

    public function deleteAction()
    {

          $this->_helper->viewRenderer->setNoRender();
          $id = $this->getRequest()->getParam('id');
          $sub = $this->getRequest()->getParam('sub');
         if($id && $sub){
            $post_model = new Application_Model_DbTable_Post();
            $post_model->deletePost($id);

            $this->redirect("/threads/list/id/$sub");
        }else{

            $this->redirect('/threads');
        }
        // action body
    }

    public function editAction()
    {

        $p_form=new Application_Form_Addpost();
        $submit=$p_form->getElement('submit');
      
        $submit->setLabel('edit post');

        $this->view->p_form=$p_form;               
        if($this->getRequest()->isPost()){
             if($p_form->isvalid($_POST)){
            $p=$this->getRequest()->getParams();
            $post_model = new Application_Model_DbTable_Post();
        
          
            $post_model->editPost($p);
            
             $post_model = new Application_Model_DbTable_Post();
            
             $post=$post_model->getPostById($p['id']);
                 
                           
            $this->redirect("posts/show/id/".$p['id']."/thread/".$p['thread']);
            
            }

          }
            
           $id=$this->getRequest()->getParam('id');
       
           $thread=$this->getRequest()->getParam('thread');
       
           if($id&&$thread){
            
             $post_model = new Application_Model_DbTable_Post();
             
            
             $post=$post_model->getPostById($id);
         
             $p_form->populate($post[0]);
            

          }else{
               
                     
                    $this->redirect("threads");

            
          }

        // action body
    }

    public function showAction()
    {
            


            $id=$this->getRequest()->getParam('id');
            
            $ban=$this->getRequest()->getParam('ban');
            
            $thread=$this->getRequest()->getParam('thread');
            
            $this->view->ban=$ban;

             $post_model = new Application_Model_DbTable_Post();
             $user_model=new Application_Model_DbTable_User();

             $com_model= new Application_Model_DbTable_Comment();
            
            $rep_form=new Application_Form_Addcomment();
            
            if($id&&$thread){
            
            
            
             $post=$post_model->getPostById($id);
             
             $this->view->post=$post;
             
             $this->view->thread=$thread;
             
             $user=$user_model->getUserById($post[0]['user_id']);
             
             $this->view->user=$user;
            
             
              $comments=$com_model->getComments($post[0]['id']); 
               
              $this->view->comments=$comments;
        
           
              $this->view->rep_form=$rep_form;



          }else{

                $this->redirect("/threads");     
        }

          if($this->getRequest()->isPost()){
             if($rep_form->isvalid($_POST)){
               $p=$this->getRequest()->getParams();
               $com_model = new Application_Model_DbTable_Comment();
               $authorization =Zend_Auth::getInstance();
               $user_id=isset($_COOKIE['user_id'])?$_COOKIE['user_id']:$authorization->getIdentity()->user_id;       
               #var_dump($p);
               $post=$post_model->getPostById($p['id']);
                  
                
                  if($post[0]['comment_allow']=='t'){

                        
                           $com_model->addComment($p,$user_id);

                           $this->redirect("/posts/show/id/".$p['id']."/thread/".$p['thread']); 
                      
                      }

          }

     }

    
    }

    public function stickyAction()
    {

         $this->_helper->viewRenderer->setNoRender();
          
          $id = $this->getRequest()->getParam('id');
          
         $sub = $this->getRequest()->getParam('sub');

          if($id&&$sub){

             $p_model = new Application_Model_DbTable_Post();
             $p_model->stickpost($id);
             $this->redirect("threads/list/id/$sub");
          
          }else{
               
               if($sub){
                    $this->redirect("threads/list/id/$sub");
                }else{
                     
                    $this->redirect("threads");

                }
             
          }
        // action body
    }

    public function locaddAction()
    {


        $this->_helper->viewRenderer->setNoRender();
          
        $id = $this->getRequest()->getParam('id');
          
        $sub = $this->getRequest()->getParam('sub');

          if($id&&$sub){

             $p_model = new Application_Model_DbTable_Post();
             
             $p_model->locComment($id);
             
             $this->redirect("threads/list/id/$sub");
          
          }else{
               
               if($sub){
                    $this->redirect("threads/list/id/$sub");
                }else{
                     
                    $this->redirect("threads");

                }
             
          }


        // action body
    }

    

    public function removelocAction()
    {
        
        $this->_helper->viewRenderer->setNoRender();
          
        $id = $this->getRequest()->getParam('id');
          
         $sub = $this->getRequest()->getParam('sub');

          if($id&&$sub){

             $p_model = new Application_Model_DbTable_Post();
             $p_model->removeloc($id);
             $this->redirect("threads/list/id/$sub");
          
          }else{
               
               if($sub){
                    $this->redirect("threads/list/id/$sub");
                }else{
                     
                    $this->redirect("threads");

                }
             
          }

        // action body
    }

    public function removestickyAction()
    {

        $this->_helper->viewRenderer->setNoRender();
          
        $id = $this->getRequest()->getParam('id');
          
         $sub = $this->getRequest()->getParam('sub');

          if($id&&$sub){

             $p_model = new Application_Model_DbTable_Post();
             $p_model->unstickpost($id);
             $this->redirect("threads/list/id/$sub");
          
          }else{
               
               if($sub){
                    $this->redirect("threads/list/id/$sub");
                }else{
                     
                    $this->redirect("threads");

                }
             
          }
        // action body
    }


}





















