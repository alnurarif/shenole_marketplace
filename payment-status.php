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
$dashboard_link = '';
if((isset($isClientLoggedIn) && $isClientLoggedIn) || (isset($isVendorLoggedIn) && $isVendorLoggedIn)){
    if(isset($isClientLoggedIn) && $isClientLoggedIn){
        $dashboard_link = SITE_LINK_CLIENT.'dashboard.php';
    }elseif(isset($isVendorLoggedIn) && $isVendorLoggedIn){
        $dashboard_link = SITE_LINK_VENDOR.'dashboard.php';;
    }
}else{
    header("Location: ".SITE_LINK);
    exit();
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
                    <div class="vendor-profile-container">
                        <div class="vendor-profile-header-container">
                        </div>
                        <div class="profile-section">
                            <?php if(isset($_GET['status']) && $_GET['status'] == 'success'){ ?>
                            <div id="successful-transaction" class="center-text">
                                <h2>Your Transaction Was Successful!</h2>
                                <a href="<?php echo $dashboard_link; ?>" class="button-03 button-link-text white-text">Back To Your Dashboard</a>
                                <div class="spacer-10px"></div>
                                <div class="spacer-10px"></div>
                                <div class="spacer-10px"></div>
                                <div class="spacer-10px"></div>
                                <img src="./images/main-images/transaction-successful.jpg" class="fluid-image" alt="">
                            </div>
                            <?php } ?>
                            <?php if(isset($_GET['status']) && $_GET['status'] == 'fail'){ ?>
                            <div id="bad-transaction" class="center-text">
                                <h2>Something Went Wrong. Try Again!</h2>
                                <a href="<?php echo $dashboard_link; ?>" class="button-03 button-link-text white-text">Back To Your Shopping Cart</a>
                                <div class="spacer-10px"></div>
                                <div class="spacer-10px"></div>
                                <div class="spacer-10px"></div>
                                <div class="spacer-10px"></div>
                                <img src="./images/main-images/transaction-error.jpg" class="fluid-image" alt="">
                            </div>
                            <?php } ?>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                        </div>
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
    </div>
    <!-- PLACE JS SCRIPT TAGS HERE -->

    <!-- THIS JAVASCRIPT SCRIPT TAG MUST BE THE LAST SCRIPT TAG ON THIS PAGE -->
    <script src="<?php echo SITE_LINK; ?>js/jquery.min.js"></script>
	<script src="<?php echo SITE_LINK; ?>js/custom.js"></script>
	<script src="<?php echo SITE_LINK; ?>js/cart_modal.js"></script>
</body>
</html>