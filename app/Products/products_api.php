<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once("../../vendor/autoload.php");
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Products\Products;

if(array_key_exists('service', $_GET)&&!empty($_GET['service'])){
	$service=$_GET['service'];
	$products=new Products();

	switch($service){
		case "show_products":
			$data=$products->show();
			echo json_encode($data);
			break;
		case "show_trashed_products":
			$data=$products->showTrash();
			echo json_encode($data);
			break;	
			
		case "add_products":
			$data = json_decode(file_get_contents("php://input"));
			$result=$products->prepare($data)->store();
			break;
		case "view_product":
			$id=$_GET['id'];
			if(is_null($id)){
            return;
			}
			$result=$products->view($id);
			echo json_encode($result);
			break;
		case "update_product":
			//$data=array();
			$data= json_decode(file_get_contents("php://input"));
			$pid=$_GET['id'];
			$result=$products->update($data, $pid);
			//$data="ok";
			echo json_encode($result);
			break;	
	
		case "soft_delete_product":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$pid=$data->pid;
			$data=$products->softDelete($pid);
			echo json_encode($data);
			break;
		case "restore_product":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$pid=$data->pid;
			$data=$products->restore($pid);
			echo json_encode($data);
			break;
		case "final_delete_product":
			$data = json_decode(file_get_contents("php://input"));
			//$id=$_GET['id'];
			$pid=$data->pid;
			$data=$products->finalDelete($pid);
			echo json_encode($data);
			break;	
		
		default:
			echo json_encode("Please go Back");
	}
}else{
	Utility::redirect(Config::BASE_URL);
}
?>
