<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

     protected function _initSession(){

               Zend_Session::start();
               $session = new Zend_Session_Namespace('user_Auth');
         }

   protected function _initPlaceholders() {
       // $this->bootstrap('View');
        //$view = $this->getResource('View');
      
        $view = Zend_Layout::startMvc()->getView();
        
        
        $view->doctype('XHTML1_STRICT');
        //Meta
        $view->headMeta()->appendName('keywords', 'framework, PHP')->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');

       #  $view->headMeta()->appendName('viewport', 'framework, PHP')->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
        
        

        //('Content-Type','text/html;charset=utf-8');
        // Set the initial title and separator:
        $view->headTitle('Forum')->setSeparator(' :: ');
        // Set the initial stylesheet:
       $view->headLink()->prependStylesheet('http://cafeteria-cafa.rhcloud.com/static/css/bootstrap.min.css');
        
       
        $view->headLink()->appendStylesheet('http://cafeteria-cafa.rhcloud.com/static/css/simple-sidebar.css');

        $view->headLink()->appendStylesheet('http://cafeteria-cafa.rhcloud.com/static/css/jquery.cleditor.css');
      
        
        // Set the initial JS to load:
        $view->headScript()->appendFile('http://cafeteria-cafa.rhcloud.com/static/js/jquery.js');
        $view->headScript()->appendFile('http://cafeteria-cafa.rhcloud.com/static/js/bootstrap.min.js');
         $view->headScript()->appendFile('http://cafeteria-cafa.rhcloud.com/static/js/jquery.cleditor.min.js');

        $view->headScript()->appendFile('http://cafeteria-cafa.rhcloud.com/static/js/jquery.cleditor.js');
        
        
        
       
    }

}

