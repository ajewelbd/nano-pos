<?php
session_start();
require_once("../../vendor/autoload.php");
use APP\Message\Message;
use APP\Utility\Utility;
use APP\Utility\Config;
use APP\Auth\Auth;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo Config::APPNAME;?> Register</title>
        <link href="../../assets/css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
        <link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
        <link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Montserrat:400,700'>
        <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
        <link rel="stylesheet" href="../../assets/css/custom.css">
    </head>
    <body>
        <div class="panel panel-primary">
        <div class="panel-heading"><center><h1>App Creation<center></h1></div>
        <div class="panel-body">
            <div class="row" id="pwd-container">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <section class="login-form">
                        <form method="post" action="../register/register_success.php" role="login">
                            <img src="../../assets/img/reg3.png" width="100" height="50"class="img-responsive" alt="Register Here" />
                            <div class="row">
                                <div class="col-md-6">
				  <div class="form-group">
					<input type="text" name="firstname" class="form-control" id="usr" placeholder="First Name" required>
				  </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="lastname" class="form-control" id="usr" placeholder="Last Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="address1" class="form-control" id="usr" placeholder="Adress Line 1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="address2" class="form-control" id="usr" placeholder="Adress Line 2">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="city" class="form-control" id="usr" placeholder="City" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="state" class="form-control">
                                            <option selected>Select State</option>
                                            <option value="Alabama">Alabama</option>
                                            <option value="Alaska">Alaska</option>
                                            <option value="Arizona">Arizona</option>
                                            <option value="Arkansas">Arkansas</option>
                                            <option value="California">California</option>
                                            <option value="Colorado">Colorado</option>
                                            <option value="Connecticut">Connecticut</option>
                                            <option value="Delaware">Delaware</option>
                                            <option value="District Of Columbia">District Of Columbia</option>
                                            <option value="Florida">Florida</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Hawaii">Hawaii</option>
                                            <option value="Idaho">Idaho</option>
                                            <option value="Illinois">Illinois</option>
                                            <option value="Indiana">Indiana</option>
                                            <option value="Iowa">Iowa</option>
                                            <option value="Kansas">Kansas</option>
                                            <option value="Kentucky">Kentucky</option>
                                            <option value="Louisiana">Louisiana</option>
                                            <option value="Maine">Maine</option>
                                            <option value="Maryland">Maryland</option>
                                            <option value="Massachusetts">Massachusetts</option>
                                            <option value="Michigan">Michigan</option>
                                            <option value="Minnesota">Minnesota</option>
                                            <option value="Mississippi">Mississippi</option>
                                            <option value="Missouri">Missouri</option>
                                            <option value="Montana">Montana</option>
                                            <option value="Nebraska">Nebraska</option>
                                            <option value="Nevada">Nevada</option>
                                            <option value="New Hampshire">New Hampshire</option>
                                            <option value="New Jersey">New Jersey</option>
                                            <option value="New Mexico">New Mexico</option>
                                            <option value="New York">New York</option>
                                            <option value="North Carolina">North Carolina</option>
                                            <option value="North Dakota">North Dakota</option>
                                            <option value="Ohio">Ohio</option>
                                            <option value="Oklahoma">Oklahoma</option>
                                            <option value="Oregon">Oregon</option>
                                            <option value="Pennsylvania">Pennsylvania</option>
                                            <option value="Rhode Island">Rhode Island</option>
                                            <option value="South Carolina">South Carolina</option>
                                            <option value="South Dakota">South Dakota</option>
                                            <option value="Tennessee">Tennessee</option>
                                            <option value="Texas">Texas</option>
                                            <option value="Utah">Utah</option>
                                            <option value="Vermont">Vermont</option>
                                            <option value="Virginia">Virginia</option>
                                            <option value="Washington">Washington</option>
                                            <option value="West Virginia">West Virginia</option>
                                            <option value="Wisconsin">Wisconsin</option>
                                            <option value="Wyoming">Wyoming</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="well well-sm">Card Type</div>
                                <label class="radio-inline"><input type="radio" name="card_type" value="VISA Card"><span class="card1">VISA Card</span></label>
                                <label class="radio-inline"><input type="radio" name="card_type" value="Master Card"><span class="card2">Master Card</span></label>
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-credit-card"></span></span>
                                <input type="tel" name="card_number" class="form-control" placeholder="Credit Card Number" required>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" name="expire" class="form-control" id="usr" placeholder="MM/YY" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="cvc" class="form-control" id="usr" placeholder="CVC Code">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-earphone"></span></span>
                                    <input type="tel" name="mobile" placeholder="Mobile Number" class="form-control" required />
                                 </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-envelope"></span></span>
                                    <input type="email" name="email" placeholder="Email" class="form-control" required />
                                 </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-user"></span></span>
                                    <input type="text" name="username" placeholder="Username" class="form-control" required />
                                 </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-lock"></span></span>
                                    <input type="password" name="password" placeholder="Password" class="form-control" required />
                                 </div>
                            </div>
                            <label class="checkbox-inline"><input type="checkbox" value="accept" required>I Accept the Company term and condition</label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="reset" class="btn btn-lg btn-danger btn-block">Reset</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="submit" name="submit" class="btn btn-lg btn-primary btn-block">Register</button>
                                </div>
                            </div>
                            <hr>
                            <div>
                                <a href="index.php">Go Home</a> or <a href="../login/login.php">Log In</a>
                            </div>
                        </form>
                    </section>
                </div>
                <div class="col-md-4"></div>
                
            </div>
            
        </div>
        <center><h4>APP Creation &copy; 2016 | <small>Design by <?php echo Config::DEVELEOPER;?></small></h4></center>
        </div>
        <script src="../../assets/js/index.js"></script>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    </body>
</html>
