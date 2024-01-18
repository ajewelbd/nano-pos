<?php
session_start();
require_once("../../vendor/autoload.php");

use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Auth\Auth;

$logout = new Auth();
$logout_success = $logout->logout($_SESSION['logged_id'], $_SESSION['token']);
if ($logout_success) {
	session_destroy();
	$_SESSION['username'] = array();
	$_SESSION['id'] = array();
	Message::set('<div class="alert alert-warning"><strong>You are Successfully Logged out!</strong></div>');
	Utility::redirect('login');
} else {
	Message::set('<div class="alert alert-warning"><strong>Something is Wrong. Try Again!</strong></div>');
	Utility::redirect('login');
}
