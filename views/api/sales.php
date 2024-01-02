<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once("../../vendor/autoload.php");
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Sales\Sales;

if(array_key_exists('service', $_GET)&&!empty($_GET['service'])){
	$service=$_GET['service'];
	$sales=new Sales();

	switch($service){
		case "show_sales":
			$data=$sales->show();
			echo json_encode($data);
			break;
		case "sold_products_list":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$sales_id=$data->sales_id;
			$data=$sales->soldProducts($sales_id);
			echo json_encode($data);
			break;
			
		case "show_trashed_sales":
			$data=$sales->showTrash();
			echo json_encode($data);
			break;	
			
		case "submit_order":
			$orders_info = json_decode(file_get_contents("php://input"));
			$result=$sales->submit_order($orders_info);
			echo json_encode($result);
			break;
		case "view_sales":
			$id=$_GET['id'];
			if(is_null($id)){
            return;
			}
			$result=$sales->view($id);
			echo json_encode($result);
			break;
		case "show_products":
			$result=$sales->showProducts();
			//$data="ok";
			echo json_encode($result);
			break;
			
		case "show_sales_history":
			$result=$sales->showSalesHistory();
			//$data="ok";
			echo json_encode($result);
			break;
			
		case "get_products":
			$result=$sales->getProducts();
			//$data="ok";
			echo json_encode($result);
			break;	
	
		case "soft_delete_sales":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$sales_id=$data->sales_id;
			$data=$sales->softDelete($sales_id);
			echo json_encode($data);
			break;
		case "restore_sales":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$sales_id=$data->sales_id;
			$data=$sales->restore($sales_id);
			echo json_encode($data);
			break;
		case "final_delete_sales":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$sales_id=$data->sales_id;
			$data=$sales->finalDelete($sales_id);
			echo json_encode($data);
			break;
		case "update_due":
			$data = json_decode(file_get_contents("php://input"));
			$result=$sales->update_due($data);
			echo json_encode($result);
			break;
		
		default:
			echo json_encode("Something Going Wrong!!!");
	}
}else{
	Utility::redirect(Config::BASE_URL);
}
?>
