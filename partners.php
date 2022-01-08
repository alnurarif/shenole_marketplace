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
                    

                        <!-- ALL MAIN CONTENT FOR THE PAGE GOES HERE -->


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
            MyHelpers::includeWithVariables('./layouts/footer.php', [], $print = true);
            ?> 
        </div>
        <script src="./js/main.js"></script>
    </body>
</html>