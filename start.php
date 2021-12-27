<?php
$SiteURL = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
/*
if(strpos($_SERVER['HTTP_HOST'], 'cassis')!==false) {
	define('LOCAL_FOLDER', '/avalign/');                # should be "/" when empty
} else {
	define('LOCAL_FOLDER', '/');                # should be "/" when empty
}
*/
define('LOCAL_FOLDER', '/upwork_projects/shenole_project/');
// define('LOCAL_FOLDER', '/');

define('MAJESTIC', 'majestic/');
define('STAFF', 'staff/');
define('CLIENT', 'clients/');
define('VENDOR', 'vendors/');
define('KNOWLEDGEBASE', 'knowledgebase/');

$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
if (isset($_SERVER['HTTPS']) &&
	($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
	isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
	$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
	$protocol = 'https://';
} else {
	$protocol = 'http://';
}

define('SITE_SERVER', $protocol . $_SERVER['HTTP_HOST']);
define('SITE_DOMAIN', preg_replace("/^(.*\.)?([^.]*\..*)$/", "$2", $_SERVER['HTTP_HOST']));
define('SITE_LINK', SITE_SERVER . '' . LOCAL_FOLDER);
define('SITE_LINK_DEFAULT', $protocol . '://www.' . SITE_DOMAIN . '/');
define('SITE_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], "/") . '' . LOCAL_FOLDER);
define('SITE_ROOT_MAJESTIC', rtrim($_SERVER['DOCUMENT_ROOT'], "/") . '' . LOCAL_FOLDER . MAJESTIC);
define('SITE_ROOT_STAFF', rtrim($_SERVER['DOCUMENT_ROOT'], "/") . '' . LOCAL_FOLDER . STAFF);
define('SITE_ROOT_CLIENT', rtrim($_SERVER['DOCUMENT_ROOT'], "/") . '' . LOCAL_FOLDER . CLIENT);
define('SITE_ROOT_VENDOR', rtrim($_SERVER['DOCUMENT_ROOT'], "/") . '' . LOCAL_FOLDER . VENDOR);
define('SITE_ROOT_KNOWLEDGEBASE', rtrim($_SERVER['DOCUMENT_ROOT'], "/") . '' . LOCAL_FOLDER . KNOWLEDGEBASE);
define('SITE_LINK_MAJESTIC', SITE_SERVER . '' . LOCAL_FOLDER . MAJESTIC);
define('SITE_LINK_STAFF', SITE_SERVER . '' . LOCAL_FOLDER . STAFF);
define('SITE_LINK_CLIENT', SITE_SERVER . '' . LOCAL_FOLDER . CLIENT);
define('SITE_LINK_VENDOR', SITE_SERVER . '' . LOCAL_FOLDER . VENDOR);
define('SITE_LINK_KNOWLEDGEBASE', SITE_SERVER . '' . LOCAL_FOLDER . KNOWLEDGEBASE);

require "vendor/autoload.php";

//If you want the errors to be shown
error_reporting(E_ALL); 
ini_set('display_errors', '1');

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
	"driver" => "mysql",
	"host" =>"127.0.0.1",
	"database" => "shenole_market_place",
	"username" => "root",
	"password" => ""
]);

// $capsule->addConnection([
// 	"driver" => "mysql",
// 	"host" =>"127.0.0.1",
// 	"database" => "eventonestop_primary" ,
// 	"username" => "eventonestop_majestic",
// 	"password" => "Z(?*E!pUfq5K"
// ]);

//Make this Capsule instance available globally.
$capsule->setAsGlobal();
// Setup the Eloquent ORM.
$capsule->bootEloquent();