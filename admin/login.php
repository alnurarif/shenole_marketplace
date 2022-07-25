<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Majestic;
use Shenole_project\models\Staff;
use Shenole_project\repositories\StaffRepository;
use Shenole_project\repositories\MajesticRepository;
use Shenole_project\helpers\UserHelper;
use Shenole_project\helpers\MyHelpers;
use Shenole_project\utils\RandomStringGenerator;

$isMajesticLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'majestic', new MajesticRepository);
$isStaffLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'staff', new StaffRepository);

if($isMajesticLoggedIn){
	header("Location: ".SITE_LINK_MAJESTIC."dashboard.php");
    exit();
}
if($isStaffLoggedIn){
	header("Location: ".SITE_LINK_STAFF."dashboard.php");
    exit();	
}

$errors = [
	'errors_number' => 0,
	'email' => '',	
	'password' => '',	
];
if(isset($_POST['login'])){
	$found_email = ($_POST['user_type'] == 'majestic') ? Majestic::where('email', '=', $_POST['email'])->exists(): Staff::where('email', '=', $_POST['email'])->exists(); 
	
	$found_password = ($_POST['user_type'] == 'majestic') ? Majestic::where('email', '=', $_POST['email'])->where('password', '=', $_POST['password'])->exists() : Staff::where('email', '=', $_POST['email'])->where('password', '=', $_POST['password'])->exists();
	
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
		$user = ($_POST['user_type'] == 'majestic') ? Majestic::where('email', '=', $_POST['email'])->where('password', '=', $_POST['password'])->first() : Staff::where('email', '=', $_POST['email'])->where('password', '=', $_POST['password'])->first();
		if(!is_null($user) && $user->account_status != 'S') {
			$generator = new RandomStringGenerator;
			$tokenLength = 60;
			$login_token = $generator->generate($tokenLength);
			$user->login_token = $login_token;
			$user->save();


			$before_login_session_array = isset($_SESSION['logged_in_as']) ? $_SESSION['logged_in_as'] : [];
			$_SESSION['logged_in_as'] = [];
			$login_session_array = [
				"logged_in_user_type" => ($_POST['user_type'] == 'majestic') ? 'majestic' : 'staff',
				"looged_in_token" => $user->login_token
			];
			$found = false;
			foreach($before_login_session_array as &$single){
				if($single['logged_in_user_type'] == $_POST['user_type']){
					$found = true;
					$single = $login_session_array;
				}
			}
			if(!$found){
				array_push($before_login_session_array, $login_session_array);
			}
			$_SESSION['logged_in_as'] = $before_login_session_array;
			
			// $_SESSION['login_token'] = $user->login_token;
			// $_SESSION['login_user_type'] = ($_POST['user_type'] == 'majestic') ? 'majestic' : 'staff';
			if($_POST['user_type'] == 'majestic'){
				header("Location: ".SITE_LINK_MAJESTIC."dashboard.php");
		    	exit();
			}else{
				header("Location: ".SITE_LINK_STAFF."dashboard.php");
		    	exit();
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<?php MyHelpers::includeWithVariables('../layouts/head_section.php', [], $print = true); ?>
<body>
	<div class="genesis-container">
		<?php 
		MyHelpers::includeWithVariables('../layouts/top_nav.php', ['isMajesticLoggedIn' => $isMajesticLoggedIn, 'isStaffLoggedIn' => $isStaffLoggedIn], $print = true);
		?>
		<div class="fix full container dual-signup-container">
			<div class="fix fifty_percent div_mid">
				<div class="fix full mt_160">
					<div class="fix ninty_percent div_mid" id="client-signup-section">
						<form action="<?php echo SITE_LINK; ?>admin/login.php" method="post">
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
							<div class="fix full mt_5 mb_5">
								<p class="fs_14 lh_20 text_dark_ash font_bold">User Type</p>
								<select class="fix full h_30 mb_5 border_box" name="user_type" id="user_type">
									<option value="majestic" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] == 'majestic') ? 'selected' : ''; ?>>Majestic</option>
									<option value="staff" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] == 'staff') ? 'selected' : ''; ?>>Staff</option>
								</select>
							</div>
							<div class="fix full">
								<button type="submit">Login</button>
							</div>
							
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php 
        MyHelpers::includeWithVariables('../layouts/footer.php', [], $print = true);
        ?>
	</div>
	<script src="../js/custom.js"></script>
</body>
</html>