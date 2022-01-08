<?php
session_start();
require "start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Client;
use Shenole_project\models\Vendor;
use Shenole_project\repositories\ClientRepository;
use Shenole_project\repositories\VendorRepository;
use Shenole_project\repositories\StaffRepository;
use Shenole_project\repositories\MajesticRepository;
use Shenole_project\helpers\UserHelper;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\MyHelpers;

$isClientLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'client', new ClientRepository);
$isVendorLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'vendor', new VendorRepository);
$isStaffLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'staff', new StaffRepository);
$isMajesticLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'majestic', new MajesticRepository);


$client_errors = [
	'errors_number' => 0,
	'email' => '',	
	'password' => '',	
	'confirm_password' => '',	
	'terms_of_services' => ''	
];
$vendor_errors = [
	'errors_number' => 0,
	'email' => '',	
	'password' => '',	
	'confirm_password' => '',	
	'terms_of_services' => ''
];
if(isset($_POST['client_signup'])){
	if($_POST['client_email'] == ""){
		$client_errors['errors_number'] += 1;
		$client_errors['email'] = 'This field cannot be empty.';
	}
	if(!Validator::isValidEmail($_POST['client_email'])){
		$client_errors['errors_number'] += 1;
		$client_errors['email'] = 'You have entered an invalid email address.';
	}
	if(Client::where('email', '=', $_POST['client_email'])->exists() || Vendor::where('email', '=', $_POST['client_email'])->exists()){
		$client_errors['errors_number'] += 1;
		$client_errors['email'] = 'This email is already in use.';
	}
	if($_POST['client_password'] == ""){
		$client_errors['errors_number'] += 1;
		$client_errors['password'] = 'This field cannot be empty.';
	}
	if(!Validator::isStrongPassword($_POST['client_password'], 8, 12)){
		$client_errors['errors_number'] += 1;
		$client_errors['password'] = 'Password must containt atleast 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character and it must be 8-12 characters long';
	}
	if(Client::where('password', '=', $_POST['client_password'])->exists() || Vendor::where('password', '=', $_POST['client_password'])->exists()){
		$client_errors['errors_number'] += 1;
		$client_errors['password'] = 'This password is already in use.';
	}
	if($_POST['client_password'] !== $_POST['client_confirm_password']){
		$client_errors['errors_number'] += 1;
		$client_errors['confirm_password'] = 'This password does not match.';	
	}

	if(!isset($_POST['client_terms_of_services'])){
		$client_errors['errors_number'] += 1;
		$client_errors['terms_of_services'] = 'You must agree to the Terms of Service Agreement to have an account.';	
	}

	if($client_errors['errors_number'] == 0){
		// Create new instance of generator class.
		$generator = new RandomStringGenerator;
		$tokenLength = 5;
		$random_string = $generator->generate($tokenLength);


		$client = new Client;
		$client->uuid = 'client_'.date("Ymdhis").'_'.$random_string;
		$client->email = $_POST['client_email'];
		$client->password = $_POST['client_password'];
		$client->terms_of_service = 'I Agree';
		$client->save();

		header("Location: login.php");
    	exit();
	}
}
if(isset($_POST['vendor_signup'])){
	if($_POST['vendor_email'] == ""){
		$vendor_errors['errors_number'] += 1;
		$vendor_errors['email'] = 'This field cannot be empty.';
	}
	if(!Validator::isValidEmail($_POST['vendor_email'])){
		$vendor_errors['errors_number'] += 1;
		$vendor_errors['email'] = 'You have entered an invalid email address.';
	}
	if(Vendor::where('email', '=', $_POST['vendor_email'])->exists() || Client::where('email', '=', $_POST['vendor_email'])->exists()){
		$vendor_errors['errors_number'] += 1;
		$vendor_errors['email'] = 'This email is already in use.';
	}
	if($_POST['vendor_password'] == ""){
		$vendor_errors['errors_number'] += 1;
		$vendor_errors['password'] = 'This field cannot be empty.';
	}
	if(!Validator::isStrongPassword($_POST['vendor_password'], 8, 12)){
		$vendor_errors['errors_number'] += 1;
		$vendor_errors['password'] = 'Password must containt atleast 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character and it must be 8-12 characters long';
	}
	if(Vendor::where('password', '=', $_POST['vendor_password'])->exists() || Client::where('password', '=', $_POST['vendor_password'])->exists()){
		$vendor_errors['errors_number'] += 1;
		$vendor_errors['password'] = 'This password is already in use.';
	}
	if($_POST['vendor_password'] !== $_POST['vendor_confirm_password']){
		$vendor_errors['errors_number'] += 1;
		$vendor_errors['confirm_password'] = 'This password does not match.';	
	}

	if(!isset($_POST['vendor_terms_of_services'])){
		$vendor_errors['errors_number'] += 1;
		$vendor_errors['terms_of_services'] = 'You must agree to the Terms of Service Agreement to have an account.';	
	}

	if($vendor_errors['errors_number'] == 0){
		// Create new instance of generator class.
		$generator = new RandomStringGenerator;
		$tokenLength = 5;
		$random_string = $generator->generate($tokenLength);

		$vendor = new Vendor;
		$vendor->uuid = 'vendor_'.date("Ymdhis").'_'.$random_string;
		$vendor->email = $_POST['vendor_email'];
		$vendor->password = $_POST['vendor_password'];
		$vendor->terms_of_service = 'I Agree';
		$vendor->save();

		header("Location: login.php");
    	exit();
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<?php MyHelpers::includeWithVariables('./layouts/head_section.php', [], $print = true); ?>
<body>
	<div class="genesis-container">
		<?php 
		MyHelpers::includeWithVariables('./layouts/top_nav.php', ['isClientLoggedIn' => $isClientLoggedIn, 'isVendorLoggedIn' => $isVendorLoggedIn, 'isStaffLoggedIn' => $isStaffLoggedIn, 'isMajesticLoggedIn' => $isMajesticLoggedIn], $print = true);
		?>
		<div class="fix full container dual-signup-container">
			<div class="fix ninty_five_percent div_mid mt_160">
				<div class="fix floatleft half">
					<div class="fix seventy_percent div_mid" id="client-signup-section">
						<p class="fs_20 lh_30 text_dark_ash font_bold">Client Singup</p>
						<form action="signup.php" method="post">
							<div class="fix full mt_5 mb_5">
								<input type="hidden" name="client_signup" value="1"/>
							</div>
							<div class="fix full mt_5 mb_5">
								<p class="fs_14 lh_20 text_dark_ash font_bold">Email
								<?php if($client_errors['email'] != ""){?>
									<span class="fs_12 lh_20 text_error ml_10"><?php echo $client_errors['email']; ?></span>
								<?php } ?>
								</p>
								<input class="h_30 full border_box" type="email" name="client_email" value="<?php if(isset($_POST['client_email'])){ echo $_POST['client_email']; } ?>" placeholder="Enter Email" />
							</div>
							<div class="fix full mt_5 mb_5">
								<p class="fs_14 lh_20 text_dark_ash font_bold">Password
								<?php if($client_errors['password'] != ""){?>
									<span class="fs_12 lh_20 text_error ml_10"><?php echo $client_errors['password']; ?></span>
								<?php } ?>
								</p>
								<input class="h_30 full border_box" type="password" name="client_password" value="<?php if(isset($_POST['client_password'])){ echo $_POST['client_password']; } ?>" placeholder="Enter Password"/>
							</div>
							<div class="fix full mt_5 mb_5">
								<p class="fs_14 lh_20 text_dark_ash font_bold">Confirm Password
								<?php if($client_errors['confirm_password'] != ""){?>
									<span class="fs_12 lh_20 text_error ml_10"><?php echo $client_errors['confirm_password']; ?></span>
								<?php } ?>
								</p>
								<input class="h_30 full border_box" type="password" name="client_confirm_password" value="<?php if(isset($_POST['client_confirm_password'])){ echo $_POST['client_confirm_password']; } ?>" placeholder="Enter Confirm Password"/>
							</div>
							<div class="fix full mt_5 mb_5">
								<p class="fs_14 lh_20 text_dark_ash font_bold">
								<?php if($client_errors['terms_of_services'] != ""){?>
									<span class="fs_12 lh_20 text_error"><?php echo $client_errors['terms_of_services']; ?></span>
								<?php } ?>
								</p>
								<input type="checkbox" name="client_terms_of_services" value="1">
								<label for="vehicle1"> Accept Terms of Service</label>
							</div>
							<div class="fix full">
								<button type="submit">Register</button>
								<a href="login.php" class="bg_none text_blue ml_10 font_bold">Login</a>
							</div>
						</form>
					</div>
				</div>
				<div class="fix floatleft half">
					<div class="fix seventy_percent div_mid" id="vendor-signup-section">
						<form action="signup.php" method="post">
							<p class="fs_20 lh_30 text_dark_ash font_bold">Vendor Singup</p>
							<div class="fix full mt_5 mb_5">
								<input type="hidden" name="vendor_signup" value="1"/>
							</div>
							<div class="fix full mt_5 mb_5">
								<p class="fs_14 lh_20 text_dark_ash font_bold">Email
								<?php if($vendor_errors['email'] != ""){?>
									<span class="fs_12 lh_20 text_error ml_10"><?php echo $vendor_errors['email']; ?></span>
								<?php } ?>
								</p>
								<input class="h_30 full border_box" type="email" name="vendor_email" value="<?php if(isset($_POST['vendor_email'])){ echo $_POST['vendor_email']; } ?>" placeholder="Enter Email" />
							</div>
							<div class="fix full mt_5 mb_5">
								<p class="fs_14 lh_20 text_dark_ash font_bold">Password
								<?php if($vendor_errors['password'] != ""){?>
									<span class="fs_12 lh_20 text_error ml_10"><?php echo $vendor_errors['password']; ?></span>
								<?php } ?>
								</p>
								<input class="h_30 full border_box" type="password" name="vendor_password" value="<?php if(isset($_POST['vendor_password'])){ echo $_POST['vendor_password']; } ?>" placeholder="Enter Password"/>
							</div>
							<div class="fix full mt_5 mb_5">
								<p class="fs_14 lh_20 text_dark_ash font_bold">Confirm Password
								<?php if($vendor_errors['confirm_password'] != ""){?>
									<span class="fs_12 lh_20 text_error ml_10"><?php echo $vendor_errors['confirm_password']; ?></span>
								<?php } ?>
								</p>
								<input class="h_30 full border_box" type="password" name="vendor_confirm_password" value="<?php if(isset($_POST['vendor_confirm_password'])){ echo $_POST['vendor_confirm_password']; } ?>" placeholder="Enter Confirm Password"/>
							</div>
							<div class="fix full mt_5 mb_5">
								<p class="fs_14 lh_20 text_dark_ash font_bold">
								<?php if($vendor_errors['terms_of_services'] != ""){?>
									<span class="fs_12 lh_20 text_error"><?php echo $vendor_errors['terms_of_services']; ?></span>
								<?php } ?>
								</p>
								<input type="checkbox" name="vendor_terms_of_services" value="1">
								<label for="vehicle1"> Accept Terms of Service</label>
							</div>
							<div class="fix full">
								<button type="submit">Register</button>
								<a href="login.php" class="bg_none text_blue ml_10 font_bold">Login</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php 
        MyHelpers::includeWithVariables('./layouts/footer.php', [], $print = true);
        ?>
	</div>
</body>
</html>