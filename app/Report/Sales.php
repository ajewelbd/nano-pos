<?php
namespace POS\Sales;
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Config\Connection;
use POS\Inventory\Inventory;
use PDO;



class Sales{
    
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
	
    public function submit_order($orders_info){
		$inventory= new Inventory();
		//var_dump($orders_info);die();
		$products=$orders_info->products;
		$others=$orders_info->others;
		$due=$others->total_payable - $others->paid;
		$invoice='POS' . date('mdy').time().rand(1, 100);
		if(!empty($products) && is_array($products) && !empty($others)){
			$sql="INSERT INTO sales (invoice, customer_id, total, paid, due, payment_method, added_by)
			VALUES('" . $invoice . "', '" . $others->customer_id . "', '" . $others->total_payable . "', '" . $others->paid . "', '" . $due . "', '" . $others->payment_method . "', '" . $others->uid . "')";			
			//var_dump($sql);die();
			$sales_entry=$this->db->prepare($sql);
			$sales_entry->execute();
			$sales_id = $this->db->lastInsertId();
			if($sales_entry && count($products)>0){
				for($i=0; $i<count($products); $i++){
					//Inventory Update for product stock chenge
					
					$inventory_data=[
					'quantity'=>$products[$i]->quantity,
					'remarks'=>"SalesID #".$invoice,
					'updated_by'=>$others->uid,
					'type'=>2
					];
					//var_dump($inventory_data);die();
					$check_inventory=$inventory->changeQuantity($inventory_data, $products[$i]->inv_id);
					//var_dump($check_inventory);die();
					if($check_inventory['state']=='OK'){
						$sql="INSERT INTO orders (sales_id, product_id, quantity, sale_price, added_by)
						VALUES('" . $sales_id . "', '" . $products[$i]->id . "', '" . $products[$i]->quantity . "', '" . $products[$i]->sale_price . "', '" . $others->uid . "')";
						$product_entry=$this->db->prepare($sql);
						$product_entry->execute();
						if($product_entry){
							$data['state']="OK";
							$data['invoice']=$invoice;
							$data['msg']="Your Tranjaction is Succesfully completed.";
						}else{
							$product_entry->pdo->rollback();
							$data['state']="ERR";
							$data['msg']="Tranjaction Failed!!!";
						}						
					}else{
							$product_entry->pdo->rollback();
							$data['state']="ERR";
							$data['msg']="Tranjaction Failed!!!";
						}
					
				}
			}else{
				$data['state']="ERR";
				$data['msg']="Transaction Failed!!!";
			}		
			
		}
		else{		
			$data['state']="ERR";
			$data['msg']="Tranjaction Failed!!!";
		}
		return $data;
		
		
    }
    
    public function showSalesHistory(){
		$sql="SELECT * FROM sales WHERE active=1 ORDER BY created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		
		$result=$query->setFetchMode(PDO::FETCH_ASSOC);
		$sales=$query->fetchAll();
		return $sales;
    }
	
	public function soldProducts($sales_id){
		$sql="SELECT orders.product_id, orders.quantity, orders.sale_price, orders.created, sales.total, products.product_name, products.base_price, 
		customers.name FROM orders INNER JOIN sales 
		ON orders.sales_id=sales.id 
		INNER JOIN products ON orders.product_id=products.id 
		INNER JOIN customers ON sales.customer_id=customers.id WHERE sales.id=".$sales_id;
		$query=$this->db->prepare($sql);
		$query->execute();
		
		$result=$query->setFetchMode(PDO::FETCH_ASSOC);
		$inventory=array();
		return $inventory[]=$query->fetchAll();
		//return json_encode($suppliers);
    }
	
	
	public function showLogSingle($id){
		$sql="SELECT * FROM inventory_log WHERE inventory_id=".$id;
		$query=$this->db->prepare($sql);
		$query->execute();
		$inventory_log=$query->setFetchMode(PDO::FETCH_OBJ);
		$inventory_log=$query->fetchAll();
		return $inventory_log;
    }
	
