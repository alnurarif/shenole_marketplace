<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Vendor;
use Shenole_project\models\Vendor_setting;
use Shenole_project\models\Client;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\VendorRepository;
use Shenole_project\helpers\MyHelpers;

$isVendorLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'vendor', new VendorRepository);
$login_token = UserHelper::getLoginTokenByUserType($_SESSION, 'vendor');
if(!$isVendorLoggedIn){
	header("Location: ".SITE_LINK."login.php");
    exit();
}
$account_errors = [
    'errors_number' => 0,
    'email' => '',	
    'password' => '',	
    'confirm_password' => ''
];
$paypal_errors = [
    'errors_number' => 0,
    'vendor_paypal_email' => ''
];

$vendor = Vendor::where('login_token',$login_token)->with('vendor_setting')->first();

if($_POST){
    if(isset($_POST['operation_add_update_account'])){
        
        if($_POST['email'] == ""){
            $account_errors['errors_number'] += 1;
            $account_errors['email'] = 'This field cannot be empty.';
        }
        if(!Validator::isValidEmail($_POST['email'])){
            $account_errors['errors_number'] += 1;
            $account_errors['email'] = 'You have entered an invalid email address.';
        }
        if(Vendor::where('email', '=', $_POST['email'])->exists() || Client::where('email', '=', $_POST['email'])->exists()){
            if($vendor->email != $_POST['email']){
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
            $vendor->email = trim($_POST['email']);
            $vendor->password = trim($_POST['password']);
            $vendor->save();
        }
    }
    if(isset($_POST['operation_add_update_paypal'])){
        if($_POST['vendor_paypal_email'] == ""){
            $paypal_errors['errors_number'] += 1;
            $paypal_errors['vendor_paypal_email'] = 'This field cannot be empty.';
        }
        if(!Validator::isValidEmail($_POST['vendor_paypal_email'])){
            $paypal_errors['errors_number'] += 1;
            $paypal_errors['vendor_paypal_email'] = 'You have entered an invalid email address.';
        }

        if($paypal_errors['errors_number'] == 0){
            if($vendor->vendor_setting === null){
                $vendor_setting_object = new Vendor_setting;
                $vendor_setting_object->vendor_paypal_email = trim($_POST['vendor_paypal_email']);
                $vendor_setting_object->paypal_client_id = trim($_POST['paypal_client_id']);
                $vendor_setting_object->paypal_secret_id = trim($_POST['paypal_secret_id']);
                
                $vendor->vendor_setting()->save($vendor_setting_object);
            }else{
                $vendor->vendor_setting->vendor_paypal_email = trim($_POST['vendor_paypal_email']);
                $vendor->vendor_setting->paypal_client_id = trim($_POST['paypal_client_id']);
                $vendor->vendor_setting->paypal_secret_id = trim($_POST['paypal_secret_id']);
                $vendor->vendor_setting->save();
            }
            $vendor = Vendor::where('login_token',$login_token)->with('vendor_setting')->first();
        }
        $show_paypal_section = true;
    }
    if(isset($_POST['operation_add_update_membership'])){
        if($vendor->vendor_setting === null){
            $vendor_setting_object = new Vendor_setting;
            if(isset($_POST['membership_1'])){
                $vendor_setting_object->membership_level = 'membership_1';
            }elseif(isset($_POST['membership_2'])){
                $vendor_setting_object->membership_level = 'membership_2';
            }elseif(isset($_POST['membership_3'])){
                $vendor_setting_object->membership_level = 'membership_3';
            }elseif(isset($_POST['membership_4'])){
                $vendor_setting_object->membership_level = 'membership_4';
            }else{
                $vendor_setting_object->membership_level = 'membership_1';
            }
            
            $vendor->vendor_setting()->save($vendor_setting_object);
        }else{
            if(isset($_POST['membership_1'])){
                $vendor->vendor_setting->membership_level = 'membership_1';
            }elseif(isset($_POST['membership_2'])){
                $vendor->vendor_setting->membership_level = 'membership_2';
            }elseif(isset($_POST['membership_3'])){
                $vendor->vendor_setting->membership_level = 'membership_3';
            }elseif(isset($_POST['membership_4'])){
                $vendor->vendor_setting->membership_level = 'membership_4';
            }else{
                $vendor->vendor_setting->membership_level = 'membership_1';
            }
            $vendor->vendor_setting->save();
        }
        $vendor = Vendor::where('login_token',$login_token)->with('vendor_setting')->first();
        $show_membership_section = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php MyHelpers::includeWithVariables('../layouts/head_section.php', [], $print = true); ?>
<body>
	<div class="genesis-container">
		<?php 
		MyHelpers::includeWithVariables('../layouts/top_nav.php', ['isVendorLoggedIn' => $isVendorLoggedIn], $print = true);
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
                        <div class="profile-nav-link" id="tab_ad_space">Ad Space</div>
                        <div class="profile-nav-link" id="tab_services">Services</div>
                    </div>
                    <div class="profile-section" id="account">
                        <h2>Account</h2>
                        <form method="post" class="full-width-form">
                            <input type="hidden" name="operation_add_update_account" value="1"/>
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
                                        <input name="email" value="<?php echo $vendor->email; ?>" type="text" id="vendor-login-email-setting" class="search-input" placeholder="example: mymail@email.com">
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
                                        <input name="password" value="<?php if(isset($_POST['password'])){ echo $_POST['password']; } ?>" type="password" id="vendor-new-password-setting" class="search-input" placeholder="example: mymail@email.com">
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
                                        <input name="confirm_password" value="<?php if(isset($_POST['confirm_password'])){ echo $_POST['confirm_password']; } ?>" type="password" id="vendor-confirm-new-password" class="search-input" placeholder="example: mymail@email.com">
                                    </div>
                                </div>
                            </div>
                            <div class="form-submit-container">
                                <button type="submit" class="button-01 white-text" id="vendor-account-submit">Submit</button>
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
                                    <?php if($paypal_errors['vendor_paypal_email'] != ""){?>
                                        <span class="fs_12 lh_20 text_error ml_10"><?php echo $paypal_errors['vendor_paypal_email']; ?></span>
                                    <?php } ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="vendor_paypal_email" value="<?php echo ($vendor->vendor_setting != null) ? $vendor->vendor_setting->vendor_paypal_email : ''; ?>" type="text" id="vendor-paypal-email-setting" class="search-input" placeholder="example: mymail@email.com">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location" class="input-label">Your Current Paypal Email</label>
                                    <div class="spacer-10px"></div>
                                    <ul class="category-ul">
                                        <li class="category-li"><div><?php echo ($vendor->vendor_setting != null && $vendor->vendor_setting->vendor_paypal_email != "" && $vendor->vendor_setting->vendor_paypal_email != null) ? $vendor->vendor_setting->vendor_paypal_email : 'No Paypal Email Saved At This Time'; ?></div></li>
                                    </ul>
                                </div>
                            </div>
                            <h3>Paypal Client & Secret ID</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="paypal-client-id" class="input-label">Enter Your Paypal Client ID</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input value="<?php echo ($vendor->vendor_setting != null) ? $vendor->vendor_setting->paypal_client_id : ''; ?>" name="paypal_client_id" type="text" id="paypal-client-id" class="search-input" placeholder="">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="paypal-secret-id" class="input-label">Enter Your Paypal Secret ID</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input value="<?php echo ($vendor->vendor_setting != null) ? $vendor->vendor_setting->paypal_secret_id : ''; ?>" name="paypal_secret_id" type="text" id="paypal-secret-id" class="search-input" placeholder="Place your Paypal secret ID here">
                                    </div>
                                </div>
                            </div>
                            <div class="form-submit-container">
                                <button type="submit" class="button-01 white-text" id="majestic-vendor-categories-submit">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="profile-section" id="upgrades" data-show-initially="<?php echo (isset($show_membership_section))? '1' : '0' ; ?>">
                        <h2>Membership Plans</h2>
                        <form method="post" class="full-width-form">
                        <input type="hidden" name="operation_add_update_membership" value="1"/>
                            <h3>Membership Selection</h3>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <div>
                                        <input 
                                        name="membership_1" 
                                        class="membership_level" 
                                        type="checkbox" 
                                        id="select-membership-1"
                                        <?php echo (($vendor->vendor_setting == null) || ($vendor->vendor_setting->membership_level == null)) ? "checked" : ""; ?>
                                        <?php echo (($vendor->vendor_setting != null) && ($vendor->vendor_setting->membership_level == 'membership_1')) ? "checked" : ""; ?>
                                        >
                                        <div class="spacer-10px"></div>
                                        <h3 class="heading-primary">Membership 1</h3>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                        <h4>Features:</h4>
                                        <ul>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                        </ul>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                        <h3 class="heading-primary"><span id="membership-one-price">$0.00/Month</span></h3>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <div>
                                        <input 
                                        name="membership_2" 
                                        class="membership_level" 
                                        type="checkbox" 
                                        id="select-membership-2"
                                        <?php echo (($vendor->vendor_setting != null) && ($vendor->vendor_setting->membership_level == 'membership_2')) ? "checked" : ""; ?>>
                                        <div class="spacer-10px"></div>
                                        <h3 class="heading-primary">Membership 2</h3>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                        <h4>Features:</h4>
                                        <ul>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                        </ul>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                        <h3 class="heading-primary"><span id="membership-one-price">$0.00/Month</span></h3>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <div>
                                        <input 
                                        name="membership_3" 
                                        class="membership_level" 
                                        type="checkbox" 
                                        id="select-membership-3"
                                        <?php echo (($vendor->vendor_setting != null) && ($vendor->vendor_setting->membership_level == 'membership_3')) ? "checked" : ""; ?>>
                                        <div class="spacer-10px"></div>
                                        <h3 class="heading-primary">Membership 3</h3>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                        <h4>Features:</h4>
                                        <ul>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                        </ul>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                        <h3 class="heading-primary"><span id="membership-one-price">$0.00/Month</span></h3>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <div>
                                        <input 
                                        name="membership_4" 
                                        class="membership_level" 
                                        type="checkbox" 
                                        id="select-membership-4"
                                        <?php echo (($vendor->vendor_setting != null) && ($vendor->vendor_setting->membership_level == 'membership_4')) ? "checked" : ""; ?>>
                                        <div class="spacer-10px"></div>
                                        <h3 class="heading-primary">Membership 4</h3>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                        <h4>Features:</h4>
                                        <ul>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                            <li class="membership-features">- Neque porro quisquam est quips</li>
                                        </ul>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                        <h3 class="heading-primary"><span id="membership-one-price">$0.00/Month</span></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="form-submit-container">
                                <button type="submit" class="button-01 white-text" name="membership-settings-submit" id="membership-settings-submit">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="profile-section" id="ad_space">
                        <h2>Ad Space</h2>
                        <form action="" class="full-width-form">
                            <p>***NOTE*** Each setting will affect the ad campaign total cost listed below.</p>
                            <h3>Campaign Length Settings</h3>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Select A Campaign Length</label>
                                    <div class="spacer-10px"></div>
                                    <select name="state-01" class="search-input">
                                        <option value="" class="select-option-01">Time Period</option>
                                        <option value="" class="select-option-01"></option>
                                    </select>
                                </div>
                            </div>
                            <h3>Keyword Settings</h3>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Select Number Of Kewords To Appear Under</label>
                                    <div class="spacer-10px"></div>
                                    <select name="state-01" class="search-input-mini">
                                        <option value="0" class="select-option-01">---</option>
                                        <option value="" class="select-option-01"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="keywords" class="input-label">Enter Comma Separated Keywords<br>(0 word max)</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input type="text" id="keyword-vendor-search" class="search-input" placeholder="example: party band, caterer, photographer">
                                    </div>
                                    <div class="spacer-10px"></div>
                                    <div class="form-submit-container">
                                        <a href="" class="button-03 button-link-text white-text">Add Keywords</a>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location" class="input-label">Keyword List</label>
                                    <div class="spacer-10px"></div>
                                    <div class="list-container">
                                        <ul class="category-ul">
                                            <li class="category-li"><div>Jazz</div><br><div><button class="small-button primary white-text">Delete</button></div></li>
                                            <li class="category-li"><div>Blues</div><br><div><button class="small-button primary white-text">Delete</button></div></li>
                                            <li class="category-li"><div>Funk</div><br><div><button class="small-button primary white-text">Delete</button></div></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <h3>Category Settings</h3>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Select Number Of Categories To Appear Under</label>
                                    <div class="spacer-10px"></div>
                                    <select name="state-01" class="search-input-mini">
                                        <option value="0" class="select-option-01">---</option>
                                        <option value="" class="select-option-01"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Category Choices</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <select name="category" id="category" class="search-input">
                                            <option value="" class="select-option-01">Select Category</option>
                                            <option value="" class="select-option-01"></option>
                                        </select>
                                    </div>
                                    <div class="spacer-10px"></div>
                                    <div class="spacer-10px"></div>
                                    <div class="form-submit-container">
                                        <a href="" class="button-03 button-link-text white-text">Add Category</a>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location" class="input-label">Category List</label>
                                    <div class="spacer-10px"></div>
                                    <div class="list-container">
                                        <ul class="category-ul">
                                            <li class="category-li"><div>Cateror</div><br><div class="multi-button-container"><button class="small-button primary white-text">Delete</button></div></li>
                                            <li class="category-li"><div>Musician</div><br><div class="multi-button-container"><button class="small-button primary white-text">Delete</button></div></li>
                                            <li class="category-li"><div>Photographer</div><br><div class="multi-button-container"><button class="small-button primary white-text">Delete</button></div></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <h3>Location Settings</h3>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Select Number Of Locations To Appear Under</label>
                                    <div class="spacer-10px"></div>
                                    <select name="state-01" class="search-input-mini">
                                        <option value="0" class="select-option-01">---</option>
                                        <option value="" class="select-option-01"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Location Choices</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <select name="category" id="category" class="search-input">
                                            <option value="" class="select-option-01">Select State</option>
                                            <option value="" class="select-option-01"></option>
                                        </select>
                                    </div>
                                    <div class="spacer-10px"></div>
                                    <div class="spacer-10px"></div>
                                    <div class="form-submit-container">
                                        <a href="" class="button-03 button-link-text white-text">Add Location</a>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location" class="input-label">Location List</label>
                                    <div class="spacer-10px"></div>
                                    <div class="list-container">
                                        <ul class="category-ul">
                                            <li class="category-li"><div>Cateror</div><br><div class="multi-button-container"><button class="small-button primary white-text">Delete</button></div></li>
                                            <li class="category-li"><div>Musician</div><br><div class="multi-button-container"><button class="small-button primary white-text">Delete</button></div></li>
                                            <li class="category-li"><div>Photographer</div><br><div class="multi-button-container"><button class="small-button primary white-text">Delete</button></div></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <h3>Upload Ad Banners</h3>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="skyscraper-upload" class="input-label">Select Your 160px by 600px Banner</label>
                                    <input type="file" id="skyscraper-upload" name="skyscraper-upload">
                                    <div class="spacer-10px"></div>
                                    <img src="../images/main-images/skyscraper-demo-size.png" alt="" class="fluid-image">
                                </div>
                                <div class="form-input-search">
                                    <label for="leader-board-upload" class="input-label">Select Your 728px by 90px Banner</label>
                                    <input type="file" id="leader-board-upload" name="leader-board-upload">
                                    <div class="spacer-10px"></div>
                                    <img src="../images/main-images/leader-board-demo-size.png" alt="" class="fluid-image">
                                </div>
                                <div class="form-input-search">
                                    <label for="banner-upload" class="input-label">Select Your 468px by 60px Banner</label>
                                    <input type="file" id="banner-upload" name="banner-upload">
                                    <div class="spacer-10px"></div>
                                    <img src="../images/main-images/banner-demo-size.png" alt="" class="fluid-image">
                                </div>
                            </div>
                            <h3>Banner Settings</h3>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Select Number Of Banners To Appear In</label>
                                    <div class="spacer-10px"></div>
                                    <select name="state-01" class="search-input-mini">
                                        <option value="0" class="select-option-01">---</option>
                                        <option value="" class="select-option-01"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Place Ad In Top Left Banner</label>
                                    <div class="spacer-10px"></div>
                                    <input type="checkbox" name="disable-ads" id="disable-ads">
                                        <div class="spacer-10px"></div>
                                        <img src="../images/main-images/top-left.jpg" alt="Place ad in top left banner." class="fluid-image" id="top-left">
                                </div>
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Place Ad In Top Right Banner</label>
                                    <div class="spacer-10px"></div>
                                    <input type="checkbox" name="disable-ads" id="disable-ads">
                                        <div class="spacer-10px"></div>
                                        <img src="../images/main-images/top-right.jpg" alt="Place ad in top right banner." class="fluid-image" id="top-right">
                                </div>
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Place Ad In Bottom Left Banner</label>
                                    <div class="spacer-10px"></div>
                                    <input type="checkbox" name="disable-ads" id="disable-ads">
                                        <div class="spacer-10px"></div>
                                        <img src="../images/main-images/bottom-left.jpg" alt="Place ad in bottom left banner." class="fluid-image" id="bottom-left">
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Place Ad In Bottom Right Banner</label>
                                    <div class="spacer-10px"></div>
                                    <input type="checkbox" name="disable-ads" id="disable-ads">
                                        <div class="spacer-10px"></div>
                                        <img src="../images/main-images/bottom-right.jpg" alt="Place ad in bottom right banner." class="fluid-image" id="bottom-right">
                                </div>
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Place Ad In Two Top Banners</label>
                                    <div class="spacer-10px"></div>
                                    <input type="checkbox" name="disable-ads" id="disable-ads">
                                        <div class="spacer-10px"></div>
                                        <img src="../images/main-images/two-top.jpg" alt="Place ad in two top banners." class="fluid-image" id="two-top">
                                </div>
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Place Ad In Two Bottom Banners</label>
                                    <div class="spacer-10px"></div>
                                    <input type="checkbox" name="disable-ads" id="disable-ads">
                                        <div class="spacer-10px"></div>
                                        <img src="../images/main-images/two-bottom.jpg" alt="Place ad in two bottom banners." class="fluid-image" id="two-bottom">
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Place Ad In All Banners</label>
                                    <div class="spacer-10px"></div>
                                    <input type="checkbox" name="disable-ads" id="disable-ads">
                                        <div class="spacer-10px"></div>
                                        <img src="../images/main-images/all-banners.jpg" alt="Place ad in all banners." class="fluid-image" id="all-banners">
                                </div>
                            </div>
                            <div>
                                <h3>Ad Campaign Total<br><span class="heading-primary" id="membership-one-price">$0.00</span></h3>
                                <p>The ad campaign total is based on all of the settings that were chosen above.</p>
                            </div>
                            <div class="form-submit-container">
                                <imput type="button" class="button-01 white-text" name="membership-settings-submit" id="membership-settings-submit">Submit</imput>
                            </div>
                        </form>
                    </div>
                    <div class="profile-section" id="services">
                        <h2>Services</h2>
                        <form action="" class="full-width-form">
                            <h3>Create Services</h3>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="vendor-service-title" class="input-label">Enter Your Service Title</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input type="text" id="vendor-service-title" class="search-input" placeholder="example: Wedding Portraits">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="vendor-service-price" class="input-label">Enter Your Service Price</label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input type="number" name="vendor-service-price" id="vendor-service-price" class="search-input" placeholder="example: 275.00">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="vendor-service-description" class="input-label">Enter Your Service Description</label>
                                <div class="spacer-10px"></div>
                                <span id="service-description-character-countdown">500</span> characters left
                                <div class="wysiwyg-container">
                                    <textarea name="vendor-service-description" id="vendor-service-description" class="full-width-textarea"></textarea>
                                </div>
                                <div class="spacer-20"></div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="vendor-service-date" class="input-label">Enter Available Dates (Optional)</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input type="date" name="vendor-service-date" id="vendor-service-date" class="search-input" placeholder="example: Musician, Caterer, Bartender">
                                    </div>
                                    <div class="spacer-10px"></div>
                                    <div class="form-submit-container">
                                        <a href="" class="button-03 button-link-text white-text">Add Date</a>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location" class="input-label">List of Available Dates</label>
                                    <div class="spacer-10px"></div>
                                    <div class="list-container">
                                        <ul class="category-ul">
                                            <li class="category-li"><div>04/19/23</div><br><div><button class="small-button primary white-text">Delete</button></div></li>
                                            <li class="category-li"><div>04/20/23</div><br><div><button class="small-button primary white-text">Delete</button></div></li>
                                            <li class="category-li"><div>07/29/23</div><br><div><button class="small-button primary white-text">Delete</button></div></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-submit-container">
                                <imput type="button" class="button-01 white-text" name="services-settings-submit" id="services-settings-submit">Submit</imput>
                            </div>
                        </form>
                        <form action="" class="full-width-form">
                            <h3>Delete Services</h3>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search-full-width">
                                    <label for="membership-01-billing-cycle" class="input-label">Set Services For Deletion</label>
                                    <div class="spacer-10px"></div>
                                    <div class="list-container-full-width" id="delete-services-display">
                                        <ul class="category-ul">
                                            <li class="category-li">
                                                <div>
                                                    <h4>Service Title Goes Here</h4>
                                                </div>
                                                <div>
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum eree. Excepteur sinte occaecat cupidatat none proident.
                                                </div>
                                                <br>
                                                <div class="service-date-listing">
                                                    <div class="service-date-container-small"><h5>10/28/22</h5></div>
                                                    <div class="service-date-container-small"><h5>10/28/22</h5></div>
                                                    <div class="service-date-container-small"><h5>10/28/22</h5></div>
                                                    <div class="service-date-container-small"><h5>10/28/22</h5></div>
                                                    <div class="service-date-container-small"><h5>10/28/22</h5></div>
                                                    <div class="service-date-container-small"><h5>10/28/22</h5></div>
                                                    <div class="service-date-container-small"><h5>10/28/22</h5></div>
                                                    <div class="service-date-container-small"><h5>10/28/22</h5></div>
                                                    <div class="service-date-container-small"><h5>10/28/22</h5></div>
                                                    <div class="service-date-container-small"><h5>10/28/22</h5></div>
                                                </div>
                                                <div>
                                                    <button class="small-button primary white-text">Delete</button>&nbsp;&nbsp;<span class="stop-text"><b>Set to be deleted.</b></span></div><br><div><button class="small-button primary white-text">Cancel Delete</button>
                                                </div>
                                                <div class="spacer-10px"></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-submit-container">
                                <imput type="button" class="button-01 white-text" name="services-delete-submit" id="services-delete-submit">Submit</imput>
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
        MyHelpers::includeWithVariables('../layouts/footer.php', [], $print = true);
        ?>
	</div>
	<script src="<?php echo SITE_LINK; ?>js/jquery.min.js"></script>
    <script src="<?php echo SITE_LINK; ?>js/pages/vendors/settings.js"></script>
    <script src="../js/custom.js"></script>
</body>
</html>