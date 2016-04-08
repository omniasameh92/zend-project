<?php

class SubcategoryController extends Zend_Controller_Action
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
         $this->redirect("categories");
        // action body
    }

    public function addAction()
    {

             
         $sub_form=new Application_Form_SubCategory();
         $this->view->sub_form=$sub_form;
        
        if($this->getRequest()->isPost()){
              
            if($sub_form->isvalid($_POST)){

               $p=$this->getRequest()->getParams();
                    
               $sub_model = new Application_Model_DbTable_Subcategory();
       
                     $sub_model->addSub($p);
                     $this->redirect("categories/getsub/id/".$p['id']."");
      
                 
                     
                   
             }
          
          }  
        // action body
    }

    public function editAction()
    {
           
        $sub_form=new Application_Form_SubCategory();
        $submit=$sub_form->getElement('submit');
      
        $submit->setLabel('edit forum');

        $this->view->sub_form=$sub_form;               
        if($this->getRequest()->isPost()){
             if($sub_form->isvalid($_POST)){
            $p=$this->getRequest()->getParams();
            $sub_model = new Application_Model_DbTable_Subcategory();
        
          
            $sub_model->editSub($p);
             
            $this->redirect("categories/getsub/id/".$p['sub']."");
            
            }

          }
            
           $id=$this->getRequest()->getParam('id');
            $sub=$this->getRequest()->getParam('sub');
           if($id&&$sub){
            $sub_model = new Application_Model_DbTable_Subcategory();
           
             $sub=$sub_model->getSubById($id);
         
             $sub_form->populate($sub[0]);
            
          }else{

            if($sub){
                   $this->redirect("categories/getsub/id/$sub");
                }else{
                     
                   $this->redirect("categories/list");
                }             
          }


                        
    


    }

    public function deleteAction()
    {

     
          $this->_helper->viewRenderer->setNoRender();
          $id = $this->getRequest()->getParam('id');    
          $sub = $this->getRequest()->getParam('sub');     
          if($id&&$sub){
             $sub_model = new Application_Model_DbTable_Subcategory();
             $sub_model->deleteSub($id);
        
             $this->redirect("categories/getsub/id/$sub");
        
        }else{
                 
                if($sub){
                   $this->redirect("categories/getsub/id/$sub");
                }else{
                     
                   $this->redirect("categories/list");
                }          
          }

        // action body

    }

    public function listAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $id = $this->getRequest()->getParam('id');
       
        if($id){
          $cat_model = new Application_Model_DbTable_Subcategory();
             $cat=$cat_model->listSubCategory($id);
             $cat=$cat_model->fetchAll($cat)->toArray();  
              $arr=array();
            foreach ($cat as  $c) {
              $key=$c['id'];
              $arr[$key]=$c['sub_category'];  
           
           }
                echo json_encode($arr);
        
        }else{

            echo json_encode(array());
        }


        #$this->redirect('categories');
    }

    public function locaddAction()
    {

       
        $this->_helper->viewRenderer->setNoRender();
          
        $id = $this->getRequest()->getParam('id');
          
        $sub = $this->getRequest()->getParam('sub');

          if($id&&$sub){

             $sub_model = new Application_Model_DbTable_Subcategory();
             
             $sub_model->locPost($id);
             
             $this->redirect("categories/getsub/id/$sub");
          
          }else{
               
               if($sub){
                   $this->redirect("categories/getsub/id/$sub");
                }else{
                     
                   $this->redirect("categories/list");
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

             $sub_model = new Application_Model_DbTable_Subcategory();
             
             $sub_model->allowPost($id);
             
             $this->redirect("categories/getsub/id/$sub");
          
          }else{
               
               if($sub){
                   $this->redirect("categories/getsub/id/$sub");
                }else{
                     
                   $this->redirect("categories/list");
                }
             
          }

        // action body
    }


}













