<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Client;
use Shenole_project\models\Vendor;
use Shenole_project\models\Client_setting;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\ClientRepository;
use Shenole_project\helpers\MyHelpers;

$isClientLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'client', new ClientRepository);
$login_token = UserHelper::getLoginTokenByUserType($_SESSION, 'client');
if(!$isClientLoggedIn){
	header("Location: ".SITE_LINK."login.php");
    exit();
}
$account_errors = [
    'errors_number' => 0,
    'username' => '',
    'email' => '',	
    'password' => '',	
    'confirm_password' => ''
];
$paypal_errors = [
    'errors_number' => 0,
    'client_paypal_email' => ''
];

$client = Client::where('login_token',$login_token)->with('client_setting')->first();

if($_POST){
    if(isset($_POST['operation_add_update_account'])){
        if($_POST['username'] == ""){
            $account_errors['errors_number'] += 1;
            $account_errors['username'] = 'This field cannot be empty.';
        }
        if($_POST['email'] == ""){
            $account_errors['errors_number'] += 1;
            $account_errors['email'] = 'This field cannot be empty.';
        }
        if(!Validator::isValidEmail($_POST['email'])){
            $account_errors['errors_number'] += 1;
            $account_errors['email'] = 'You have entered an invalid email address.';
        }
        if(Vendor::where('email', '=', $_POST['email'])->exists() || Client::where('email', '=', $_POST['email'])->exists()){
            if($client->email != $_POST['email']){
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
        if(Vendor::where('password', '=', $_POST['password'])->exists() || Client::where('password', '=', $_POST['password'])->exists()){
            $account_errors['errors_number'] += 1;
            $account_errors['password'] = 'This password is already in use.';
        }
        if($_POST['password'] !== $_POST['confirm_password']){
            $account_errors['errors_number'] += 1;
            $account_errors['confirm_password'] = 'This password does not match.';	
        }

        if($account_errors['errors_number'] == 0){
            $client->email = trim($_POST['email']);
            $client->password = trim($_POST['password']);
            $client->save();

            if($client->client_setting === null){
                $client_setting_object = new Client_setting;
                $client_setting_object->username = trim($_POST['username']);
                $client->client_setting()->save($client_setting_object);
            }else{
                $client->client_setting->username = trim($_POST['username']);
                $client->client_setting->save();
            }
            $client = Client::where('login_token',$login_token)->with('client_setting')->first();
        }
    }
    if(isset($_POST['operation_add_update_paypal'])){
        if($_POST['client_paypal_email'] == ""){
            $paypal_errors['errors_number'] += 1;
            $paypal_errors['client_paypal_email'] = 'This field cannot be empty.';
        }
        if(!Validator::isValidEmail($_POST['client_paypal_email'])){
            $paypal_errors['errors_number'] += 1;
            $paypal_errors['client_paypal_email'] = 'You have entered an invalid email address.';
        }

        if($paypal_errors['errors_number'] == 0){
            if($client->client_setting === null){
                $client_setting_object = new Client_setting;
                $client_setting_object->client_paypal_email = trim($_POST['client_paypal_email']);
                
                $client->client_setting()->save($client_setting_object);
            }else{
                $client->client_setting->client_paypal_email = trim($_POST['client_paypal_email']);
                $client->client_setting->save();
            }
            $client = Client::where('login_token',$login_token)->with('client_setting')->first();
        }
        $show_paypal_section = true;
    }
    if(isset($_POST['operation_add_update_advertise'])){
        if($client->client_setting === null){
            $client_setting_object = new Client_setting;
            
            if(isset($_POST['disable_ads'])){
                $client_setting_object->disable_ads = 'on';
            }else{
                $client_setting_object->disable_ads = 'off';
            }
            
            $client->client_setting()->save($client_setting_object);
        }else{
            if(isset($_POST['disable_ads'])){
                $client->client_setting->disable_ads = 'on';
            }else{
                $client->client_setting->disable_ads = 'off';
            }
            $client->client_setting->save();
        }
        $client = Client::where('login_token',$login_token)->with('client_setting')->first();
        $show_advertise_section = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<?php MyHelpers::includeWithVariables('../layouts/head_section.php', [], $print = true); ?>
<body>
	<div class="genesis-container">
		<?php 
		MyHelpers::includeWithVariables('../layouts/top_nav.php', ['isClientLoggedIn' => $isClientLoggedIn], $print = true);
		?>
		<div class="main-body-content">
			<section class="section-type-01">
                <div class="ad-space-container-160">
                    <div class="ad-space-type01-desktop">
                        <!-- Ad Space (160 x 600) -->
                    </div>
                    <div class="ad-space-type01-desktop">
                        <!-- Ad Space (160 x 600) -->
                    </div>
                </div>
                <!-- <div class="ad-space-type01-mobile">

                </div> -->
                <div class="content-container-center">
                    <div class="vendor-profile-nav">
                        <div class="profile-nav-link" id="tab_account">Account</div>
                        <div class="profile-nav-link" id="tab_paypal">Paypal</div>
                        <div class="profile-nav-link" id="tab_upgrades">Upgrades</div>
                    </div>
                    <div class="profile-section" id="account">
                        <h2>Account</h2>
                        <form method="post" class="full-width-form">
                            <input type="hidden" name="operation_add_update_account" value="1"/>
                            <h3>Change Your Login Email & Username</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="client-username" class="input-label">Enter Your Desired Username
                                    <?php if($account_errors['username'] != ""){?>
                                        <span class="fs_12 lh_20 text_error ml_10"><?php echo $account_errors['username']; ?></span>
                                    <?php } ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="username" value="<?php echo ($client->client_setting != null) ? $client->client_setting->username : ''; ?>" type="text" id="client-username" class="search-input" placeholder="example: JohnyBoy123">
                                    </div>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="client-login-email-setting" class="input-label">Enter New Login Email
                                    <?php if($account_errors['email'] != ""){?>
                                        <span class="fs_12 lh_20 text_error ml_10"><?php echo $account_errors['email']; ?></span>
                                    <?php } ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="email" value="<?php echo $client->email; ?>" type="text" id="client-login-email-setting" class="search-input" placeholder="example: mymail@email.com">
                                    </div>
                                </div>
                                <!-- <div class="form-input-search">
                                    <label for="paypal-email-display" class="input-label">Your Current Paypal Email</label>
                                    <div class="spacer-10px"></div>
                                    <ul class="category-ul">
                                        <li class="category-li"><div>No Paypal Email Saved At This Time</div></li>
                                    </ul>
                                </div> -->
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
                                        <input name="password" value="<?php if(isset($_POST['password'])){ echo $_POST['password']; } ?>" type="password" id="client-new-password-setting" class="search-input" placeholder="example: mymail@email.com">
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
                                        <input name="confirm_password" value="<?php if(isset($_POST['confirm_password'])){ echo $_POST['confirm_password']; } ?>" type="password" id="client-confirm-new-password" class="search-input" placeholder="example: mymail@email.com">
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
                                    <?php if($paypal_errors['client_paypal_email'] != ""){?>
                                        <span class="fs_12 lh_20 text_error ml_10"><?php echo $paypal_errors['client_paypal_email']; ?></span>
                                    <?php } ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="client_paypal_email" value="<?php echo ($client->client_setting != null) ? $client->client_setting->client_paypal_email : ''; ?>" type="text" id="client-paypal-email-setting" class="search-input" placeholder="example: mymail@email.com">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location" class="input-label">Your Current Paypal Email</label>
                                    <div class="spacer-10px"></div>
                                    <ul class="category-ul">
                                        <li class="category-li"><div><?php echo ($client->client_setting != null && $client->client_setting->client_paypal_email != "" && $client->client_setting->client_paypal_email != null) ? $client->client_setting->client_paypal_email : 'No Paypal Email Saved At This Time'; ?></div></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-submit-container">
                                <button type="submit" class="button-01 white-text" id="majestic-vendor-categories-submit">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="profile-section" id="upgrades" data-show-initially="<?php echo (isset($show_advertise_section))? '1' : '0' ; ?>">
                        <h2>Upgrades</h2>
                        <form method="post" class="full-width-form">
                            <input type="hidden" name="operation_add_update_advertise" value="1"/>
                            <h3>Advertisement Settings</h3>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <div>
                                        <label for="disable-ads" class="input-label">Disable Advertisements ($2.99/month)</label>
                                        <div class="spacer-10px"></div>
                                        <input name="disable_ads" type="checkbox" id="disable-ads" <?php echo (($client->client_setting != null) && ($client->client_setting->disable_ads == 'on')) ? "checked" : ""; ?>>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-submit-container">
                                <button type="submit" class="button-01 white-text" name="membership-settings-submit" id="membership-settings-submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <div class="ad-space-type01-mobile">

                </div> -->
                <div class="ad-space-container-160">
                    <div class="ad-space-type01-desktop">
                        <!-- Ad Space (160 x 600) -->
                    </div>
                    <div class="ad-space-type01-desktop">
                        <!-- Ad Space (160 x 600) -->
                    </div>
                </div>
            </section>
		</div>
		<?php 
        MyHelpers::includeWithVariables('../layouts/cart_modal.php', [], $print = true);
        MyHelpers::includeWithVariables('../layouts/footer.php', [], $print = true);
        ?>
	</div>
	<script src="<?php echo SITE_LINK; ?>js/jquery.min.js"></script>
    <script src="<?php echo SITE_LINK; ?>js/pages/clients/settings.js"></script>
	<script src="<?php echo SITE_LINK; ?>js/custom.js"></script>
	<script src="<?php echo SITE_LINK; ?>js/cart_modal.js"></script>

</body>
</html>