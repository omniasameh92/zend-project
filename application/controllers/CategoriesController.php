<?php

class CategoriesController extends Zend_Controller_Action
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

         $this->redirect('categories/list/');
    }

    public function listAction()
   
     {
       
        #$cat_model = new Application_Model_DbTable_Category();
        #$this->view->categories = $cat_model->listCategories();   
        $this->readallAction();
   
     }

    public function addAction()
    {
        $cat_form=new Application_Form_Category();
        $this->view->cat_form=$cat_form;
        $p=$this->getRequest()->getParams();

        if($this->getRequest()->isPost()){
              
            if($cat_form->isvalid($_POST)){
                    
                $cat_model = new Application_Model_DbTable_Category();
                     $cat_model->addCategory($p);
                     $this->redirect('categories/list/');
            #var_dump($newFilename);
                 
                     
                   
             }
          
          }  
        // action body
    }

    public function editAction()
    {


        $cat_form=new Application_Form_Category();
        $submit=$cat_form->getElement('submit');
      
        $submit->setLabel('edit category');

        $this->view->cat_form=$cat_form;               
        if($this->getRequest()->isPost()){
             if($cat_form->isvalid($_POST)){
            $p=$this->getRequest()->getParams();
            $cat_model = new Application_Model_DbTable_Category();
        
            $cat_model->editCategory($p);
            $this->redirect('categories/list/');
            
            }

          }
            
           $id=$this->getRequest()->getParam('id');
           if($id){
            $cat_model = new Application_Model_DbTable_Category();
           
           $category=$cat_model->getCategoryById($id);
          // var_dump($category);
           $cat_form->populate($category[0]);
            
          }



        // action body
    }

    public function deleteAction()
    {
          $this->_helper->viewRenderer->setNoRender();
          $id = $this->getRequest()->getParam('id');
          
          if($id){
             $cat_model = new Application_Model_DbTable_Category();
             $cat_model->deleteCategory($id);
             $this->redirect("categories/list/");
        }else{

            $this->redirect("categories/list/");
        }
        // action body
    }

    public function getsubAction()
    {
      $id = $this->getRequest()->getParam('id');
      $page = $this->_request->getParam('page',1);
          
       if($id){
             $cat_model = new Application_Model_DbTable_Category();
             $sub_model=  new Application_Model_DbTable_Subcategory();
           
             $category=$cat_model->getCategoryById($id);
            
             $this->view->category=$category[0];
     
             $data=$sub_model->listSubCategory($category[0]['id']);

             $all=$sub_model->fetchAll($data)->toArray();
             $this->view->all=$all;
             $this->readSubAction($data,$page);

             
        }else{

            $this->redirect("categories/list/");
        }

    }

      
        public function readallAction()
   {
   
    $page = $this->_request->getParam('page',1); //get curent page param, default 1 if param not available.
    $model = new Application_Model_DbTable_Category(); // get Model
    $data = $model->listCategories(); // call Method
    $adapter = new Zend_Paginator_Adapter_DbSelect($data); //adapter
    $paginator = new Zend_Paginator($adapter); // setup Pagination
    $paginator->setItemCountPerPage(3); // Items perpage, in this example is 10
    $paginator->setCurrentPageNumber($page); // current page
    $this->view->categories = $paginator;
 }   


       public function readSubAction($data,$page)
   {
   
    
    $adapter = new Zend_Paginator_Adapter_DbSelect($data); //adapter
    $paginator = new Zend_Paginator($adapter); // setup Pagination
    $paginator->setItemCountPerPage(3); // Items perpage, in this example is 10
    $paginator->setCurrentPageNumber($page); // current page
    $this->view->sub = $paginator;
 }  


}   











