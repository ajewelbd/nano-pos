<?php
session_start();
require_once("../../vendor/autoload.php");

use POS\Message\Message;
use POS\Utility\Utility;
use POS\Utility\Config;
use POS\Auth\Auth;

$new_login = new Auth();
$check = $new_login->is_loggedin();
$login_user = "";
$login_user = $_SESSION['username'];
if (!$check) {
    Message::set('<div class="alert alert-success"><strong>Please Login to View The Content!</strong></div>');
    Utility::redirect("../../index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $page_title; ?> || Nano poS</title>

    <!-- Bootstrap core CSS -->
    <link href="../../resources/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../resources/css/jsidebar.css" rel="stylesheet">
    <link href="../../resources/css/jstyle.css" rel="stylesheet">
    <script src="../../resources/js/jquery.min.js"></script>
    <!-- PDF Make -->
    <script src="../../resources/js_office/file_saver/FileSaver.min.js"></script>
    <script src="../../resources/js_office/js_xlsx/xlsx.core.min.js"></script>
    <script src="../../resources/js_office/pdf_make/pdfmake.min.js"></script>
    <script src="../../resources/js_office/pdf_make/vfs_fonts.js"></script>
    <script src="../../resources/js_office/js_pdf/jspdf.min.js"></script>
    <script src="../../resources/js_office/js_pdf_autotable/jspdf.plugin.autotable.js"></script>
    <script src="../../resources/js_office/table_export/tableexport.min.js"></script>

    <!-- Select2 -->
    <link href="../../resources/js/select2/select2.min.css" rel="stylesheet" />
    <script src="../../resources/js/select2//select2.min.js"></script>

    <script src="../../resources/js/angular.min.js"></script>
    <script src="../../resources/app/main.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="../../resources/datatables/datatables.bootstrap.min.css">

    <link rel="shortcut icon" href="../../resources/img/favicon.ico">


</head>

<body ng-app="jShop">

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        Print Surface
                    </a>
                </li>
                <li>
                    <a href="../dashboard/index"><span class="glyphicon glyphicon-dashboard"> Dashboard</a>
                </li>
                <li>
                    <a href="../products/products"><span class="glyphicon glyphicon-leaf"> Products</a>
                </li>
                <li>
                    <a href="../sales/sales"><span class="glyphicon glyphicon-send"> Sales</a>
                </li>
                <li>
                    <a href="../customers/customers"><span class="glyphicon glyphicon-user"> Customer</a>
                </li>
                <li>
                    <a href="../suppliers/suppliers"><span class="glyphicon glyphicon-list-alt"> Suppliers</a>
                </li>
                <li>
                    <a href="../inventory/inventory"><span class="glyphicon glyphicon-list-alt"> Inventory</a>
                </li>
                <li>
                    <a href="../report/report"><span class="glyphicon glyphicon-equalizer"> Report</a>
                </li>
                <!-- <li>
                    <a href="#"><span class="glyphicon glyphicon-cog"> Settings</a>
                </li> -->
                <li>
                    <a href="../login/logout"><span class="glyphicon glyphicon-log-out"> Logout</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrPOSer -->
        <nav class="navbar navbar-inverse" style="border-radius: 0px; background: #000;">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="#menu-toggle" class="btn btn-secondary" id="menu-toggle"><span style="font-size:30px;">&#9776;</span></a>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="../sales/sales"><span class="glyphicon glyphicon-shopping-cart"></span> Sales</a></li>
                    <li><a href="../sales/sales_history"><span class="glyphicon glyphicon-shopping-cart"></span> Sales History</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['username']; ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <!-- <li>
                                <a href="#"><span class="glyphicon glyphicon-equalizer"> Profile</a>
                            </li>
                            <li>
                                <a href="#"><span class="glyphicon glyphicon-wrench"> Settings</a>
                            </li> -->
                            <li>
                                <a href="../login/logout"><span class="glyphicon glyphicon-log-out"> Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Page Content -->
        <div class="container">