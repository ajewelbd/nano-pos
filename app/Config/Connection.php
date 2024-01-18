<?php

namespace POS\Config;

use PDO;

class Connection
{
	static public function db()
	{
		$env = parse_ini_file("../../.env");

		$host = $env["HOST"];
		$username = $env["USERNAME"];
		$password = $env["PASSWORD"];
		$db = $env["DB"];

		try {
			$db = new PDO("mysql:host=$host;dbname=$db", $username, $password);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $db;
		} catch (PDOException $e) {
			echo "Database Connection failed: " . $e->getMessage();
		}
	}
}
