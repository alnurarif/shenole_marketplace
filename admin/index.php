<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Majestic;
use Shenole_project\models\Staff;
use Shenole_project\repositories\MajesticRepository;
use Shenole_project\repositories\StaffRepository;
use Shenole_project\helpers\UserHelper;
use Shenole_project\helpers\MyHelpers;
use Shenole_project\utils\RandomStringGenerator;

$isMajesticLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'majestic', new MajesticRepository);
$isStaffLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'staff', new StaffRepository);

if($isMajesticLoggedIn){
	header("Location: ".SITE_LINK_MAJESTIC."dashboard.php");
    exit();
}
if($isStaffLoggedIn){
	header("Location: ".SITE_LINK_STAFF."dashboard.php");
    exit();	
}

header("Location: ".SITE_LINK."admin/login.php");
exit();	