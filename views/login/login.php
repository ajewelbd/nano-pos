<?php
session_start();
require_once("../../vendor/autoload.php");

use POS\Message\Message;
use POS\Utility\Utility;
use POS\Config\Config;
use POS\Auth\Auth;

$new_login = new Auth();
$check = $new_login->is_loggedin();
if ($check) {
    Utility::redirect("../dashboard/index");
}
$current_year = date('Y');
if ($current_year == 2017) {
    $year = null;
} else {
    $year = '-' . $current_year;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login :: Nano poS</title>
    <link href="../../resources/css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
    <link rel="stylesheet" href="../../resources/css/jstyle.css">
    <link rel="shortcut icon" href="../../resources/img/favicon.ico">
</head>

<body>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <center>
                <h1>Nano poS<center>
                </h1>
            </center>
        </div>
        <div class="panel-body">
            <div class="row" id="pwd-container">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <section class="login-form">
                        <form method="post" action="login_success.php" role="login">
                            <img src="../../resources/img/login-logo.png" width="100" height="50" class="img-responsive login-logo" alt="" />
                            <div>
                                <?php
                                if ((array_key_exists('Message', $_SESSION)) && !empty($_SESSION['Message'])) {
                                    echo Message::flush();
                                }
                                ?>
                            </div>
                            <input type="text" name="username" value="admin" placeholder="Username" required class="form-control input-lg" required>
                            <br>
                            <input type="password" name="password" value="123456" class="form-control input-lg" id="password" placeholder="Password" required>
                            <br>
                            <button type="submit" name="submit" class="btn btn-lg btn-primary btn-block">Sign in</button>

                        </form>
                    </section>
                </div>
                <div class="col-md-4"></div>

            </div>

        </div>
        <center>
            <h4>Nano poS &copy; 2017<?php echo $year; ?> | <small>Developed by <?php echo Config::DEVELEOPER; ?></small></h4>
        </center>
    </div>
    <script src="../../resources/js/bootstrap.js"></script>
    <script src="../../resources/js/jquery.min.js"></script>
    <script>
        $('.alert').fadeOut(3000);
    </script>
</body>

</html>