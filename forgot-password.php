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


$errors = [
	'errors_number' => 0,
	'email' => '',	
	'password' => '',	
];
if(isset($_POST['forgot_password'])){
	$found_email = ($_POST['user_type'] == 'client') ? Client::where('email', '=', $_POST['email'])->exists(): Vendor::where('email', '=', $_POST['email'])->exists(); 
	
	if(!$found_email){
		$errors['errors_number'] += 1;
		$errors['email'] = 'This email is not associated with any accounts on file.';
	}
	if(!Validator::isValidEmail($_POST['email'])){
		$errors['errors_number'] += 1;
		$errors['email'] = 'You have entered an invalid email address.';
	}
	if($_POST['email'] == ""){
		$errors['errors_number'] += 1;
		$errors['email'] = 'This field cannot be empty.';
	}

	if($errors['errors_number'] == 0){
// 		$user = ($_POST['user_type'] == 'client') ? Client::where('email', '=', $_POST['email'])->first() : Vendor::where('email', '=', $_POST['email'])->first();

// 		$to = $_POST['email'];
// 		$subject = 'Recover Password';
	    
// 		$user_password = $user->password;
	    
// 	    $admin_email = 'test@gmail';

// 	    $from = $admin_email;
// 	    $headers ='';
// 	    $headers .= 'From: '.$from.' '. "\r\n";
// 	    $message = "Greetings,\r\n
// We are providing you with your password. Please be sure to save it in a safe place.\r\n
// Your password is: ".$user_password;

// 		$retval = mail($to, $subject, $message, $headers);
// 		header("Location: recover_password_success.php");
// 		exit();

		echo '<h1 class="fs_20 lh_30 font_bold textcenter mt_30">Email Sent, Please Check email!!!</h1>';
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
			<div class="fix fifty_percent div_mid">
				<div class="fix full mt_160">
					<div class="fix ninty_percent div_mid" id="client-signup-section">
						<form action="forgot-password.php" method="post">
							<div class="fix full mt_5 mb_5">
								<input type="hidden" name="forgot_password" value="1"/>
							</div>
							<div class="fix full mt_5 mb_5">
								<p class="fs_14 lh_20 text_dark_ash font_bold">Email
								<?php if($errors['email'] != ""){?>
								<span class="fs_12 lh_20 text_error ml_10"><?php echo $errors['email']; ?></span>
								<?php } ?>
								</p>
								<input class="fix full h_30 mb_5 border_box" type="email" name="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>" placeholder="Enter Email" />
							</div>
							<div class="fix full mt_5 mb_5">
								<p class="fs_14 lh_20 text_dark_ash font_bold">User Type</p>
								<select class="fix full h_30 mb_5 border_box" name="user_type" id="user_type">
									<option value="client" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] == 'client') ? 'selected' : ''; ?>>Client</option>
									<option value="vendor" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] == 'vendor') ? 'selected' : ''; ?>>Vendor</option>
								</select>
							</div>
							<div class="fix full">
								<button type="submit">Submit</button>
								<a href="signup.php" class="bg_none text_blue ml_10 font_bold">Signup</a>
								<a href="login.php" class="bg_none text_blue ml_10 font_bold">Login</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>