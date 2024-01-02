<?php
namespace POS\Inventory;
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Config\Connection;
use POS\Supplier\Supplier;
use PDO;



class Inventory{
    
    public $id="";
    public $product_id="";
    public $quantity="";
    public $added_by="";
	public $updated_by="";
	public $datetime="";
	protected $db;
    
    
    
   public function __construct(){
       $this->db=Connection::db();
        
    }
    public function prepare($data=array()){
		//Utility::dd($data);
			$this->added_by    			=   mysql_real_escape_string($data->uid);
            $this->product_id    			=   mysql_real_escape_string($data->product_id);
            $this->quantity   			=   mysql_real_escape_string($data->quantity);
        return $this;
    }
	
    public function store(){
		$barcode=rand(100000000000, 9999999999999);
        $sql="INSERT INTO inventory(barcode, inventory_name, inventory_model, size, base_price, added_by, active) 
                   VALUES($barcode, '".$this->inventory_name."','".$this->inventory_model."','".$this->size."','".$this->base_price."','".$this->added_by."',1)";
        
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
		$sql="SELECT inventory.id, inventory.product_id, inventory.quantity, inventory.added_by, inventory.updated_by, inventory.created, 
		inventory.updated, products.product_name FROM inventory INNER JOIN products on inventory.product_id=products.id ORDER BY updated DESC";
		//$sql="SELECT * FROM inventory";
		$query=$this->db->prepare($sql);
		$query->execute();
		
		$result=$query->setFetchMode(PDO::FETCH_ASSOC);
		$inventory=array();
		return $inventory[]=$query->fetchAll();
		//return json_encode($suppliers);
    }
	public function changeQuantity($inventory_data, $inv_id){
		$quantity='';
		$rematks='';
		$updated_by='';
		$buy_price=0;
		$supplier_id=0;
		$i = 0;
		$type='';
		//$new_quantity='';
		//$log="";
		foreach($inventory_data as $key=>$val){
			$supplier= new Supplier();
			
			$pre = ($i > 0)?', ':'';
			$val = htmlspecialchars(strip_tags($val));
			if($key=='quantity'){
				$quantity=$val;
			}elseif($key=='remarks'){
				$remarks=$val;
			}elseif($key=='updated_by'){
				$updated_by=$val;
			}elseif($key=='type'){
				$type=$val;
			}elseif($key=='supplier_id'){
				$supplier_id=$val;
			}elseif($key=='buy_price'){
				$buy_price=$val;
			}
			$i++;
		}
		$old_quantity="SELECT quantity FROM inventory WHERE id=".$inv_id;
		$query=$this->db->prepare($old_quantity);
		$query->execute();
		$query->setFetchMode(PDO::FETCH_OBJ);
		
		if($query){
			$old=$query->fetchAll();
			$old=$old[0]->quantity;
			if($type==1){
				$new_quantity=$old+$quantity;
			}elseif($type==0){
				$new_quantity=$old-$quantity;
			}elseif($type==2){
				$new_quantity=$old-$quantity;
			}
			$update="UPDATE inventory SET quantity='" . $new_quantity ."', updated_by='" . $updated_by ."' WHERE id=".$inv_id;
			$update=$this->db->prepare($update);
			$update->execute();
			if($type==1){
				$bill_info=[			
					'type'=>'buy',
					'supplier_id'=>$supplier_id,
					'inventory_id'=>$inv_id,
					'amount'=>$quantity * $buy_price,
					'payment_type'=>0,
					'remarks'=>'Product Buy',
					'added_by'=>$updated_by
				];
				$supplier_bill_payment=$supplier->billUpdate($bill_info);
			}
			
			if($update){
				$log="INSERT INTO inventory_log(inventory_id, supplier_id, buy_price, old_quantity, quantity, updated_quantity, inventory_type, remarks, added_by)
				VALUES('".$inv_id."','" .$supplier_id."','" .$buy_price."','" .$old."', '" .$quantity."', '" .$new_quantity."','".$type."','".$remarks. "','".$updated_by. "')";
				$log=$this->db->prepare($log);
				$log->execute();
				if($log){
					$data['state']="OK";
					$data['msg']="Quantity Changed Succesfully!!!";
				}else{
					$data['state']="ERR";
					$data['msg']="Quantity Changed Failed!!!";
				}
			}else{
					$data['state']="ERR";
					$data['msg']="Quantity Changed Failed!!!";
				}
			
		}
		else{
			$data['state']="ERR";
			$data['msg']="Quantity Change Failed!!!";
		}
		
		return $data;
		
	}
	
