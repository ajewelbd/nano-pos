<?php
namespace POS\Supplier;
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Config\Connection;
use PDO;



class Supplier{
    
    public $id="";
    public $company_name="";
    public $owners_name="";
    public $address="";
    public $email="";
    public $added_by="";
	public $mobile="";
	protected $db;
    
    
    
   public function __construct(){
       $this->db=Connection::db();
        
    }
    public function prepare($data=array()){
		//Utility::dd($data);
			$this->added_by    			=   mysql_real_escape_string($data->uid);
            $this->company_name    		=   mysql_real_escape_string($data->company_name);
            $this->owners_name   		=   mysql_real_escape_string($data->owners_name);
            $this->address				=   mysql_real_escape_string($data->address);
            $this->email     			=   mysql_real_escape_string($data->email);
			$this->mobile     			=   mysql_real_escape_string($data->mobile);
        return $this;
    }
        
    public function store(){
        $sql="INSERT INTO suppliers(company_name, owners_name, address, email, mobile, added_by) 
                   VALUES('".$this->company_name."','".$this->owners_name."','".$this->address."','".$this->email."','".$this->mobile."','".$this->added_by."')";
        
        //Utility::dd($sql);
		$query=$this->db->prepare($sql);
        $query->execute();
        
        if($query){
			$data['state']='OK';
			$data['msg']="New Supplier Added Sucessfully!!!";
		}else{
			$data['state']='ERR';
			$data['msg']="New Supplier Adding Failed!!!";
		}
		return $data;
    }
    
    public function show(){
		/*$sql="SELECT suppliers.id, suppliers.compnay_name, suppliers.owners_name, suppliers.address, suppliers.mobile, 
		suppliers. email, suppliers.created, suppliers.updated, suppliers.added_by, suppliers.updated_by, users.first_name,
		users.last_name FROM suppliers INNER JOIN users on suppliers.id=users.id"; */
		$sql="SELECT * FROM suppliers WHERE active=1 ORDER BY created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		
		$result=$query->setFetchMode(PDO::FETCH_ASSOC);
		$suppliers=array();
		return $suppliers[]=$query->fetchAll();
		//return json_encode($suppliers);
    }
	public function showTrash(){
		$sql="SELECT * FROM suppliers WHERE active=0 ORDER BY created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		
		$result=$query->setFetchMode(PDO::FETCH_ASSOC);
		$suppliers=array();
		return $supliers[]=$query->fetchAll();
		//return json_encode($suppliers);
    }
   
	public function view($id){
		$sql="SELECT * FROM suppliers WHERE id=".$id;
		$query=$this->db->prepare($sql);
		$query->execute();
		$supplier=$query->setFetchMode(PDO::FETCH_OBJ);
		$supplier=$query->fetchAll();
		return $supplier;
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
	public function suppliedProducts($id){
		$sql="SELECT log.quantity, log.buy_price, log.added_by, log.created, products.product_name, user.first_name, user.last_name 
		FROM inventory_log AS log 
		INNER JOIN inventory ON inventory.id=log.inventory_id 
		INNER JOIN products ON products.id=inventory.product_id 
		INNER JOIN user ON user.id=log.added_by WHERE log.supplier_id=".$id;
		$query=$this->db->prepare($sql);
		$query->execute();
		$supplied=$query->setFetchMode(PDO::FETCH_OBJ);
		$supplied=$query->fetchAll();
		return $supplied;
		//Utility::dd($sql);
    }
    
    public function update($supplier_data, $id){
		//$data=array();
		$columns=array('id','updated_by','supplier_name', 'supplier_model', 'size', 'base_price');
		$colvalSet = '';
		$i = 0;
		foreach($supplier_data as $key=>$val){
			$pre = ($i > 0)?', ':'';
			$val = htmlspecialchars(strip_tags($val));
			$colvalSet .= $pre.$key."='".$val."'";
			$i++;
		}
		//echo $colvalSet;die();
		$sql="UPDATE suppliers SET " . $colvalSet . " WHERE id=" .$id;
		//echo $sql;die();
		$query=$this->db->prepare($sql);
		$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Supplier Updated Succesfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Supplier Update Failed!!!";
            }
            return $data;
		
			
    }
    
