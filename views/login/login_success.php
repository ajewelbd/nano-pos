<?php
session_start();
require_once("../../vendor/autoload.php");

use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Auth\Auth;

$new_login = new Auth();
if (isset($_REQUEST['submit'])) {
    $new_login->loginPrepare($_REQUEST)->loginCheck();
} else {
    Message::set('<div class="alert alert-warning"><strong>Something is Wrong. Try Again!</strong></div>');
    Utility::redirect('login.php');
}
