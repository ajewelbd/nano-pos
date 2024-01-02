<?php
namespace POS\Customer;
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Config\Connection;
use PDO;



class Customer{
    
    public $id="";
    public $name="";
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
            $this->name    				=   mysql_real_escape_string($data->name);
            $this->owners_name   		=   mysql_real_escape_string($data->owners_name);
            $this->address				=   mysql_real_escape_string($data->address);
            $this->email     			=   mysql_real_escape_string($data->email);
			$this->mobile     			=   mysql_real_escape_string($data->mobile);
        return $this;
    }
	
    public function store(){
        $sql="INSERT INTO customers(name, owners_name, address, email, mobile, added_by) 
                   VALUES('".$this->name."','".$this->owners_name."','".$this->address."','".$this->email."','".$this->mobile."','".$this->added_by."')";
        
        //Utility::dd($sql);
		$query=$this->db->prepare($sql);
        $query->execute();
		$id = $this->db->lastInsertId();
        
        if($query){
			$data['customer_id']=$id;
			$data['state']='OK';
			$data['msg']="New Customer Added Sucessfully!!!";
		}else{
			$data['state']='ERR';
			$data['msg']="New Customer Adding Failed!!!";
		}
		return $data;
    }
    
    public function show(){
		$sql="SELECT * FROM customers WHERE active=1 ORDER BY created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		
		$result=$query->setFetchMode(PDO::FETCH_ASSOC);
		$customer=$query->fetchAll();
		return $customer;
    }
	
	public function getCustomers(){
		$sql="SELECT * FROM customers WHERE active=1 AND deleted IS NULL ORDER BY created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		
		$query->setFetchMode(PDO::FETCH_ASSOC);
		$customer=$query->fetchAll();
		
		/*for($i=0; $i<count($customer); $i++){
			$sql="SELECT SUM(due) AS dues FROM sales WHERE customer_id=".$customer[$i]['id'];
			$query=$this->db->prepare($sql);
			$query->execute();
			$query->setFetchMode(PDO::FETCH_OBJ);
			$result=$query->fetch();
			$customer[$i]['dues']=$result->dues;
		}*/
		return $customer;
    }
	
	public function showTrash(){
		$sql="SELECT * FROM customers WHERE active=0 ORDER BY created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		
		$result=$query->setFetchMode(PDO::FETCH_ASSOC);
		$customer=$query->fetchAll();
		return $customer;
		//return json_encode($customer);
    }
   
	public function view($id){
		//$sql="SELECT * FROM customers WHERE id=".$id;
		$sql="SELECT customers.id, customers.name, customers.owners_name, customers.address, customers.email, customers.mobile, customers.created,
		(SELECT first_name FROM user WHERE id=customers.added_by) AS added_first_name,
        (SELECT last_name FROM user WHERE id=customers.added_by) AS added_last_name,
		(SELECT first_name FROM user WHERE id=customers.updated_by) AS updated_first_name,
        (SELECT last_name FROM user WHERE id=customers.updated_by) AS updated_last_name FROM customers WHERE customers.id=".$id;
		$query=$this->db->prepare($sql);
		$query->execute();
		$customer=$query->setFetchMode(PDO::FETCH_OBJ);
		$customer=$query->fetchAll();
		return $customer[0];
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
    
    public function update($customer_data, $id){
		//var_dump($customer_data);die();
		//$data=array();
		//$columns=array('id','updated_by','product_name', 'product_model', 'size', 'base_price');
		$colvalSet = '';
		$i = 0;
		foreach($customer_data as $key=>$val){
			$pre = ($i > 0)?', ':'';
			$val = htmlspecialchars(strip_tags($val));
			$colvalSet .= $pre.$key."='".$val."'";
			$i++;
		}
		//echo $colvalSet;die();
		$sql="UPDATE customers SET " . $colvalSet ." WHERE id=" .$id;
		//echo $sql;die();
		$query=$this->db->prepare($sql);
		$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Customer Updated Succesfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Customer Update Failed!!!";
            }
            return $data;
		
			
    }
    
    public function softDelete($cid){
         if(is_null($cid)){
			$data['msg']="No Customer selected yet";
            return $data;
        }else{
            $sql="UPDATE customers SET active=0 WHERE id = " .$cid;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Customer Deleted Sucessfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Customer Delation Failed!!!";
            }
            return $data;
        }
	
	
	}
	
	public function restore($cid){
         if(is_null($cid)){
			$data['msg']="No Customer selected yet";
            return $data;
        }else{
            $sql="UPDATE customers SET active=1 WHERE id = " .$cid;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Customer Restored Sucessfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Customer Restoring failed!!!";
            }
            return $data;
        }
	
	
	}
	
	public function finalDelete($cid){
         if(is_null($cid)){
			$data['msg']="No Customer selected yet";
            return $data;
        }else{
            $sql="DELETE FROM customers WHERE id = " .$cid;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Customer Permanently Deleted!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Customer Deletion Failed!!!";
            }
            return $data;
        }
	
	
	}
	public function showTransaction($cid){
		//var_dump($cid);die();
         if(is_null($cid)){
			$data['state']='ERR';
			$data['msg']="No Customer selected yet";
            return $data;
        }else{
            $sql="SELECT sales.invoice, sales.total, sales.paid, sales.due, sales.payment_method, sales.created, user.first_name, user.last_name FROM sales 
			INNER JOIN user ON user.id=sales.added_by WHERE sales.customer_id = " .$cid;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$transactions=$query->fetchAll();
			//var_dump($transactions);die();
            if($query){
				$data['state']='OK';
				$data['transactions']=$transactions;
            }else{
				$data['state']='ERR';
				$data['msg']="Something Wrong!!!";
            }
			return $data;
        }
	}
	public function singlePaymentHistory($cid){
		//var_dump($cid);die();
         if(is_null($cid)){
			$data['state']='ERR';
			$data['msg']="No Customer selected yet";
            return $data;
        }else{
            $sql="SELECT log.id, log.amount, log.old_total_expense, log.updated_total_expense, log.old_total_paid, log.updated_total_paid, log.old_total_due, log.updated_total_due, log.remarks, log.created, user.first_name, user.last_name FROM customers_payment_log AS log
			INNER JOIN user ON user.id=log.added_by WHERE log.customer_id=".$cid;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
			$query->setFetchMode(PDO::FETCH_ASSOC);
			$payments=$query->fetchAll();
			//var_dump($transactions);die();
            if($query){
				$data['state']='OK';
				$data['payments_log']=$payments;
            }else{
				$data['state']='ERR';
				$data['msg']="Something Wrong!!!";
            }
			return $data;
        }
	}
	public function customerPaymentUpdate($payment_info=array()){
		$sql="SELECT total_expense, total_paid, total_due FROM customers WHERE id='".$payment_info['customer_id']."' AND active=1 AND deleted IS NULL";
		$query=$this->db->prepare($sql);
		$query->execute();
		$statement=$query->setFetchMode(PDO::FETCH_OBJ);
		$statement=$query->fetchAll();
		$new_total_expense='';
		$new_total_paid='';
		$new_total_due='';
		if($statement){
			if($payment_info['type']=='sale'){
				$new_total_expense=$statement[0]->total_expense + $payment_info['amount'];
			}elseif($payment_info['type']=='balance_update'){
				$new_total_expense=$statement[0]->total_expense;
			}
			if($payment_info['entry_type']==1 && $statement[0]->total_due>=0){
				$new_total_paid=$statement[0]->total_paid + $payment_info['paid'];
				$new_total_due=$new_total_expense - $new_total_paid;
			}elseif($payment_info['entry_type']==1 && $statement[0]->total_due<0){
				if($payment_info['type']=='sale'){
					$new_total_paid=$statement[0]->total_paid + $payment_info['paid'];
					$new_total_due=$statement[0]->total_due + $payment_info['amount'];
				}else{
					$new_total_paid=$statement[0]->total_paid + $payment_info['amount'];
					$new_total_due=$statement[0]->total_due - $payment_info['paid'];
				}				
			}elseif($payment_info['entry_type']==0){
				$new_total_paid=$statement[0]->total_paid - $payment_info['paid'];
				$new_total_due=$statement[0]->total_due + $payment_info['paid'];
			}
			
			$query="UPDATE customers set total_expense='".$new_total_expense."', total_paid='".$new_total_paid."', total_due='".$new_total_due."' WHERE id=".$payment_info['customer_id'];
			$update=$this->db->prepare($query);
			$update->execute();
			if($update){
				$payment_log="INSERT INTO customers_payment_log(customer_id, amount, old_total_expense, updated_total_expense, old_total_paid, updated_total_paid, old_total_due, updated_total_due, remarks, added_by) 
				VALUES('".$payment_info['customer_id']."', '".$payment_info['amount']."', '".$statement[0]->total_expense."', '".$new_total_expense."', '".$statement[0]->total_paid."', '".$new_total_paid."', '".$statement[0]->total_due."', '".$new_total_due."', '".$payment_info['remarks']."', '".$payment_info['added_by']."')";
				$insert=$this->db->prepare($payment_log);
				$insert->execute();
				if($insert){
					$data['state']="OK";
					$data['msg']="Payment Successfully Updated!!!";
				}else{
					$update->pdo->rollback();
					$data['state']="ERR";
					$data['msg']="Payment Update Failed!!!";
				}
			}else{
				$data['state']="ERR";
				$data['msg']="Payment Update Failed!!!";
			}
		}else{
			$data['state']="ERR";
			$data['msg']="Customer is not Regsitered!!!";
		}
		return $data;
		
		
	}
	
}

?>
