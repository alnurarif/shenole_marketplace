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

$isMajesticLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'majestic', new MajesticRepository);

if(!$isMajesticLoggedIn){
	header("Location: ".SITE_LINK_MAJESTIC."login.php");
    exit();
}

$errors = [
	'errors_number' => 0,
	'first_name' => '',
	'last_name' => '',
	'location_city' => '',
	'location_zip_code' => '',
	'email' => '',	
	'password' => '',	
	'confirm_password' => ''
];
if($_POST){
	$staff_id = $_POST['staff_id'];
	if(isset($_POST['staff_edit_operation'])){
		$staff = Staff::find($staff_id);
		if($_POST['first_name'] == ""){
			$errors['errors_number'] += 1;
			$errors['first_name'] = 'This field cannot be empty.';
		}
		if($_POST['last_name'] == ""){
			$errors['errors_number'] += 1;
			$errors['last_name'] = 'This field cannot be empty.';
		}
		if($_POST['location_city'] == ""){
			$errors['errors_number'] += 1;
			$errors['location_city'] = 'This field cannot be empty.';
		}
		if($_POST['location_zip_code'] == ""){
			$errors['errors_number'] += 1;
			$errors['location_zip_code'] = 'This field cannot be empty.';
		}
		if($_POST['email'] == ""){
			$errors['errors_number'] += 1;
			$errors['email'] = 'This field cannot be empty.';
		}
		if(!Validator::isValidEmail($_POST['email'])){
			$errors['errors_number'] += 1;
			$errors['email'] = 'You have entered an invalid email address.';
		}
		if((Client::where('email', '=', $_POST['email'])->exists() || Vendor::where('email', '=', $_POST['email'])->exists() || Staff::where('email', '=', $_POST['email'])->exists() || Majestic::where('email', '=', $_POST['email'])->exists()) && $staff->email != $_POST['email']){
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
		if((Client::where('password', '=', $_POST['password'])->exists() || Vendor::where('password', '=', $_POST['password'])->exists() || Staff::where('password', '=', $_POST['password'])->exists() || Majestic::where('password', '=', $_POST['password'])->exists()) && $staff->password != $_POST['password']){
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

		$staff->first_name = $_POST['first_name'];
		$staff->last_name = $_POST['last_name'];
		$staff->state_id = $_POST['state_id'];
		$staff->location_city = $_POST['location_city'];
		$staff->location_zip_code = $_POST['location_zip_code'];
		$staff->email = $_POST['email'];
		$staff->password = $_POST['password'];
		$staff->account_status = $_POST['status'];
		if($errors['errors_number'] == 0){
			$staff->save();
			header("Location: ".SITE_LINK_MAJESTIC."staff-listing.php");
			exit();
		}
	}
	if(isset($_POST['staff_edit_show_operation'])){
		$staff = Staff::find($staff_id);
	}
}
$states = State::get();

$show_state_options = '';
foreach($states as $state){
	$show_state_options .= (isset($staff->state_id) && $staff->state_id == $state->id) ? '<option value="'.$state->id.'" selected>'.$state->short_name.'</option>' : '<option value="'.$state->id.'">'.$state->short_name.'</option>';
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
					<div class="fix half floatleft"><h1 class="fs_30 lh_40 font_bold text_dark_ash mb_10 pl_5 pr_5">Edit Staff</h1></div>
					<div class="fix half floatleft"><a class="display_inline_block lh_30 pl_16 pr_16 bg_very_light_ash2 text_dark_ash font_bold cursor_pointer mb_10 floatright mt_5" href="staff-listing.php">View Staff</a></div>

					
					
					<div class="fix full pl_10 border_box">
						<div class="fix full mt_20">
							<form method="post">
								<input type="hidden" name="staff_edit_operation" value="1">
								<input type="hidden" name="staff_id" value="<?php echo isset($staff->id) ? $staff->id : ''; ?>">
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
									value="<?php echo isset($staff->first_name) ? $staff->first_name : ''; ?>"
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
									value="<?php echo isset($staff->last_name) ? $staff->last_name : ''; ?>"
									/>
								</div>
								<div class="fix floatleft half pr_16 border_box mn_h_80">
									<p class="fs_14 lh_22 text_dark_ash font_bold">State</p>
									<select 
									class="full h_30 bt_1 br_1 bb_1 bl_1 border_solid border_ash pl_5 pr_5 border_box" 
									name="state_id" 
									id="state_id" 
									>
										<?php echo $show_state_options; ?>
									</select>
									
								</div>
								<div class="fix floatleft half pr_16 border_box mn_h_80">
									<p class="fs_14 lh_22 text_dark_ash font_bold">City
									<?php if($errors['location_city'] != ""){?>
										<span class="fs_12 lh_20 text_error ml_10"><?php echo $errors['location_city']; ?></span>
									<?php } ?>
									</p>
									<input
									type="text" 
									class="full h_30 bt_1 br_1 bb_1 bl_1 border_solid border_ash pl_5 pr_5 border_box" 
									name="location_city" 
									id="location_city" 
									value="<?php echo isset($staff->location_city) ? $staff->location_city : ''; ?>"
									/>
								</div>
								<div class="fix floatleft half pr_16 border_box mn_h_80">
									<p class="fs_14 lh_22 text_dark_ash font_bold">Zip Code
									<?php if($errors['location_zip_code'] != ""){?>
										<span class="fs_12 lh_20 text_error ml_10"><?php echo $errors['location_zip_code']; ?></span>
									<?php } ?>
									</p>
									<input
									type="text" 
									class="full h_30 bt_1 br_1 bb_1 bl_1 border_solid border_ash pl_5 pr_5 border_box" 
									name="location_zip_code" 
									id="location_zip_code" 
									value="<?php echo isset($staff->location_zip_code) ? $staff->location_zip_code : ''; ?>"
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
									value="<?php echo isset($staff->email) ? $staff->email : ''; ?>"
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
									value="<?php echo isset($staff->password) ? $staff->password : ''; ?>"
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
									<p class="fs_14 lh_22 text_dark_ash font_bold">Status</p>
									<select 
									class="full h_30 bt_1 br_1 bb_1 bl_1 border_solid border_ash pl_5 pr_5 border_box" 
									name="status" 
									id="status" 
									>
										<option value="A" <?php echo (isset($staff->account_status) && $staff->account_status == 'A') ? 'selected' : ''; ?>>Active</option>
										<option value="I" <?php echo (isset($staff->account_status) && $staff->account_status == 'I') ? 'selected' : ''; ?>>Inactive</option>
										<option value="S" <?php echo (isset($staff->account_status) && $staff->account_status == 'S') ? 'selected' : ''; ?>>Suspended</option>
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
	</div>
	<script src="../js/jquery.min.js"></script>
</body>
</html>