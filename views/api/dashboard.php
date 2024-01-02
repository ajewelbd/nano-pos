<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once("../../vendor/autoload.php");

use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Dashboard\Dashboard;

if (array_key_exists('service', $_GET) && !empty($_GET['service'])) {
	$service = $_GET['service'];
	$content = new Dashboard();

	switch ($service) {
		case "daily_sales":
			$data = $content->dailySales();
			echo json_encode($data);
			break;
		case "header_widget":
			$data = $content->headerWidget();
			echo json_encode($data);
			break;
		case "inventory_hightlights":
			$type = $_GET['type'];
			if ($type == null) {
				return;
			}
			$data = $content->inventoryHightlights($type);
			echo json_encode($data);
			break;
		case "sales_by_date":
			if (isset($_GET["start"]) && isset($_GET["end"])) {
				$start_date = $_GET["start"];
				$end_date = $_GET["end"];

				if ($start_date && $end_date) {
					$data = $content->salesByDate($start_date, $end_date);
					echo json_encode($data);
				} else {
					echo json_encode(["status" => "Error", "msg" => "Invalid Parameter"]);
				}
			} else {
				echo json_encode(["status" => "Error", "msg" => "Invalid Parameter"]);
			}

			break;
		default:
			echo json_encode("Something Going Wrong!!!");
	}
} else {
	Utility::redirect(Config::BASE_URL);
}
