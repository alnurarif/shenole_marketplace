<?php
session_start();
require "../start.php";
use Shenole_project\models\State;
use Shenole_project\models\Staff;
use Shenole_project\models\Client;
use Shenole_project\models\Vendor;
use Shenole_project\models\Majestic;
use Shenole_project\helpers\Validator;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\MajesticRepository;
use Shenole_project\helpers\MyHelpers;
use Shenole_project\models\Vendor_membership_level;

$isMajesticLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'majestic', new MajesticRepository);

if(!$isMajesticLoggedIn){
	header("Location: ".SITE_LINK_MAJESTIC."login.php");
    exit();
}

$errors = [
	'errors_number' => 0,
	'first_name' => '',
	'last_name' => '',
	'email' => '',	
	'password' => '',	
	'confirm_password' => ''
];
if($_POST){
	$vendor_id = $_POST['vendor_id'];
	if(isset($_POST['vendor_edit_operation'])){
		$vendor = Vendor::find($vendor_id);
		if($_POST['first_name'] == ""){
			$errors['errors_number'] += 1;
			$errors['first_name'] = 'This field cannot be empty.';
		}
		if($_POST['last_name'] == ""){
			$errors['errors_number'] += 1;
			$errors['last_name'] = 'This field cannot be empty.';
		}
		if($_POST['email'] == ""){
			$errors['errors_number'] += 1;
			$errors['email'] = 'This field cannot be empty.';
		}
		if(!Validator::isValidEmail($_POST['email'])){
			$errors['errors_number'] += 1;
			$errors['email'] = 'You have entered an invalid email address.';
		}
		if((Vendor::where('email', '=', $_POST['email'])->exists() || Vendor::where('email', '=', $_POST['email'])->exists() || Vendor::where('email', '=', $_POST['email'])->exists() || Majestic::where('email', '=', $_POST['email'])->exists()) && $vendor->email != $_POST['email']){
			$errors['errors_number'] += 1;
			$errors['email'] = 'This email is already in use.';
		}
		if($_POST['password'] == ""){
			$errors['errors_number'] += 1;
			$errors['password'] = 'This field cannot be empty.';
		}
		if(!Validator::isStrongPassword($_POST['password'], 8, 12)){
			$errors['errors_number'] += 1;
			$errors['password'] = 'Password must containt atleast 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character and it must be 8-12 characters long';
		}
		if((Vendor::where('password', '=', $_POST['password'])->exists() || Vendor::where('password', '=', $_POST['password'])->exists() || Vendor::where('password', '=', $_POST['password'])->exists() || Majestic::where('password', '=', $_POST['password'])->exists()) && $vendor->password != $_POST['password']){
			$errors['errors_number'] += 1;
			$errors['password'] = 'This password is already in use.';
		}
		if($_POST['password'] !== $_POST['confirm_password']){
			$errors['errors_number'] += 1;
			$errors['confirm_password'] = 'This password does not match.';	
		}

		$generator = new RandomStringGenerator;
		$tokenLength = 5;
		$random_string = $generator->generate($tokenLength);

		$vendor->first_name = $_POST['first_name'];
		$vendor->last_name = $_POST['last_name'];
		$vendor->email = $_POST['email'];
		$vendor->password = $_POST['password'];
		$vendor->account_status = $_POST['status'];
		$vendor->membership_level = $_POST['membership_level'];
		if($errors['errors_number'] == 0){
			$vendor->save();
			header("Location: ".SITE_LINK_MAJESTIC."vendor-listing.php");
			exit();
		}
	}
	if(isset($_POST['vendor_edit_show_operation'])){
		$vendor = Vendor::find($vendor_id);
	}
}
$membership_levels = Vendor_membership_level::get();


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
					<div class="fix half floatleft"><h1 class="fs_30 lh_40 font_bold text_dark_ash mb_10 pl_5 pr_5">Edit Vendor</h1></div>
					<div class="fix half floatleft"><a class="display_inline_block lh_30 pl_16 pr_16 bg_very_light_ash2 text_dark_ash font_bold cursor_pointer mb_10 floatright mt_5" href="vendor-listing.php">View Vendor</a></div>

					
					
					<div class="fix full pl_10 border_box">
						<div class="fix full mt_20">
							<form method="post">
								<input type="hidden" name="vendor_edit_operation" value="1">
								<input type="hidden" name="vendor_id" value="<?php echo isset($vendor->id) ? $vendor->id : ''; ?>">
								<div class="fix floatleft half pr_16 border_box mn_h_80">
									<p class="fs_14 lh_22 text_dark_ash font_bold">First Name
									<?php if($errors['first_name'] != ""){?>
										<span class="fs_12 lh_20 text_error ml_10"><?php echo $errors['first_name']; ?></span>
									<?php } ?>
									</p>
									<input
									type="text" 
									class="full h_30 bt_1 br_1 bb_1 bl_1 border_solid border_ash pl_5 pr_5 border_box" 
									name="first_name" 
									id="first_name" 
									value="<?php echo isset($vendor->first_name) ? $vendor->first_name : ''; ?>"
									/>
								</div>
								<div class="fix floatleft half pr_16 border_box mn_h_80">
									<p class="fs_14 lh_22 text_dark_ash font_bold">Last Name
									<?php if($errors['last_name'] != ""){?>
										<span class="fs_12 lh_20 text_error ml_10"><?php echo $errors['last_name']; ?></span>
									<?php } ?>
									</p>
									<input
									type="text" 
									class="full h_30 bt_1 br_1 bb_1 bl_1 border_solid border_ash pl_5 pr_5 border_box" 
									name="last_name" 
									id="last_name" 
									value="<?php echo isset($vendor->last_name) ? $vendor->last_name : ''; ?>"
									/>
								</div>
								
								
								<div class="fix floatleft half pr_16 border_box mn_h_80">
									<p class="fs_14 lh_22 text_dark_ash font_bold">Email
									<?php if($errors['email'] != ""){?>
										<span class="fs_12 lh_20 text_error ml_10"><?php echo $errors['email']; ?></span>
									<?php } ?>
									</p>
									<input
									type="text" 
									class="full h_30 bt_1 br_1 bb_1 bl_1 border_solid border_ash pl_5 pr_5 border_box" 
									name="email" 
									id="email" 
									value="<?php echo isset($vendor->email) ? $vendor->email : ''; ?>"
									/>
								</div>
								<div class="fix floatleft half pr_16 border_box mn_h_80">
									<p class="fs_14 lh_22 text_dark_ash font_bold">Password
									<?php if($errors['password'] != ""){?>
										<span class="fs_12 lh_20 text_error ml_10"><?php echo $errors['password']; ?></span>
									<?php } ?>
									</p>
									<input
									type="text" 
									class="full h_30 bt_1 br_1 bb_1 bl_1 border_solid border_ash pl_5 pr_5 border_box" 
									name="password" 
									id="password" 
									value="<?php echo isset($vendor->password) ? $vendor->password : ''; ?>"
									/>
								</div>
								<div class="fix floatleft half pr_16 border_box mn_h_80">
									<p class="fs_14 lh_22 text_dark_ash font_bold">Confirm Password
									<?php if($errors['confirm_password'] != ""){?>
										<span class="fs_12 lh_20 text_error ml_10"><?php echo $errors['confirm_password']; ?></span>
									<?php } ?>
									</p>
									<input
									type="text" 
									class="full h_30 bt_1 br_1 bb_1 bl_1 border_solid border_ash pl_5 pr_5 border_box" 
									name="confirm_password" 
									id="confirm_password" 
									value="<?php echo isset($_POST['confirm_password']) ? $_POST['confirm_password'] : ''; ?>"
									/>
								</div>
								<div class="fix floatleft half pr_16 border_box mn_h_80">
									<p class="fs_14 lh_22 text_dark_ash font_bold">Membership Level</p>
									<select 
									class="full h_30 bt_1 br_1 bb_1 bl_1 border_solid border_ash pl_5 pr_5 border_box" 
									name="membership_level" 
									id="membership_level" 
									>
										<?php foreach($membership_levels as $single_level){?>
											<option value="<?php echo $single_level->id; ?>" <?php echo (isset($vendor->membership_level) && $vendor->membership_level ==  $single_level->id) ? 'selected' : ''; ?>><?php echo $single_level->name; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="fix floatleft half pr_16 border_box mn_h_80">
									<p class="fs_14 lh_22 text_dark_ash font_bold">Status</p>
									<select 
									class="full h_30 bt_1 br_1 bb_1 bl_1 border_solid border_ash pl_5 pr_5 border_box" 
									name="status" 
									id="status" 
									>
										<option value="A" <?php echo (isset($vendor->account_status) && $vendor->account_status == 'A') ? 'selected' : ''; ?>>Active</option>
										<option value="I" <?php echo (isset($vendor->account_status) && $vendor->account_status == 'I') ? 'selected' : ''; ?>>Inactive</option>
										<option value="S" <?php echo (isset($vendor->account_status) && $vendor->account_status == 'S') ? 'selected' : ''; ?>>Suspended</option>
									</select>
								</div>
								<div class="fix full">
									<button type="submit" class="display_block fs_14 lh_30 w_100 textcenter font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer">Edit</button>
									
								</div>
								
							</form>
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