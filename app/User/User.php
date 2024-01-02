<?php
namespace POS\User;
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;



class User{
    
    public $id="";
    public $firstname="";
    public $lastname="";
    public $address1="";
    public $address2="";
    public $city="";
    public $state="";
    public $card_type="";
    public $card_number="";
    public $expire="";
    public $cvc="";
    public $mobile="";
    public $email="";
    public $username="";
    public $password="";
    
    
    
   public function __construct(){
       Config::db();
        
    }
    public function prepare($data=array()){
        //Utility::dd($data);
        if(is_array($data) && array_key_exists('firstname', $data)){
            $this->firstname    =   $data['firstname'];
            $this->lastname     =   $data['lastname'];
            $this->address1     =   $data['address1'];
            $this->address2     =   $data['address2'];
            $this->city         =   $data['city'];
            $this->state        =   $data['state'];
            $this->mobile       =   $data['mobile'];
            $this->email        =   $data['email'];
            $this->mobile       =   $data['mobile'];
            $this->username     =   $data['username'];
            $this->password     =   $data['password'];
            
            if(array_key_exists("card_type",$data) && !empty($data)){
            $this->card_type    =   $data['card_type'];
            $this->card_number  =   $data['card_number'];
            $this->expire       =   $data['expire'];
            $this->cvc          =   $data['cvc'];
            }
           if(array_key_exists("id",$data) && !empty($data)){
               $this->id = $data['id'];
            }
                        
            
        }
        return $this;
    }
    public function cardPrepare($data=array()){
        if(array_key_exists("id",$data) && !empty($data)){
            $this->id           =   $data['id'];
            $this->card_type    =   $data['card_type'];
            $this->card_number  =   $data['card_number'];
            $this->expire       =   $data['expire'];
            $this->cvc          =   $data['cvc'];
            }
    }
    
    public function store(){
        //Utility::dd($this->username);
        $sql1="INSERT INTO user(firstname, lastname, address1, address2, city, state, email, mobile, username, password) 
                   VALUES('".$this->firstname."','".$this->lastname."','".$this->address1."','".$this->address2."','".$this->city."','".$this->state."','".$this->email."','".$this->mobile."','".$this->username."','".$this->password."')";
        $sql2="INSERT INTO card(username, card_type, card_number, expire, cvc) VALUES('".$this->username."','".$this->card_type."','".$this->card_number."','".$this->expire."','".$this->cvc."')";        
        $sql3="INSERT INTO login(username, password, email) VALUES('".$this->username."','".$this->password."','".$this->email."')";
        
        //utility::dd($sql3);
        $result1=mysql_query($sql1);
        $result2=mysql_query($sql2);
        $result3=mysql_query($sql3);
        
        if(($result1) && ($result2) && ($result3)){
            Message::set('<div class="alert alert-success"><strong>Registration Sucessfull! Please login!!!!!!</strong></div>');
        }else{
            //echo "failed";
            Message::set('<div class="alert alert-warning"><strong>Something is Wrong. Try Again!</strong></div>');
        }
        Utility::redirect('../login/login.php');
    }
    
    public function view($login_user){
         if(is_null($login_user)){
            return;
        }else{
            $sql="SELECT * FROM user WHERE username='$login_user'";
            $result=mysql_query($sql);
            $user=mysql_fetch_object($result);
            return $user;
        }
        
    }
    public function paymentView($login_user){
         if(is_null($login_user)){
            return;
        }else{
            $sql="SELECT * FROM card WHERE username='$login_user'";
            $result=mysql_query($sql);
            $user=mysql_fetch_object($result);
            return $user;
        }
        
    }
    
    public function update(){
            $sql="UPDATE user set firstname='".$this->firstname."', lastname='".$this->lastname."', email='".$this->email."', mobile='".$this->mobile."', address1='".$this->address1."',address2='".$this->address2."', city='".$this->city."', state='".$this->state."', password='".$this->password."' WHERE id=".$this->id;
            //utility::dd($sql);
            $result=mysql_query($sql);
            if($result){
              Message::set('<div class="alert alert-success"><strong>Profile updated Sucessfully!</strong> </div>');
            }else{
                Message::set('<div class="alert alert-warning"><strong>Profile update failed! Try again</strong> </div>');
            }
            Utility::redirect('view.php');
        
        
    }
    public function cardUpdate(){
            $sql="UPDATE card set card_type='".$this->card_type."', card_number='".$this->card_number."', expire='".$this->expire."', cvc='".$this->cvc."' WHERE id='".$this->id."'";
            //utility::dd($sql);
            $result=mysql_query($sql);
            if($result){
              Message::set('<div class="alert alert-success"><strong>Credit Card updated Sucessfully!</strong> </div>');
            }else{
                Message::set('<div class="alert alert-warning"><strong>Credit Card update Error!</strong> </div>');
            }
            Utility::redirect('payment.php');
       } 
        
    
    
    public function delete($id=null){
         if(is_null($id)){
            return;
        }else{
            $sql="DELETE FROM hobby WHERE id=".$id;
            $result=mysql_query($sql);
            if($result){
              Message::set('<div class="alert alert-success"><strong>Hobby Data Deleted Sucessfully!</strong> </div>');
            }else{
              Message::set('<div class="alert alert-warning"><strong>Hobby Data Not Deleted  Sucessfully!</strong> </div>');
            }
            Utility::redirect('index.php');
        }
    
}
}

?>
