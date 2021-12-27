<?php
session_start();
require "../start.php";
use Shenole_project\models\Client;
use Shenole_project\models\Staff;
use Shenole_project\helpers\Validator;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\StaffRepository;

$isStaffLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'staff', new StaffRepository);

if(!$isStaffLoggedIn){
	header("Location: ".SITE_LINK_STAFF."login.php");
    exit();
}

if($_POST){
	$client_id = $_POST['client_id'];
	if(isset($_POST['client_detail_show_operation'])){
		$client = Client::find($client_id);
	}
	if(isset($_POST['client_login_operation'])){
		$client = Client::find($client_id);

		$generator = new RandomStringGenerator;
		$tokenLength = 60;
		$login_token = $generator->generate($tokenLength);

		$client->login_token = $login_token;
		$client->save();


		$before_login_session_array = isset($_SESSION['logged_in_as']) ? $_SESSION['logged_in_as'] : [];
		$_SESSION['logged_in_as'] = [];
		$login_session_array = [
			"logged_in_user_type" => 'client',
			"looged_in_token" => $client->login_token
		];

		$found = false;
		foreach($before_login_session_array as &$single){
			if($single['logged_in_user_type'] == 'client'){
				$found = true;
				$single = $login_session_array;
			}
			if($single['logged_in_user_type'] == 'vendor'){
				$single = [
					"logged_in_user_type" => 'vendor',
					"looged_in_token" => ''
				];
			}
		}
		if(!$found){
			array_push($before_login_session_array, $login_session_array);
		}

		$_SESSION['logged_in_as'] = $before_login_session_array;
		
		header("Location: ".SITE_LINK."clients/dashboard.php");
    	exit();
	}
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
					<div class="fix half floatleft"><h1 class="fs_30 lh_40 font_bold text_dark_ash mb_10 pl_5 pr_5">Client Details</h1></div>
					<div class="fix half floatleft">
						<a class="display_inline_block lh_30 pl_16 pr_16 bg_very_light_ash2 text_dark_ash font_bold cursor_pointer mb_10 floatright mt_5" href="client-listing.php">View Clients</a>
						<form method="post" class="floatright ml_10" target="_blank">
							<input type="hidden" name="client_login_operation" value="1"/>
							<input type="hidden" name="client_id" value="<?php echo $client->id; ?>"/>
							<button type="submit" class="fs_14 lh_28 w_200 textcenter display_inline_block font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer mr_5">Login As User</button>
						</form>
					</div>

					
					
					<div class="fix full pl_10 border_box">
						<div class="fix full mt_20">
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">First Name</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($client) ? $client->first_name : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">Last Name</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($client) ? $client->last_name : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">State</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($client->state) ? $client->state->name : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">City</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($client) ? $client->location_city : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">Zip Code</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($client) ? $client->location_zip_code : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">Email</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($client) ? $client->email : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">Status</p>
								<?php if($client->account_status == 'A'){ ?>
								<p class="fs_14 lh_22 text_dark_ash">Active</p>
								<?php }elseif($client->account_status == 'I'){ ?>
								<p class="fs_14 lh_22 text_dark_ash">Inactive</p>
								<?php }elseif($client->account_status == 'S'){ ?>
								<p class="fs_14 lh_22 text_dark_ash">Suspended</p>
								<?php } ?>
							</div>
							<div class="fix full">
								<!-- <button type="submit" class="display_block fs_14 lh_30 w_100 textcenter font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer">Edit</button> -->
								
							</div>		
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="../js/jquery.min.js"></script>
</body>
</html>