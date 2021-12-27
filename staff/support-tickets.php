<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Client;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\StaffRepository;

$isStaffLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'staff', new StaffRepository);

if(!$isStaffLoggedIn){
	header("Location: ".SITE_LINK_STAFF."login.php");
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
		<?php include('layouts/staff_panel_head.php'); ?>
		
		<div class="fix full">
			<?php include('layouts/staff_panel_left_menu.php'); ?>	
			
			<div class="fix floatleft eighty_percent pt_10 pr_10 pb_10 pl_10 border_box">
				<div class="fix full">
					<div class="fix half floatleft"><h1 class="fs_30 lh_40 font_bold text_dark_ash mb_10 pl_5 pr_5">Support Tickets</h1></div>
					<div class="fix half floatleft">
						<!-- <a class="display_inline_block lh_30 pl_16 pr_16 bg_very_light_ash2 text_dark_ash font_bold cursor_pointer mb_10 floatright mt_5" href="client-add.php">Add Client</a> -->
					</div>
					
					
					
				</div>
			</div>
		</div>
	</div>
	<script src="../js/jquery.min.js"></script>
	<script src="../js/pages/index_page.js"></script>
</body>
</html>