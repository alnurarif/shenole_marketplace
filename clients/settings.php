<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Client;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\ClientRepository;
use Shenole_project\helpers\MyHelpers;

$isClientLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'client', new ClientRepository);

if(!$isClientLoggedIn){
	header("Location: ".SITE_LINK."login.php");
    exit();
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
                        <div class="profile-nav-link">Account</div>
                        <div class="profile-nav-link">Paypal</div>
                        <div class="profile-nav-link">Upgrades</div>
                    </div>
                    <div class="profile-section" id="client-account">
                        <h2>Account</h2>
                        <form action="" class="full-width-form">
                            <h3>Change Your Login Email</h3>
                            <div class="form-input-container">
                                
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
                    <div class="profile-section" id="client-paypal">
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
                        <h2>Upgrades</h2>
                        <form action="" class="full-width-form">
                            <h3>Advertisement Settings</h3>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <div>
                                        <label for="disable-ads" class="input-label">Disable Advertisements ($2.99/month)</label>
                                        <div class="spacer-10px"></div>
                                        <input type="checkbox" name="disable-ads" id="disable-ads">
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                    </div>
                                </div>
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
	<script src="../js/jquery.min.js"></script>
	<script src="../js/pages/index_page.js"></script>
</body>
</html>