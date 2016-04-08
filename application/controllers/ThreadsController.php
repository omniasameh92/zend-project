<?php

class ThreadsController extends Zend_Controller_Action
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
        
    }

    public function indexAction()
    {
        // action body
             
        $cat_form=new Application_Form_Getsub();
         
        $this->view->form=$cat_form;


        if($this->getRequest()->isPost()){
       
            if($cat_form->isvalid($_POST)){
                
                 $p=$this->getRequest()->getParams();
                 #var_dump($p);
                 $this->redirect("/threads/list/id/".$p['forum'].""); 
                  

                }

       }    

         #change js
         #post/reply
         #add
         #edit
         #update
         #delete    
    
    }

    public function listAction()
    {

    	 $id = $this->getRequest()->getParam('id');
          
         
           if($id){
              
                   $sub_model = new Application_Model_DbTable_Subcategory();        
                  
                   $post_model = new Application_Model_DbTable_Post();
                   
                   $this->readallAction($id);
                  
                   $this->view->sub=$sub_model->getSubById($id);
                   
                   $this->view->id=$id;       
                     

            }else{

            	$this->redirect('/threads');
            }

    }


     
   public function readallAction($id)
   
   {
   
    $page = $this->_request->getParam('page',1); //get curent page param, default 1 if param not available.
    $model= new Application_Model_DbTable_Post(); // get Model
    $data =    $model->listPosts($id); // call Method
    $adapter   = new Zend_Paginator_Adapter_DbSelect($data); //adapter
    $paginator = new Zend_Paginator($adapter); // setup Pagination
    $paginator->setItemCountPerPage(3); // Items perpage, in this example is 10
    $paginator->setCurrentPageNumber($page); // current page
    $this->view->posts = $paginator;
  }   


}



