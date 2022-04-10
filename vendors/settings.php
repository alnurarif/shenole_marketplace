<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Vendor;
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
$vendor = Vendor::where('login_token',$login_token)->first();
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
                    <div class="profile-section" id="paypal">
                        <h2>Paypal</h2>
                        <form action="" class="full-width-form">
                            <h3>Paypal Email</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Enter Your Paypal Email</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input type="text" id="client-paypal-email-setting" class="search-input" placeholder="example: mymail@email.com">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location" class="input-label">Your Current Paypal Email</label>
                                    <div class="spacer-10px"></div>
                                    <ul class="category-ul">
                                        <li class="category-li"><div>No Paypal Email Saved At This Time</div></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-submit-container">
                                <imput type="button" class="button-01 white-text" id="majestic-vendor-categories-submit">Submit</imput>
                            </div>
                        </form>
                    </div>
                    <div class="profile-section" id="upgrades">
                        <h2>Membership Plans</h2>
                        <form action="" class="full-width-form">
                            <h3>Membership Selection</h3>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <div>
                                        <input type="checkbox" name="disable-ads" id="disable-ads">
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
                                        <input type="checkbox" name="disable-ads" id="disable-ads">
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
                                        <input type="checkbox" name="disable-ads" id="disable-ads">
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
                                        <input type="checkbox" name="disable-ads" id="disable-ads">
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
                                <imput type="button" class="button-01 white-text" name="membership-settings-submit" id="membership-settings-submit">Submit</imput>
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
                                    <label for="category" class="input-label">Place Ad In Top Left Banner</label>
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
</body>
</html>