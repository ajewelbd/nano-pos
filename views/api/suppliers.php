<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once("../../vendor/autoload.php");
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Supplier\Supplier;

if(array_key_exists('service', $_GET)&&!empty($_GET['service'])){
	$service=$_GET['service'];
	$supplier=new Supplier();

	switch($service){
		case "show_suppliers":
			$data=$supplier->show();
			echo json_encode($data);
			break;
		case "show_trashed_suppliers":
			$data=$supplier->showTrash();
			echo json_encode($data);
			break;
		case "supplied_products":
			$id=$_GET['id'];
			if(is_null($id)){
            return;
			}
			$data=$supplier->suppliedProducts($id);
			echo json_encode($data);
			break;			
			
		case "add_supplier":
			$data = json_decode(file_get_contents("php://input"));
			$result=$supplier->prepare($data)->store();
			echo json_encode($result);
			break;
		case "view_supplier":
			$id=$_GET['id'];
			if(is_null($id)){
            return;
			}
			$result=$supplier->view($id);
			echo json_encode($result);
			break;
		case "update_supplier":
			//$data=array();
			$data= json_decode(file_get_contents("php://input"));
			$sid=$_GET['id'];
			$result=$supplier->update($data, $sid);
			//$data="ok";
			echo json_encode($result);
			break;	
	
		case "soft_delete_supplier":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$sid=$data->sid;
			$data=$supplier->softDelete($sid);
			echo json_encode($data);
			break;
		case "restore_supplier":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$sid=$data->sid;
			$data=$supplier->restore($sid);
			echo json_encode($data);
			break;
		case "final_delete_supplier":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$sid=$data->sid;
			$data=$supplier->finalDelete($sid);
			echo json_encode($data);
			break;
		case "bill_update":
			$data = json_decode(file_get_contents("php://input"));
			$bill_info=[			
					'type'=>'bill_pay',
					'supplier_id'=>$data->supplier_id,
					'inventory_id'=>0,
					'amount'=>$data->amount,
					'payment_type'=>$data->payment_type,
					'remarks'=>$data->remarks,
					'added_by'=>$data->added_by
				];
			$result=$supplier->billUpdate($bill_info);
			echo json_encode($result);
			break;
		case "supplier_bill_log":
			$data = json_decode(file_get_contents("php://input"));
			$sid=$data->sid;
			$data=$supplier->billLogs($sid);
			echo json_encode($data);
			break;			
		
		default:
			$data['state']='ERR';
			$data['msg']='Request Failed';
			echo json_encode($data);
	}
}else{
	Utility::redirect(Config::BASE_URL);
}
?>
