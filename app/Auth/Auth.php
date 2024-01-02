<?php
namespace POS\Auth;
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Connection;
use PDO;



class Auth{
    public $id="";
    public $username="";
    public $password="";
	protected $db;
    
    public function __construct(){
        $this->db=Connection::db();
    }
    
    public function loginPrepare($data=array()){
        //Utility::dd($data);
        if(is_array($data) && array_key_exists('username', $data)){
            $this->username=$data['username'];
            $this->password=$data['password'];
        }
        return $this;
    }
	
	static public function login_log($id, $token){
		$db=Connection::db();
		$sql="INSERT INTO login(user_id, token) VALUES(:id, :token)";
		$query=$db->prepare($sql);
		$result=$query->execute(array(
			':id'		=>$id,
			':token'	=>$token,
		));
		if(!$query){
			echo "Something is Wrong";
		}else{
			$_SESSION['logged_id']=$db->lastInsertId();
			return true;
		}
        
    }
    
    public function loginCheck(){
        $sql= "SELECT * FROM user WHERE username=:username AND password=:password";
		$query=$this->db->prepare($sql);
		
		$result=$query->execute(array(
			':username'=>$this->username,
			':password'=>$this->password,
		));
		$rows=$query->rowCount();
		$find_id=$query->fetch(PDO::FETCH_OBJ);
		$id=$find_id->id;
        //Utility::dd($id);
       if(!empty($rows)){
		   $token=$id . rand(1,100000) . bin2hex(openssl_random_pseudo_bytes(10));
		   $login_log=self::login_log($id, $token);
		   if($login_log){
			    $_SESSION['username']=$this->username;
				$_SESSION['id']=$id;
				$_SESSION['token']=$token;
				
				//Utility::dd($sql);
			    Utility::redirect("../dashboard/index.php");
		   }else{
			   Message::set('<div class="alert alert-warning"><strong> Something is wrong!</strong></div>');
            Utility::redirect("../../index.php");
		   }
        } else{
            Message::set('<div class="alert alert-warning"><strong>Wrong Username or Password !</strong></div>');
            Utility::redirect("../../index.php");
        } 
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
	public function logout($id, $token){
		$sql="UPDATE login SET logout_time=NOW() WHERE id=:id AND token=:token";
		$query=$this->db->prepare($sql);
		$result=$query->execute(array(
			':id'			=>$id,
			':token'		=>$token,
		));
		//Utility::dd($token);
		if(!$query){
			echo "Something is Wrong";die();
		}else{
			return true;
		}
    }
    
}

