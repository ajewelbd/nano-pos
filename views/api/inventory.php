<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once("../../vendor/autoload.php");
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Inventory\Inventory;

if(array_key_exists('service', $_GET)&&!empty($_GET['service'])){
	$service=$_GET['service'];
	$inventory=new Inventory();

	switch($service){
		case "show_inventory":
			$data=$inventory->show();
			echo json_encode($data);
			break;
		case "change_quantity":
			$data=json_decode(file_get_contents("php://input"));
			$inv_id=$_GET['id'];
			$result=$inventory->changeQuantity($data, $inv_id);
			echo json_encode($result);
			break;
			
		case "show_trashed_inventorys":
			$data=$inventory->showTrash();
			echo json_encode($data);
			break;	
			
		case "add_inventory":
			$data = json_decode(file_get_contents("php://input"));
			$result=$inventory->addInventory($data);
			echo json_encode($result);
			break;
		case "view_inventory":
			$id=$_GET['id'];
			if(is_null($id)){
            return;
			}
			$result=$inventory->view($id);
			echo json_encode($result);
			break;
		case "show_log_single":
			//$data=array();
			$data= json_decode(file_get_contents("php://input"));
			$inv_id=$_GET['id'];
			$result=$inventory->showLogSingle($inv_id);
			//$data="ok";
			echo json_encode($result);
			break;
			
		case "show_log_all":
			$result=$inventory->showLogAll();
			//$data="ok";
			echo json_encode($result);
			break;
			
		case "get_products":
			$result=$inventory->getProducts();
			//$data="ok";
			echo json_encode($result);
			break;	
	
		case "soft_delete_inventory":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$inv_id=$data->inv_id;
			$data=$inventory->softDelete($inv_id);
			echo json_encode($data);
			break;
		case "restore_inventory":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$inv_id=$data->inv_id;
			$data=$inventory->restore($inv_id);
			echo json_encode($data);
			break;
		case "final_delete_inventory":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$inv_id=$data->inv_id;
			$data=$inventory->finalDelete($inv_id);
			echo json_encode($data);
			break;	
		
		default:
			echo json_encode("Please go Back");
	}
}else{
	Utility::redirect(Config::BASE_URL);
}
?>
