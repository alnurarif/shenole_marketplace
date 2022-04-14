<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Majestic;
use Shenole_project\repositories\MajesticRepository;
use Shenole_project\helpers\UserHelper;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\MyHelpers;

$isMajesticLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'majestic', new MajesticRepository);
if($isMajesticLoggedIn){
	header("Location: ".SITE_LINK_MAJESTIC."dashboard.php");
    exit();
}

$errors = [
	'errors_number' => 0,
	'email' => '',	
	'password' => '',	
];
if(isset($_POST['login'])){
	$found_email = Majestic::where('email', '=', $_POST['email'])->exists(); 
	$found_password = Majestic::where('email', '=', $_POST['email'])->where('password', '=', $_POST['password'])->exists();
	
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
	if(!$found_password){
		$errors['errors_number'] += 1;
		$errors['password'] = 'The password you have entered is incorrect.';
	}
	if(!Validator::isStrongPassword($_POST['password'], 8, 12)){
		$errors['errors_number'] += 1;
		$errors['password'] = 'Password must containt atleast 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character and it must be 8-12 characters long';
	}
	if($_POST['password'] == ""){
		$errors['errors_number'] += 1;
		$errors['password'] = 'This field cannot be empty.';
	}

	if($errors['errors_number'] == 0){
		$user = Majestic::where('email', '=', $_POST['email'])->where('password', '=', $_POST['password'])->first();
		if(!is_null($user)) {
			$generator = new RandomStringGenerator;
			$tokenLength = 60;
			$login_token = $generator->generate($tokenLength);
			$user->login_token = $login_token;
			$user->save();

			$before_login_session_array = isset($_SESSION['logged_in_as']) ? $_SESSION['logged_in_as'] : [];
			$_SESSION['logged_in_as'] = [];
			$login_session_array = [
				"logged_in_user_type" => 'majestic',
				"looged_in_token" => $user->login_token
			];
			$found = false;
			foreach($before_login_session_array as &$single){
				if($single['logged_in_user_type'] == 'majestic'){
					$found = true;
					$single = $login_session_array;
				}
			}
			if(!$found){
				array_push($before_login_session_array, $login_session_array);
			}
			$_SESSION['logged_in_as'] = $before_login_session_array;
			
			header("Location: ".SITE_LINK."majestic/dashboard.php");
	    	exit();
		}
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
	<link rel="stylesheet" href="../css/modal.css">
</head>
<body>
	<div class="fix full container dual-signup-container">
		<div class="fix fifty_percent div_mid">
			<div class="fix full mt_160">
				<div class="fix ninty_percent div_mid" id="client-signup-section">
					<form action="login.php" method="post">
						<div class="fix full mt_5 mb_5">
							<input type="hidden" name="login" value="1"/>
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
							<p class="fs_14 lh_20 text_dark_ash font_bold">Password
							<?php if($errors['password'] != ""){?>
							<span class="fs_12 lh_20 text_error ml_10"><?php echo $errors['password']; ?></span>
							<?php } ?>
							</p>
							<input class="fix full h_30 mb_5 border_box" type="password" name="password" value="<?php if(isset($_POST['password'])){ echo $_POST['password']; } ?>" placeholder="Enter Password"/>
						</div>
						<div class="fix full">
							<button type="submit">Login</button>
							<!-- <a href="signup.php" class="bg_none text_blue ml_10 font_bold">Signup</a> -->
						</div>
						<!-- <div class="fix full">
							<a href="forgot-password.php" class="bg_none text_blue mt_10 font_bold display_block">Forgot Password?</a>
						</div> -->
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php 
	MyHelpers::includeWithVariables('../layouts/common_footer.php', [], $print = true);
	?>
</body>
</html>