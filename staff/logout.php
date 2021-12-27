<?php
session_start();
require "../start.php";
// unset($_SESSION['logged_in_as']);
$before_login_session_array = isset($_SESSION['logged_in_as']) ? $_SESSION['logged_in_as'] : [];
$_SESSION['logged_in_as'] = [];
$login_session_array = [
	"logged_in_user_type" => 'staff',
	"looged_in_token" => ''
];

$found = false;
foreach($before_login_session_array as &$single){
	if($single['logged_in_user_type'] == 'staff'){
		$found = true;
		$single = $login_session_array;
	}
}
if(!$found){
	array_push($before_login_session_array, $login_session_array);
}

$_SESSION['logged_in_as'] = $before_login_session_array;

header("Location: ".SITE_LINK_STAFF."login.php");
exit();