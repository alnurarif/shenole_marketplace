<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Vendor;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\VendorRepository;
use Shenole_project\helpers\MyHelpers;

$isVendorLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'vendor', new VendorRepository);

if(!$isVendorLoggedIn){
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
                    <div class="listing-search-header">
                        <form action="" class="full-width-form">
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="client-username-search" class="input-label">Client Username</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input type="text" id="client-username-search" class="search-input" placeholder="Enter Username">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="client-location-search" class="input-label">Location City, State</label>
                                    <div class="spacer-10px"></div>
                                    <input type="text" id="client-location-search" class="search-input" placeholder="Orlando, FL">
                                </div>
                                <div class="form-input-select">
                                    <label for="client-sort-alphabetically" class="input-label">Sort Alphabetically</label>
                                    <div class="spacer-10px"></div>
                                    <select name="client-sort-alphabetically" id="client-sort-alphabetically" class="search-select-small">
                                        <option value="">Sort Clients Username</option>
                                        <option value="a-z">A - Z</option>
                                        <option value="z-a">Z - A</option>
                                    </select>
                                </div>
                                <div class="form-input-select">
                                    <label for="client-sort-status" class="input-label">Sort Status</label>
                                    <div class="spacer-10px"></div>
                                    <select name="client-sort-status" id="client-sort-status" class="search-select-small">
                                        <option value="">Sort Client Status</option>
                                        <option value="a-z">Active</option>
                                        <option value="z-a">Inactive</option>
                                        <option value="z-a">Suspended</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-submit-container">
                                <input type="submit" class="button-01 primary white-text" value="Search">
                            </div>
                        </form>
                    </div>
                    <div class="listing-content-container">
                        <article class="listing-card2">
                            <div class="listing-card-vendor-name-container">
                                <h3 class="listing-card-vendor-name">
                                    <a href="" class="listing-card-vendor-name-link">Client Username Here And It May Be A Long Name</a>
                                </h3>
                            </div>
                            <ul class="listing-card-ul-01">
                                <li class="listing-card-li-01"><img class="listing-card-li-icon" src="../images/icons/maps-and-flags.png">Tampa, FL</li>
                            </ul>
                            <div class="spacer-10px"></div>
                            <div class="listing-card-footer">
                                <a href="" class="button-03 button-link-text white-text">Find Out More</a>
                            </div>
                        </article>
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
    <script src="<?php echo SITE_LINK; ?>js/pages/vendors/clients.js"></script>
    <script src="../js/custom.js"></script>
</body>
</html>