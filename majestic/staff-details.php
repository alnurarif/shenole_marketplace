<?php
session_start();
require "../start.php";
use Shenole_project\models\Staff;
use Shenole_project\models\Majestic;
use Shenole_project\helpers\Validator;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\MajesticRepository;
use Shenole_project\helpers\MyHelpers;

$isMajesticLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'majestic', new MajesticRepository);

if(!$isMajesticLoggedIn){
	header("Location: ".SITE_LINK_MAJESTIC."login.php");
    exit();
}

if($_POST){
	$staff_id = $_POST['staff_id'];
	if(isset($_POST['staff_detail_show_operation'])){
		$staff = Staff::find($staff_id);
	}
	if(isset($_POST['staff_login_operation'])){
		$staff = Staff::find($staff_id);

		$generator = new RandomStringGenerator;
		$tokenLength = 60;
		$login_token = $generator->generate($tokenLength);

		$staff->login_token = $login_token;
		$staff->save();


		$before_login_session_array = isset($_SESSION['logged_in_as']) ? $_SESSION['logged_in_as'] : [];
		$_SESSION['logged_in_as'] = [];
		$login_session_array = [
			"logged_in_user_type" => 'staff',
			"looged_in_token" => $staff->login_token
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
		
		header("Location: ".SITE_LINK."staff/dashboard.php");
    	exit();
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<?php MyHelpers::includeWithVariables('../layouts/head_section.php', [], $print = true); ?>
<body>
	<div class="genesis-container">
		<?php 
		MyHelpers::includeWithVariables('../layouts/top_nav.php', ['isMajesticLoggedIn' => $isMajesticLoggedIn], $print = true);
		?>
		<div class="main-body-content">
            <section>
                <div class="content-container-center">
					<div class="fix half floatleft"><h1 class="fs_30 lh_40 font_bold text_dark_ash mb_10 pl_5 pr_5">Staff Details</h1></div>
					<div class="fix half floatleft">
						<a class="display_inline_block lh_30 pl_16 pr_16 bg_very_light_ash2 text_dark_ash font_bold cursor_pointer mb_10 floatright mt_5" href="staff-listing.php">View Staff</a>
						<form method="post" class="floatright ml_10" target="_blank">
							<input type="hidden" name="staff_login_operation" value="1"/>
							<input type="hidden" name="staff_id" value="<?php echo $staff->id; ?>"/>
							<button type="submit" class="fs_14 lh_28 w_200 textcenter display_inline_block font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer mr_5">Login As User</button>
						</form>
					</div>

					
					
					<div class="fix full pl_10 border_box">
						<div class="fix full mt_20">
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">First Name</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($staff) ? $staff->first_name : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">Last Name</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($staff) ? $staff->last_name : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">State</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($staff) ? $staff->state->name : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">City</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($staff) ? $staff->location_city : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">Zip Code</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($staff) ? $staff->location_zip_code : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">Email</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($staff) ? $staff->email : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">Status</p>
								<?php if($staff->account_status == 'A'){ ?>
								<p class="fs_14 lh_22 text_dark_ash">Active</p>
								<?php }elseif($staff->account_status == 'A'){ ?>
								<p class="fs_14 lh_22 text_dark_ash">Inactive</p>
								<?php }elseif($staff->account_status == 'S'){ ?>
								<p class="fs_14 lh_22 text_dark_ash">Suspended</p>
								<?php } ?>
							</div>
									
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	<script src="../js/jquery.min.js"></script>
</body>
</html>