	public function showLogAll(){
		$sql="SELECT * FROM inventory_log ORDER BY created";
		$query=$this->db->prepare($sql);
		$query->execute();
		$inventory_log=$query->setFetchMode(PDO::FETCH_ASSOC);
		$inventory_log=$query->fetchAll();
		return $inventory_log;
    }
	
	public function showTrash(){
		$sql="SELECT * FROM sales WHERE active=0 ORDER BY created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		
		$result=$query->setFetchMode(PDO::FETCH_ASSOC);
		$sales=$query->fetchAll();
		return $sales;
    }
   
	public function showProducts(){
		$sql="SELECT products.id, products.product_name, products.base_price, inventory.id AS inv_id, inventory.quantity FROM products INNER JOIN inventory ON products.id=inventory.product_id WHERE active=1";
		$query=$this->db->prepare($sql);
		$query->execute();
		$query->setFetchMode(PDO::FETCH_ASSOC);
		$products=$query->fetchAll();
		return $products;
    }
	
	public function getProducts(){
		$sql="SELECT id, product_name FROM products WHERE active=1 ORDER BY created DESC";
		$query=$this->db->prepare($sql);
		$query->execute();
		$products=$query->setFetchMode(PDO::FETCH_ASSOC);
		$products=$query->fetchAll();
		return $products;
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
    
    public function softDelete($sales_id){
         if(is_null($sales_id)){
			 $msg="No Sales selected yet";
            return $msg;
        }else{
            $sql="UPDATE sales SET active=0 WHERE id = " .$sales_id;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Sales Deleted Sucessfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Sales Delation Failed!!!";
            }
            return $data;
        }
	
	
	}
	
	public function restore($sales_id){
         if(is_null($sales_id)){
			 $msg="No Sales selected yet";
            return $msg;
        }else{
            $sql="UPDATE sales SET active=1 WHERE id = " .$sales_id;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Sales Restored Sucessfully!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Sales Restoring failed!!!";
            }
            return $data;
        }
	
	
	}
	public function finalDelete($sales_id){
         if(is_null($sales_id)){
			 $msg="No Sales selected yet";
            return $msg;
        }else{
            $sql="DELETE FROM sales WHERE id = " .$sales_id;
			//return $sql; die();
			$query=$this->db->prepare($sql);
			$query->execute();
            if($query){
				$data['state']='OK';
				$data['msg']="Sales information Permanently Deleted!!!";
            }else{
				$data['state']='ERR';
				$data['msg']="Sales information Deletion Failed!!!";
            }
            return $data;
        }
	
	
	}
	public function update_due($due_data){
		//var_dump($due_data);die();
         if(is_null($due_data->sales_id)){
			 $data['state']='ERR';
			 $data['msg']="Wrong Sales information!!!";
            return $data;
        }else{
			$sql="SELECT total, paid, due FROM sales WHERE id=".$due_data->sales_id;
			$query=$this->db->prepare($sql);
			$query->execute();
			$query->setFetchMode(PDO::FETCH_OBJ);
			$sales_info=$query->fetch();
			//var_dump($sales_info);die();
			if($query){	
				$updated_paid=$sales_info->paid + $due_data->ammount;
				$updated_due=$sales_info->total - $updated_paid;
				
				$sql="UPDATE sales set paid='".$updated_paid."', due='".$updated_due."' WHERE id=".$due_data->sales_id;
				$update=$this->db->prepare($sql);
				$update->execute();
				if($update){
					$data['state']='OK';
					$data['msg']="Sales Due Updated!!!";
				}else{
					$data['state']='ERR';
					$data['msg']="Failed to Updated Sales Due!!!!!!";
				}
			}else{
				$data['state']='ERR';
				$data['msg']="Failed to Updated Sales Due!!!!!!";
            }         
            return $data;
        }
	}
	
}

?>
