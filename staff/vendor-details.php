<?php
session_start();
require "../start.php";
use Shenole_project\models\Vendor;
use Shenole_project\models\Staff;
use Shenole_project\helpers\Validator;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\StaffRepository;
use Shenole_project\helpers\MyHelpers;

$isStaffLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'staff', new StaffRepository);

if(!$isStaffLoggedIn){
	header("Location: ".SITE_LINK_STAFF."login.php");
    exit();
}

if($_POST){
	$vendor_id = $_POST['vendor_id'];
	if(isset($_POST['vendor_detail_show_operation'])){
		$vendor = Vendor::find($vendor_id);
	}
	if(isset($_POST['vendor_login_operation'])){
		$vendor = Vendor::find($vendor_id);

		$generator = new RandomStringGenerator;
		$tokenLength = 60;
		$login_token = $generator->generate($tokenLength);

		$vendor->login_token = $login_token;
		$vendor->save();


		$before_login_session_array = isset($_SESSION['logged_in_as']) ? $_SESSION['logged_in_as'] : [];
		$_SESSION['logged_in_as'] = [];
		$login_session_array = [
			"logged_in_user_type" => 'vendor',
			"looged_in_token" => $vendor->login_token
		];

		$found = false;
		foreach($before_login_session_array as &$single){
			if($single['logged_in_user_type'] == 'vendor'){
				$found = true;
				$single = $login_session_array;
			}
			if($single['logged_in_user_type'] == 'client'){
				$single = [
					"logged_in_user_type" => 'client',
					"looged_in_token" => ''
				];
			}
		}
		if(!$found){
			array_push($before_login_session_array, $login_session_array);
		}

		$_SESSION['logged_in_as'] = $before_login_session_array;
		
		header("Location: ".SITE_LINK."vendors/dashboard.php");
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
		MyHelpers::includeWithVariables('../layouts/top_nav.php', ['isStaffLoggedIn' => $isStaffLoggedIn], $print = true);
		?>
		<div class="main-body-content">
            <section>
                <div class="content-container-center">
					<div class="fix half floatleft"><h1 class="fs_30 lh_40 font_bold text_dark_ash mb_10 pl_5 pr_5">Vendor Details</h1></div>
					<div class="fix half floatleft">
						<a class="display_inline_block lh_30 pl_16 pr_16 bg_very_light_ash2 text_dark_ash font_bold cursor_pointer mb_10 floatright mt_5" href="vendor-listing.php">View Vendors</a>
						<form method="post" class="floatright ml_10" target="_blank">
							<input type="hidden" name="vendor_login_operation" value="1"/>
							<input type="hidden" name="vendor_id" value="<?php echo $vendor->id; ?>"/>
							<button type="submit" class="fs_14 lh_28 w_200 textcenter display_inline_block font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer mr_5">Login As User</button>
						</form>
					</div>

					
					
					<div class="fix full pl_10 border_box">
						<div class="fix full mt_20">
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">First Name</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($vendor) ? $vendor->first_name : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">Last Name</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($vendor) ? $vendor->last_name : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">Email</p>
								<p class="fs_14 lh_22 text_dark_ash"><?php echo isset($vendor) ? $vendor->email : ''; ?></p>
							</div>
							<div class="fix floatleft half pr_16 border_box mn_h_80">
								<p class="fs_14 lh_22 text_dark_ash font_bold">Status</p>
								<?php if($vendor->account_status == 'A'){ ?>
								<p class="fs_14 lh_22 text_dark_ash">Active</p>
								<?php }elseif($vendor->account_status == 'I'){ ?>
								<p class="fs_14 lh_22 text_dark_ash">Inactive</p>
								<?php }elseif($vendor->account_status == 'S'){ ?>
								<p class="fs_14 lh_22 text_dark_ash">Suspended</p>
								<?php } ?>
							</div>
							<div class="fix full">
								<!-- <button type="submit" class="display_block fs_14 lh_30 w_100 textcenter font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer">Edit</button> -->
								
							</div>		
						</div>
					</div>
				</div>
			</section>
		</div>
		<?php 
        MyHelpers::includeWithVariables('../layouts/footer.php', [], $print = true);
        ?>
	</div>
	<script src="../js/jquery.min.js"></script>
</body>
</html>