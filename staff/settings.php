<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Staff;
use Shenole_project\models\Vendor;
use Shenole_project\models\Staff_setting;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\StaffRepository;
use Shenole_project\helpers\MyHelpers;

$isStaffLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'staff', new StaffRepository);
$login_token = UserHelper::getLoginTokenByUserType($_SESSION, 'staff');
if(!$isStaffLoggedIn){
	header("Location: ".SITE_LINK_STAFF."login.php");
    exit();
}

$account_errors = [
    'errors_number' => 0,
    'first_name' => '',
    'last_name' => '',
    'username' => '',
    'email' => '',	
    'password' => '',	
    'confirm_password' => ''
];
$paypal_errors = [
    'errors_number' => 0,
    'staff_paypal_email' => ''
];
$staff = Staff::where('login_token',$login_token)->with('staff_setting')->first();

if($_POST){
    if(isset($_POST['operation_add_update_account'])){
        if($_POST['username'] == ""){
            $account_errors['errors_number'] += 1;
            $account_errors['username'] = 'This field cannot be empty.';
        }
        if($_POST['first_name'] == ""){
            $account_errors['errors_number'] += 1;
            $account_errors['first_name'] = 'This field cannot be empty.';
        }
        if($_POST['last_name'] == ""){
            $account_errors['errors_number'] += 1;
            $account_errors['last_name'] = 'This field cannot be empty.';
        }
        if($_POST['email'] == ""){
            $account_errors['errors_number'] += 1;
            $account_errors['email'] = 'This field cannot be empty.';
        }
        if(!Validator::isValidEmail($_POST['email'])){
            $account_errors['errors_number'] += 1;
            $account_errors['email'] = 'You have entered an invalid email address.';
        }
        if(Staff::where('email', '=', $_POST['email'])->exists()){
            if($staff->email != $_POST['email']){
                $account_errors['errors_number'] += 1;
                $account_errors['email'] = 'This email is already in use.';
            }
        }
        if($_POST['password'] == ""){
            $account_errors['errors_number'] += 1;
            $account_errors['password'] = 'This field cannot be empty.';
        }
        if(!Validator::isStrongPassword($_POST['password'], 8, 12)){
            $account_errors['errors_number'] += 1;
            $account_errors['password'] = 'Password must containt atleast 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character and it must be 8-12 characters long';
        }
        if(Staff::where('password', '=', $_POST['password'])->exists()){
            $account_errors['errors_number'] += 1;
            $account_errors['password'] = 'This password is already in use.';
        }
        if($_POST['password'] !== $_POST['confirm_password']){
            $account_errors['errors_number'] += 1;
            $account_errors['confirm_password'] = 'This password does not match.';	
        }

        if($account_errors['errors_number'] == 0){
            $staff->email = trim($_POST['email']);
            $staff->password = trim($_POST['password']);
            $staff->save();

            if($staff->staff_setting === null){
                $staff_setting_object = new Staff_setting;
                $staff_setting_object->username = trim($_POST['username']);
                $staff->staff_setting()->save($staff_setting_object);
            }else{
                $staff->staff_setting->username = trim($_POST['username']);
                $staff->staff_setting->save();
            }
            $staff = Staff::where('login_token',$login_token)->with('staff_setting')->first();
        }
    }
    if(isset($_POST['operation_add_update_paypal'])){
        if($_POST['staff_paypal_email'] == ""){
            $paypal_errors['errors_number'] += 1;
            $paypal_errors['staff_paypal_email'] = 'This field cannot be empty.';
        }
        if(!Validator::isValidEmail($_POST['staff_paypal_email'])){
            $paypal_errors['errors_number'] += 1;
            $paypal_errors['staff_paypal_email'] = 'You have entered an invalid email address.';
        }

        if($paypal_errors['errors_number'] == 0){
            if($staff->staff_setting === null){
                $staff_setting_object = new Staff_setting;
                $staff_setting_object->staff_paypal_email = trim($_POST['staff_paypal_email']);
                
                $staff->staff_setting()->save($staff_setting_object);
            }else{
                $staff->staff_setting->staff_paypal_email = trim($_POST['staff_paypal_email']);
                $staff->staff_setting->save();
            }
            $staff = staff::where('login_token',$login_token)->with('staff_setting')->first();
        }
        $show_paypal_section = true;
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
			<section class="section-type-01">
                <div class="content-container-center">
                    <div class="vendor-profile-nav">
                        <div class="profile-nav-link" id="tab_account">Account</div>
                        <div class="profile-nav-link" id="tab_paypal">Paypal</div>
                    </div>
                    <div class="profile-section" id="account">
                        <h2>Account Settings</h2>
                        <form method="post" class="full-width-form">
                            <input type="hidden" name="operation_add_update_account" value="1"/>
                            <h3>Staff Member Info</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="staff-username" class="input-label">Enter Your Desired Username
                                    <?php if($account_errors['username'] != ""){?>
                                        <span class="fs_12 lh_20 text_error ml_10"><?php echo $account_errors['username']; ?></span>
                                    <?php } ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="username" value="<?php echo ($staff->staff_setting != null) ? $staff->staff_setting->username : ''; ?>" type="text" id="staff-username" class="search-input" placeholder="example: JohnyBoy123">
                                    </div>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">First Name
                                    <?php if($account_errors['first_name'] != ""){?>
                                        <span class="fs_12 lh_20 text_error ml_10"><?php echo $account_errors['first_name']; ?></span>
                                    <?php } ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="first_name" value="<?php echo $staff->first_name; ?>" type="text" id="staff-first-name" class="search-input" placeholder="example: John">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Last Name
                                    <?php if($account_errors['last_name'] != ""){?>
                                        <span class="fs_12 lh_20 text_error ml_10"><?php echo $account_errors['last_name']; ?></span>
                                    <?php } ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="last_name" value="<?php echo $staff->last_name; ?>" type="text" id="staff-last-name" class="search-input" placeholder="example: Smith">
                                    </div>
                                </div>
                            </div>
                            <h3>Change Your Login Email</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Enter New Login Email
                                    <?php if($account_errors['email'] != ""){?>
                                        <span class="fs_12 lh_20 text_error ml_10"><?php echo $account_errors['email']; ?></span>
                                    <?php } ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="email" value="<?php echo $staff->email; ?>" type="text" id="client-login-email-setting" class="search-input" placeholder="example: mymail@email.com">
                                    </div>
                                </div>
                            </div>
                            <h3>Change Your Password</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Enter New Password
                                    <?php if($account_errors['password'] != ""){?>
                                        <span class="fs_12 lh_20 text_error ml_10"><?php echo $account_errors['password']; ?></span>
                                    <?php } ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="password" value="<?php if(isset($_POST['password'])){ echo $_POST['password']; } ?>" type="text" id="client-new-password-setting" class="search-input" placeholder="example: mymail@email.com">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Confirm New Password
                                    <?php if($account_errors['confirm_password'] != ""){?>
                                        <span class="fs_12 lh_20 text_error ml_10"><?php echo $account_errors['confirm_password']; ?></span>
                                    <?php } ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="confirm_password" value="<?php if(isset($_POST['confirm_password'])){ echo $_POST['confirm_password']; } ?>" type="text" id="client-confirm-new-password" class="search-input" placeholder="example: mymail@email.com">
                                    </div>
                                </div>
                            </div>
                            <div class="form-submit-container">
                                <button type="submit" class="button-01 white-text" id="majestic-vendor-categories-submit">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="profile-section" id="paypal" data-show-initially="<?php echo (isset($show_paypal_section))? '1' : '0' ; ?>">
                        <h2>Paypal</h2>
                        <form method="post" class="full-width-form">
                            <input type="hidden" name="operation_add_update_paypal" value="1"/>
                            <h3>Paypal Email</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Enter Your Paypal Email
                                    <?php if($paypal_errors['staff_paypal_email'] != ""){?>
                                        <span class="fs_12 lh_20 text_error ml_10"><?php echo $paypal_errors['staff_paypal_email']; ?></span>
                                    <?php } ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="staff_paypal_email" value="<?php echo ($staff->staff_setting != null) ? $staff->staff_setting->staff_paypal_email : ''; ?>" type="text" id="staff-paypal-email-setting" class="search-input" placeholder="example: mymail@email.com">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location" class="input-label">Your Current Paypal Email</label>
                                    <div class="spacer-10px"></div>
                                    <ul class="category-ul">
                                        <li class="category-li"><div><?php echo ($staff->staff_setting != null && $staff->staff_setting->staff_paypal_email != "" && $staff->staff_setting->staff_paypal_email != null) ? $staff->staff_setting->staff_paypal_email : 'No Paypal Email Saved At This Time'; ?></div></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-submit-container">
                                <button type="submit" class="button-01 white-text" id="majestic-vendor-categories-submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
		</div>
		<?php 
        MyHelpers::includeWithVariables('../layouts/footer.php', [], $print = true);
        ?>
	</div>
	<script src="<?php echo SITE_LINK; ?>js/jquery.min.js"></script>
    <script src="<?php echo SITE_LINK; ?>js/pages/staff/settings.js"></script>
</body>
</html>