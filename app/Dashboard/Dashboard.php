<?php

namespace POS\Dashboard;

use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Config\Connection;
use PDO;



class Dashboard
{

	public $id = "";
	public $product_id = "";
	public $quantity = "";
	public $added_by = "";
	public $updated_by = "";
	public $datetime = "";
	protected $db;



	public function __construct()
	{
		$this->db = Connection::db();
		date_default_timezone_set("Asia/Dhaka");
	}

	public function dailySales()
	{
		$date = Date('Y-m-d');
		$sql = "SELECT * FROM sales WHERE active=1 AND created LIKE '%" . $date . "%' ORDER BY created DESC";
		$query = $this->db->prepare($sql);
		$query->execute();

		$query->setFetchMode(PDO::FETCH_ASSOC);
		$sales = $query->fetchAll();
		return $sales;
	}

	public function salesByDate($start_date, $end_date)
	{
		$sql = "SELECT DATE(created) as date, ROUND(SUM(total), 2) AS total FROM sales WHERE active = 1 AND DATE(created) BETWEEN '$start_date' AND '$end_date' GROUP BY created";
		$query = $this->db->prepare($sql);
		$query->execute();

		$query->setFetchMode(PDO::FETCH_ASSOC);
		$sales = $query->fetchAll();
		return $sales;
	}

	public function headerWidget()
	{
		$widget = [];
		//Product Widget
		$sql = "SELECT (SELECT count(id) FROM products WHERE active=1 AND deleted IS NULL) AS total_products, 
		(SELECT SUM(quantity) FROM inventory WHERE active=1 AND deleted IS NULL) AS in_inventory, 
		(SELECT SUM(quantity) FROM orders WHERE active=1 AND deleted IS NULL) AS total_sold";
		$query = $this->db->prepare($sql);
		$query->execute();

		$query->setFetchMode(PDO::FETCH_OBJ);
		$products = $query->fetchAll();
		$widget['products'] = $products[0];

		//Customer Widget
		$sql = "SELECT (SELECT count(id) FROM customers WHERE active=1 AND deleted IS NULL) AS total_customer, 
		(SELECT ROUND(SUM(total), 2) FROM sales WHERE active=1 AND deleted IS NULL) AS spend, 
		(SELECT ROUND(SUM(paid), 2) FROM sales WHERE active=1 AND deleted IS NULL) AS paid";
		$query = $this->db->prepare($sql);
		$query->execute();

		$query->setFetchMode(PDO::FETCH_OBJ);
		$customer = $query->fetchAll();
		$widget['customer'] = $customer[0];

		//Suppliers Widget
		$sql = "SELECT (SELECT count(id) FROM suppliers WHERE active=1 AND deleted IS NULL) AS total_suppliers, 
		(SELECT SUM(quantity) FROM inventory_log WHERE supplier_id>0 ANd active=1 AND deleted IS NULL AND inventory_type=1) AS added, 
		(SELECT SUM(quantity) FROM inventory_log WHERE supplier_id>0 ANd active=1 AND deleted IS NULL AND inventory_type=0) AS removed";
		$query = $this->db->prepare($sql);
		$query->execute();

		$query->setFetchMode(PDO::FETCH_OBJ);
		$suppliers = $query->fetchAll();
		$widget['suppliers'] = $suppliers[0];

		//Sales Widget
		$sql = "SELECT count(id) AS total_sales, ROUND(SUM(paid), 2) AS paid, ROUND(SUM(due), 2) AS due FROM sales WHERE active=1 AND deleted IS NULL";
		$query = $this->db->prepare($sql);
		$query->execute();

		$query->setFetchMode(PDO::FETCH_OBJ);
		$sales = $query->fetchAll();
		$widget['sales'] = $sales[0];

		return $widget;
	}
	public function inventoryHightlights($type)
	{
		$products;
		if ($type == 'lowest_inventory') {
			/*$sql="SELECT inventory.quantity, inventory_log.updated_quantity, products.product_name FROM inventory 
			INNER JOIN inventory_log ON inventory_log.inventory_id=inventory.id 
			INNER JOIN products ON products.id=inventory.product_id 
			WHERE inventory.active=1 AND inventory.deleted IS NULL AND inventory.quantity<50 ORDER BY inventory.quantity ASC LIMIT 5";*/
			$sql = "SELECT id, product_id, quantity FROM inventory WHERE active=1 AND deleted IS NULL ORDER BY quantity ASC LIMIT 5";
			$query = $this->db->prepare($sql);
			$query->execute();

			$result = $query->setFetchMode(PDO::FETCH_ASSOC);
			$products = $query->fetchAll();
			for ($i = 0; $i < count($products); $i++) {
				$sql = "SELECT (SELECT product_name FROM products WHERE id='" . $products[$i]['product_id'] . "' AND active=1 AND deleted IS NULL) AS product_name,
				(SELECT DISTINCT updated_quantity FROM inventory_log WHERE active=1 AND deleted IS NULL AND inventory_id='" . $products[$i]['id'] . "' ORDER BY updated_quantity ASC LIMIT 1) AS updated_quantity";
				$query = $this->db->prepare($sql);
				$query->execute();

				$result = $query->setFetchMode(PDO::FETCH_OBJ);
				$result = $query->fetchAll();
				//var_dump($result);die();
				$products[$i]['product_name'] = $result[0]->product_name;
				$products[$i]['updated_quantity'] = $result[0]->updated_quantity;
			}
			//var_dump($products);die();
			return $products;
		} elseif ($type == 'highest_sold') {
			$sql = "SELECT (SELECT product_name FROM products WHERE id=inventory.product_id AND active=1 AND deleted IS NULL) AS product_name, (SELECT SUM(quantity) quantity FROM inventory_log WHERE inventory_id=inventory.id AND inventory_type=2 AND active=1 AND deleted IS NULL) as quantity FROM inventory WHERE active=1 AND deleted IS NULL ORDER BY quantity DESC LIMIT 5";
			$query = $this->db->prepare($sql);
			$query->execute();

			$result = $query->setFetchMode(PDO::FETCH_ASSOC);
			$products = $query->fetchAll();
			return $products;
		} elseif ($type == 'lowest_sold') {
			$sql = "SELECT (SELECT product_name FROM products WHERE id=inventory.product_id AND active=1 AND deleted IS NULL) AS product_name, (SELECT SUM(quantity) FROM inventory_log WHERE inventory_id=inventory.id AND inventory_type=2 AND active=1 AND deleted IS NULL) as quantity FROM inventory WHERE active=1 AND deleted IS NULL ORDER BY quantity ASC LIMIT 5";
			$query = $this->db->prepare($sql);
			$query->execute();

			$result = $query->setFetchMode(PDO::FETCH_ASSOC);
			$products = $query->fetchAll();
			return $products;
		}
	}
}
