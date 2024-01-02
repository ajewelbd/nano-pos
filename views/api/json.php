<?php
use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Products\Products;

$service=$_GET['service'];

$products=new Products();

if($service=='show_products'){
	show();
}




?>
