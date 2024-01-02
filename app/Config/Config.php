<?php
namespace POS\Config;
use PDO;

class Config {
    const APPNAME           = 'Nano poS';
    const DEVELEOPER        = '<a href="http://www.facebook.com/ebongbd">bAdBoY</a>';
    const HOSTNAME          = 'localhost';
    const DBNAME            = 'nano_pos';
    const DBUSER            = 'root';
    const DBPASS            = '';
    //const UPLOAD_DIR    = DIRECTORY_SEPARATOR."atomic13".DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR;
    const BASE_URL          = 'http://localhost/jshop/';
    const IS_SECURE         = FALSE;
    const DEBUG_MODE        = TRUE;
	//const TIMEZONE			= date_default_timezone_set('Asia/Dhaka');
    
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
