<?php
namespace POS\Auth;
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;



class Auth{
    public $id="";
    public $username="";
    public $password="";
	public $db="";
    
    public function __construct(){
        //Config::db();
    }
    
    public function loginPrepare($data=array()){
        //Utility::dd($data);
        if(is_array($data) && array_key_exists('username', $data)){
            $this->username=$data['username'];
            $this->password=$data['password'];
        }
        return $this;
    }
    
    public function loginCheck(){
        $sql= $this->db->prepare("SELECT * FROM login WHERE username=$username AND password=$password");
        //Utility::dd($sql);
        $result=mysql_query($sql);
        $row=mysql_fetch_assoc($result);
       if(!empty($row)){
           $_SESSION['username']=$this->username;
        //Utility::dd($sql);
           Utility::redirect("../dashboard/index.php");
        } else{
            Message::set('<div class="alert alert-warning"><strong>Wrong Username or Password !</strong></div>');
            Utility::redirect("../../index.php");
        }
       // 
    }
    
    public function is_loggedin(){
        if(isset($_SESSION['username'])){
            if(!is_null($_SESSION['username']) && !empty($_SESSION['username'])){
                return TRUE; 
            }
        } else{
            return FALSE;
        }
    }
    
}

