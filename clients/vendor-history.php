<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Client;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\ClientRepository;

$isClientLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'client', new ClientRepository);

if(!$isClientLoggedIn){
	header("Location: ".SITE_LINK."login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Market Place</title>
	<link rel="stylesheet" href="../css/style.css">
</head>
<body>
	<div class="fix full div_mid">
		<?php include('layouts/client_panel_head.php'); ?>
		
		<div class="fix full">
			<?php include('layouts/client_panel_left_menu.php'); ?>	
			
			<div class="fix floatleft eighty_percent pt_10 pr_10 pb_10 pl_10 border_box">
				<div class="fix full">
					<div class="fix half floatleft"><h1 class="fs_30 lh_40 font_bold text_dark_ash mb_10 pl_5 pr_5">Vendor History</h1></div>
					<div class="fix half floatleft"></div>
					
					
				</div>
			</div>
		</div>
	</div>
	<script src="../js/jquery.min.js"></script>
	<script src="../js/pages/index_page.js"></script>
</body>
</html>