    public function softDelete($sid){
         if(is_null($sid)){
			 $msg="No supplier selected yet";
            return $msg;
        }else{
			$this->datetime=date('Y:m:d H:i:s');
            $sql="UPDATE suppliers SET active=0 WHERE id = " .$sid;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Supplier Deleted Sucessfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Supplier Delation Failed!!!";
            }
            return $data;
        }
	
	
	}
	
	public function restore($sid){
         if(is_null($sid)){
			 $msg="No supplier selected yet";
            return $msg;
        }else{
			$this->datetime=date('Y:m:d H:i:s');
            $sql="UPDATE suppliers SET active=1 WHERE id = " .$sid;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Supplier Restored Sucessfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Supplier storing failed!!!";
            }
            return $data;
        }
	
	
	}
	
	public function finalDelete($sid){
         if(is_null($sid)){
			 $msg="No supplier selected yet";
            return $msg;
        }else{
            $sql="DELETE FROM suppliers WHERE id = " .$sid;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Supplier Permanently Deleted!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Supplier Deletion Failed!!!";
            }
            return $data;
        }
	
	
	}
	public function billUpdate($bill_info=array()){
		$sql="SELECT total_bill, total_paid, total_due FROM suppliers WHERE id='".$bill_info['supplier_id']."' AND active=1 AND deleted IS NULL";
		//echo $sql;die();
		$query=$this->db->prepare($sql);
		$query->execute();
		$query->setFetchMode(PDO::FETCH_OBJ);
		$statement=$query->fetchAll();
		$new_total_bill='';
		$new_total_paid='';
		$new_total_due='';
		//$bill=$bill_info['quantity']*$bill_info['buy_price'];
		if($statement){
			if($bill_info['type']=='buy'){
				$new_total_bill=$statement[0]->total_bill + $bill_info['amount'];
				$new_total_paid=$statement[0]->total_paid;
				$new_total_due=$new_total_bill - $new_total_paid;
				
			}elseif($bill_info['type']=='bill_pay'){
				$new_total_bill=$statement[0]->total_bill;
				$new_total_paid=$statement[0]->total_paid + $bill_info['amount'];
				//$new_total_due=$satetment[0]->total_due - $bill_info['amount'];
				$new_total_due=$new_total_bill - $new_total_paid;
			}
			$sql="UPDATE suppliers set total_bill='".$new_total_bill."', total_paid='".$new_total_paid."', total_due='".$new_total_due."' WHERE id=".$bill_info['supplier_id'];
			$query=$this->db->prepare($sql);
			$update=$query->execute();
			if($update){
				$sql="INSERT INTO suppliers_bill_pay_log(supplier_id, inventory_id, amount, old_total_bill, updated_total_bill, old_total_paid, updated_total_paid, old_total_due, updated_total_due, payment_type, remarks, added_by) 
				VALUES('".$bill_info['supplier_id']."', '".$bill_info['inventory_id']."', '".$bill_info['amount']."', '".$statement[0]->total_bill."', '".$new_total_bill."', '".$statement[0]->total_paid."', '".$new_total_paid."', '".$statement[0]->total_due."', '".$new_total_due."', '".$bill_info['payment_type']."', '".$bill_info['remarks']."', '".$bill_info['added_by']."')";
				$query=$this->db->prepare($sql);
				$query->execute();
				if($query){
					$data['state']='OK';
					$data['msg']="Bill Payment Successfull!!!";
				}else{
					$update->pdo->rollback();
					$data['state']='ERR';
					$data['msg']="Bill Payment Failed!!!";
				}
			}else{
				$data['state']='ERR';
				$data['msg']="Bill Payment Failed!!!";
			}
			
		}else{
			$data['state']='ERR';
			$data['msg']="Supplier Yet Not REgistered!!!";
		}
		return $data;
	}
	public function billLogs($sid){
		$sql="SELECT * FROM suppliers_bill_pay_log WHERE supplier_id='".$sid."' AND active=1 AND deleted IS NULL";
		$query=$this->db->prepare($sql);
		$query->execute();
		$logs=$query->setFetchMode(PDO::FETCH_OBJ);
		$logs=$query->fetchAll();
		return $logs;
	}
	
}

?>
