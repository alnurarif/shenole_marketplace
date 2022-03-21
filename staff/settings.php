<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Client;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\StaffRepository;
use Shenole_project\helpers\MyHelpers;

$isStaffLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'staff', new StaffRepository);

if(!$isStaffLoggedIn){
	header("Location: ".SITE_LINK_STAFF."login.php");
    exit();
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
                        <form action="" class="full-width-form">
                            <h3>Staff Member Info</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="staff-username" class="input-label">Enter Your Desired Username</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input type="text" id="staff-username" class="search-input" placeholder="example: JohnyBoy123">
                                    </div>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">First Name</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input type="text" id="staff-first-name" class="search-input" placeholder="example: John">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Last Name</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input type="text" id="staff-last-name" class="search-input" placeholder="example: Smith">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Location</label>
                                    <div class="spacer-10px"></div>
                                    <select name="state-01" class="search-input-mini">
                                        <option value="" class="select-option-01">State</option>
                                        <option value="" class="select-option-01"></option>
                                    </select>
                                </div>
                            </div>
                            <h3>Change Your Login Email</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Enter New Login Email</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input type="text" id="client-login-email-setting" class="search-input" placeholder="example: mymail@email.com">
                                    </div>
                                </div>
                            </div>
                            <h3>Change Your Password</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Enter New Password</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input type="text" id="client-new-password-setting" class="search-input" placeholder="example: mymail@email.com">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="category" class="input-label">Confirm New Password</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input type="text" id="client-confirm-new-password" class="search-input" placeholder="example: mymail@email.com">
                                    </div>
                                </div>
                            </div>
                            <div class="form-submit-container">
                                <imput type="button" class="button-01 white-text" id="majestic-vendor-categories-submit">Submit</imput>
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
                                        <input type="text" id="staff-paypal-email-setting" class="search-input" placeholder="example: mymail@email.com">
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