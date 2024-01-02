<?php
namespace POS\Config;
use PDO;

class Connection {
    static public function db(){
		try{
			$db=new PDO('mysql:host=localhost;dbname=nano_pos', 'root', '');
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $db;
		}
		catch(PDOException $e)
			{
			echo "Database Connection failed: " . $e->getMessage();
			}
	}
}

?>
