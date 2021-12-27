<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Client;
use Shenole_project\models\Vendor;
use Shenole_project\models\Staff;
use Shenole_project\models\Majestic;
use Shenole_project\utils\RandomStringGenerator;

if(isset($_SESSION['login_token']) && (Client::where('login_token', '=', $_SESSION['login_token'])->exists() || Vendor::where('login_token', '=', $_SESSION['login_token'])->exists() || Staff::where('login_token', '=', $_SESSION['login_token'])->exists() || Majestic::where('login_token', '=', $_SESSION['login_token'])->exists())){

}else{
	header("Location: ".SITE_LINK);
    exit();
}
