<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

     protected $_name = 'users';

     function listUsers($ban){


         $select  = $this->select()->where('banned=?',$ban)->where('user_id !=1')->order('user_id');
         
         return $select;
      
   
    }


    public function editUser($data){
        
       $row=array('name'=>$data['name'],'gender'=>$data['gender'],'country'=>$data['country'],'phone_number'=>$data['phone_number']);

     if(isset($data['password'])&&!empty($data['password'])){
       
       $row['password']=md5($data['password']);    

     }
    
     if(isset($data['photo'])&&!empty($data['photo'])){
       
       $row['photo']=$data['photo'];    

     }
       
     return $this->update($row,"user_id=".$data['id']);

    }

     function deleteUser($id){
        return $this->delete("user_id=$id");
    }

     function adminUser($id,$admin){
        
        $row=array('admin'=>$admin);
        return $this->update($row,"user_id=".$id);
    }

    function banUser($id){
        $row=array('banned'=>'t');
        return $this->update($row,"user_id=".$id);
    }
     
    function bandelUser($id){
        $row=array('banned'=>'f');
        return $this->update($row,"user_id=".$id);
    }

    function addUser($data){   
        $row = $this->createRow();
        $row->name = $data['name'];
        $row->password = md5($data['password']);
        $row->email = $data['email'];
        $row->gender= $data['gender'];
        $row->country= $data['country'];
        $row->phone_number= $data['phone_number'];
        $row->photo= $data['photo'];
        $row->signature= $data['signature'];
        $row->admin='f';
        $row->banned='f';
        return $row->save();
      
      }

     
     function banemailUser($email){
               
        $row=array('banned'=>'t');
        return $this->update($row,"email='".$email."'");

     }

     function getUserByEmail($email){

        $select  = $this->select()->where('email=?',$email);
        
         return $this->fetchAll($select)->toArray();

     }

     function getUserById($id){

        $select  = $this->select()->where('user_id=?',$id);
        
         return $this->fetchAll($select)->toArray();

     }


     function change_email($data){

        $row=array('email'=>$data['email1']);
        return $this->update($row,"user_id='".$data['id']."'");

     }

   

    function resetpass($pass,$email){

        $row=array('password'=>md5($pass));
        return $this->update($row,"email='".$email."'");

    }

}