	public function showLogSingle($id){
		/*$sql="SELECT log.quantity, log.inventory_type, log.remarks, log.added_by, log.created, supplier.company_name FROM inventory_log AS log 
		INNER JOIN suppliers AS supplier ON log.supplier_id=supplier.id WHERE inventory_id=".$id;*/
		
		$sql="SELECT * FROM inventory_log WHERE inventory_id='".$id."' ORDER BY created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		$log=$query->setFetchMode(PDO::FETCH_ASSOC);
		$log=$query->fetchAll();
		for($i=0; $i<count($log); $i++){
			if($log[$i]['supplier_id']!=0){
				$sql="SELECT company_name FROM suppliers WHERE id=".$log[$i]['supplier_id'];
				$query=$this->db->prepare($sql);
				$query->execute();
				$supplier=$query->setFetchMode(PDO::FETCH_OBJ);
				$supplier=$query->fetchAll();
				$log[$i]['company_name']=$supplier[0]->company_name;
			}else{
				$log[$i]['company_name']='No Supplier';
			}
		}
		return $log;
		//Utility::dd($sql);
    }
	
	public function showLogAll(){
		$sql="SELECT log.inventory_id, log.old_quantity, log.quantity, log.updated_quantity, log.inventory_type, log.remarks, log.added_by, log.created,
		inventory.id AS inv_id, inventory.product_id,
		products.product_name, user.first_name, user.last_name 
		FROM inventory_log AS log 
		INNER JOIN inventory ON log.inventory_id=inventory.id 
		INNER JOIN products ON products.id=inventory.product_id 
		INNER JOIN user ON user.id=log.added_by ORDER BY log.created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		$inventory_log=$query->setFetchMode(PDO::FETCH_ASSOC);
		$inventory_log=$query->fetchAll();
		return $inventory_log;
		//Utility::dd($sql);
    }
	
	public function showTrash(){
		$sql="SELECT * FROM inventory WHERE deleted IS NOT NUll ORDER BY created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		
		$result=$query->setFetchMode(PDO::FETCH_ASSOC);
		$suppliers=array();
		return $inventory[]=$query->fetchAll();
		//return json_encode($inventory);
    }
   
	public function view($id){
		$sql="SELECT * FROM inventory WHERE id=".$id;
		$query=$this->db->prepare($sql);
		$query->execute();
		$inventory=$query->setFetchMode(PDO::FETCH_OBJ);
		$inventory=$query->fetchAll();
		return $inventory;
		//Utility::dd($sql);
    }
	
	public function getProducts(){
		$sql="SELECT id, product_name FROM products WHERE active=1 ORDER BY created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		$products=$query->setFetchMode(PDO::FETCH_ASSOC);
		$products=$query->fetchAll();
		return $products;
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
    
    public function update($_data, $id){
		//$data=array();
		$columns=array('id','updated_by','inventory_name', 'inventory_model', 'size', 'base_price');
		$colvalSet = '';
		$i = 0;
		foreach($inventory_data as $key=>$val){
			$pre = ($i > 0)?', ':'';
			$val = htmlspecialchars(strip_tags($val));
			$colvalSet .= $pre.$key."='".$val."'";
			$i++;
		}
		//echo $colvalSet;die();
		$sql="UPDATE inventory SET " . $colvalSet ." WHERE id=" .$id;
		//echo $sql;die();
		$query=$this->db->prepare($sql);
		$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Inventory Updated Succesfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Inventory Update Failed!!!";
            }
            return $data;
		
			
    }
    
    public function softDelete($pid){
         if(is_null($pid)){
			 $msg="No inventory selected yet";
            return $msg;
        }else{
			$this->datetime=date('Y:m:d H:i:s');
            $sql="UPDATE inventory SET deleted ='DATE()' WHERE id = " .$pid;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Inventory Deleted Sucessfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Inventory Delation Failed!!!";
            }
            return $data;
        }
	
	
	}
	
	public function restore($pid){
         if(is_null($pid)){
			 $msg="No inventory selected yet";
            return $msg;
        }else{
			$this->datetime=date('Y:m:d H:i:s');
            $sql="UPDATE inventory SET deleted =NULL WHERE id = " .$pid;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Inventory Restored Sucessfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Inventory storing failed!!!";
            }
            return $data;
        }
	
	
	}
	
	public function addInventory($inv_data){
		//var_dump($inv_data);die();
         if(is_null($inv_data->uid)){
			 $data['state']='ERR';
			 $data['msg']="You are Not Authorised";
            return $data;
        }
		if(is_null($inv_data->product_id)){
			 $data['state']='ERR';
			 $data['msg']="No Product selected yet";
            return $data;
        }
		else{
			$duplicate_check="SELECT * FROM inventory WHERE product_id=". $inv_data->product_id;
			$checking=$this->db->prepare($duplicate_check);
			$checking->execute();
			$checking->setFetchMode(PDO::FETCH_ASSOC);
			$result=$checking->fetchAll();
			if(count($result)>=1){
				$data['state']='ERR';
				$data['msg']="Product already in Inventory";
			}else{
				$sql="INSERT INTO inventory(product_id, added_by)VALUES('" . $inv_data->product_id . "', '" . $inv_data->uid ."')";
				//echo $sql; die();
				$query=$this->db->prepare($sql);
				$query->execute();
				if($query){
					$data['state']='OK';
					$data['msg']="Inventory successfully Created!!!";
				}else{
					$data['state']='ERR';
					$data['msg']="New Inventory Creation Failed!!!";
				}
			}
            return $data;
        }
	
	
	}
	
}

?>
