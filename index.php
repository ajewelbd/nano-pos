<?php
date_default_timezone_set('Asia/Dhaka');
session_start();
require_once("vendor/autoload.php");

use POS\Message\Message;
use POS\Utility\Utility;
use POS\Utility\Config;
use POS\Auth\Auth;

$new_login = new Auth();
$check = $new_login->is_loggedin();

try {
    file_get_contents("https://devjewel.xyz/api/info.php?service=visitor&site=nanopos");
} catch (Exception $e) {
}


if ($check) {
    Utility::redirect("views/dashboard/index");
} else {
    Utility::redirect("views/login/login");
}
