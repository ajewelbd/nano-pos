<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once("../../vendor/autoload.php");
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Customer\Customer;

if(array_key_exists('service', $_GET)&&!empty($_GET['service'])){
	$service=$_GET['service'];
	$customer=new Customer();

	switch($service){
		case "get_customers":
			$data=$customer->getCustomers();
			echo json_encode($data);
			break;
			
		case "show_customers":
			$data=$customer->show();
			echo json_encode($data);
			break;
			
		case "show_trashed_customers":
			$data=$customer->showTrash();
			echo json_encode($data);
			break;	
			
		case "add_customer":
			$data = json_decode(file_get_contents("php://input"));
			$result=$customer->prepare($data)->store();
			echo json_encode($result);
			break;
		case "view_customer":
			$id=$_GET['id'];
			if(is_null($id)){
            return;
			}
			$result=$customer->view($id);
			echo json_encode($result);
			break;
		case "update_customer":
			//$data=array();
			$data= json_decode(file_get_contents("php://input"));
			$cid=$_GET['id'];
			$result=$customer->update($data, $cid);
			//$data="ok";
			echo json_encode($result);
			break;	
	
		case "soft_delete_customer":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$cid=$data->cid;
			$data=$customer->softDelete($cid);
			echo json_encode($data);
			break;
		case "restore_customer":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$cid=$data->cid;
			$data=$customer->restore($cid);
			echo json_encode($data);
			break;
		case "final_delete_customer":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$cid=$data->cid;
			$data=$customer->finalDelete($cid);
			echo json_encode($data);
			break;	
		case "show_transaction":
			$data = json_decode(file_get_contents("php://input"));
			$result=$customer->showTransaction($data);
			echo json_encode($result);
			break;
		case "payment_update":
			$data = json_decode(file_get_contents("php://input"));
			$payment_data=['type'=>'balance_update', 'entry_type'=>$data->entry_type, 'customer_id'=>$data->customer_id, 'amount'=>$data->amount, 'paid'=>$data->amount, 'remarks'=>$data->remarks, 'added_by'=>$_SESSION['id']];
			//var_dump($payment_data);die();
			$result=$customer->customerPaymentUpdate($payment_data);
			echo json_encode($result);
			break;
		case "show_single_payment_hsitory":
			$customer_id = json_decode(file_get_contents("php://input"));
			$result=$customer->singlePaymentHistory($customer_id);
			echo json_encode($result);
			break;
		default:
			echo json_encode("Please go Back");
	}
}else{
	Utility::redirect(Config::BASE_URL);
}
?>
