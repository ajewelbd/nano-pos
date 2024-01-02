<?php
namespace POS\Products;
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Config\Connection;
use PDO;



class Products{
    
    public $id="";
    public $product_name="";
    public $product_model="";
    public $size="";
    public $base_price="";
    public $added_by="";
	public $datetime="";
	public $barcode="";
	protected $db;
    
    
    
   public function __construct(){
       $this->db=Connection::db();
        
    }
    public function prepare($data=array()){
		//Utility::dd($data);
			$this->added_by    		=   mysql_real_escape_string($data->uid);
            $this->product_name    	=   mysql_real_escape_string($data->product_name);
            $this->product_model   	=   mysql_real_escape_string($data->product_model);
            $this->size				=   mysql_real_escape_string($data->size);
            $this->base_price     	=   mysql_real_escape_string($data->base_price);
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
		$barcode=rand(100000000000, 9999999999999);
        $sql="INSERT INTO products(barcode, product_name, product_model, size, base_price, added_by, active) 
                   VALUES($barcode, '".$this->product_name."','".$this->product_model."','".$this->size."','".$this->base_price."','".$this->added_by."',1)";
        
        //Utility::dd($sql);
		$query=$this->db->prepare($sql);
        $result=$query->execute();
        
        if($result){
            Message::set('<div class="alert alert-success"><strong>Registration Sucessfull! Please login!!!!!!</strong></div>');
        }else{
            //echo "failed";
            Message::set('<div class="alert alert-warning"><strong>Something is Wrong. Try Again!</strong></div>');
        }
        return true;
    }
    
    public function show(){
		$sql="SELECT * FROM products WHERE active=1 ORDER BY created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		
		$result=$query->setFetchMode(PDO::FETCH_ASSOC);
		$products=array();
		return $products[]=$query->fetchAll();
		//return json_encode($products);
    }
	public function showTrash(){
		$sql="SELECT * FROM products WHERE active=0 ORDER BY created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		
		$result=$query->setFetchMode(PDO::FETCH_ASSOC);
		$products=array();
		return $products[]=$query->fetchAll();
		//return json_encode($products);
    }
   
	public function view($id){
		$sql="SELECT * FROM products WHERE id=".$id;
		$query=$this->db->prepare($sql);
		$query->execute();
		$product=$query->setFetchMode(PDO::FETCH_OBJ);
		$product=$query->fetchAll();
		return $product;
		//Utility::dd($sql);
    }
	public function keyCheck($keys, $array){
		foreach($keys as $key){
			if(!array_key_exists($key, $array)){
				return false;
			}
			return true;
		}
	}
    
    public function update($product_data, $id){
		//$data=array();
		$columns=array('id','updated_by','product_name', 'product_model', 'size', 'base_price');
		$colvalSet = '';
		$i = 0;
		foreach($product_data as $key=>$val){
			$pre = ($i > 0)?', ':'';
			$val = htmlspecialchars(strip_tags($val));
			$colvalSet .= $pre.$key."='".$val."'";
			$i++;
		}
		//echo $colvalSet;die();
		$sql="UPDATE products SET " . $colvalSet ." WHERE id=" .$id;
		//echo $sql;die();
		$query=$this->db->prepare($sql);
		$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Product Updated Succesfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Product Update Failed!!!";
            }
            return $data;
		
			
    }
    
    public function softDelete($pid){
         if(is_null($pid)){
			 $msg="No product selected yet";
            return $msg;
        }else{
			$this->datetime=date('Y:m:d H:i:s');
            $sql="UPDATE products SET active=0 WHERE id = " .$pid;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Product Deleted Sucessfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Product Delation Failed!!!";
            }
            return $data;
        }
	
	
	}
	
	public function restore($pid){
         if(is_null($pid)){
			 $msg="No product selected yet";
            return $msg;
        }else{
			//$this->datetime=date('Y:m:d H:i:s');
            $sql="UPDATE products SET active=1 WHERE id = " .$pid;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Product Restored Sucessfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Product storing failed!!!";
            }
            return $data;
        }
	
	
	}
	
	public function finalDelete($pid){
         if(is_null($pid)){
			 $msg="No product selected yet";
            return $msg;
        }else{
            $sql="DELETE FROM products WHERE id = " .$pid;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Product Permanently Deleted!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Product Deletion Failed!!!";
            }
            return $data;
        }
	
	
	}
	
}

?>
