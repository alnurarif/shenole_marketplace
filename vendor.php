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

if($_GET['vendor_uuid']){
    $vendor = Vendor::where('uuid',$_GET['vendor_uuid'])->with('categories','locations','locations.state')->first();
    if(empty($vendor)){
        header("Location: ".SITE_LINK."listings.php");
        exit();    
    }
}else{
    header("Location: ".SITE_LINK."listings.php");
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
                <div class="content-container-center">
                    <div class="vendor-profile-container">
                        <div class="vendor-profile-header-container">
                            <div class="vendor-profile-main-photo">
                                <img class="full h_full" onerror="this.src='<?php echo SITE_LINK; ?>images/no_image.jpg'" src="<?php echo SITE_LINK;?>images/vendors/<?php echo $vendor->profile_photo; ?>" alt="profile photo">
                            </div>
                            <div class="vendor-profile-main-overview">
                                <div class="profile-header-title">
                                    <?php echo $vendor->company_name; ?>
                                </div>
                                <div class="profile-sub-header">
                                    <h4><?php echo $vendor->categories[0]->category->name?> from <?php echo $vendor->locations[0]->location_city; ?>, <?php echo $vendor->locations[0]->state->short_name; ?></h4>
                                </div>
                                <div class="profile-header-reviews">
                                    <div class="profile-header-star-container">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>images/star.png" alt="Review star">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>images/star.png" alt="Review star">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>images/star.png" alt="Review star">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>images/star.png" alt="Review star">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>images/star.png" alt="Review star">
                                    </div>
                                    <div>
                                        <h4>0 <a href="">Reviews</a></h4>
                                    </div>
                                </div>
                                <div class="profile-header-icon-description-container">
                                    <img src="<?php echo SITE_LINK; ?>images/maps-and-flags.png" alt="Travel range." class="icon-primary">
                                    <div class="overview-icon-text">Will travel up to <?php echo $vendor->travel_distance; ?> miles.</div>
                                </div>
                                <div class="profile-header-icon-description-container">
                                    <img src="<?php echo SITE_LINK; ?>images/money.png" alt="Travel range." class="icon-primary">
                                    <div class="overview-icon-text">Starting at $<?php echo $vendor->starting_fee; ?> per event.</div>
                                </div>
                                <hr class="divider">
                                <?php if($vendor->locations[0]->location_phone != null && $vendor->locations[0]->location_phone != "" && $vendor->locations[0]->is_phone_number_visible == 1){ ?>
                                <a href="tel:<?php echo $vendor->locations[0]->location_phone; ?>" class="button-link-text">
                                    <div class="button-02">
                                        Call <?php echo $vendor->locations[0]->location_phone; ?>
                                    </div>
                                </a>
                                <?php } ?>
                                <div class="spacer-1rem"></div>
                                <a href="" class="button-link-text white-text">
                                    <div class="button-01 primary">
                                        Add To Favorites
                                    </div>
                                </a>
                                <div class="spacer-1rem"></div>
                                <a href="" class="button-link-text white-text">
                                    <div class="button-01 primary">
                                        Give A Review
                                    </div>
                                </a>
    
                            </div>
                        </div>
                        <div class="vendor-profile-nav">
                            <div class="profile-nav-link tab tab-active" id="tab_description">Description</div>
                            <div class="profile-nav-link tab" id="tab_services">Services</div>
                            <div class="profile-nav-link tab" id="tab_photos">Photos</div>
                            <div class="profile-nav-link tab" id="tab_videos">Videos</div>
                            <div class="profile-nav-link tab" id="tab_audio">Audio</div>
                            <div class="profile-nav-link tab" id="tab_reviews">Reviews</div>
                        </div>
                        <div class="profile-section" id="description">
                            <h2>Description</h2>
                            <?php echo $vendor->vendor_description; ?>

                            <h3>Contact Information</h3>
                            <div class="multicolumn-container">
                            <?php foreach($vendor->locations as $key=>$single_location){ ?>
                                <div class="profile-contact-info" id="<?php echo ($key == 0) ? 'primary-loction' : 'location-'.($key+1); ?>">
                                    <h4 class="primary-text"><?php echo ($key == 0) ? 'Primary Location' : 'Location #'.($key+1); ?></h4>
                                    <ul class="profile-location-ul">
                                        <li class="profile-location-li"><?php echo ($single_location->city_state_only == 0) ? $single_location->street_address_1 : '&nbsp;'; ?></li>
                                        <li class="profile-location-li"><?php echo ($single_location->city_state_only == 0) ? $single_location->street_address_2 : '&nbsp;'; ?></li>
                                        <li class="profile-location-li"><?php echo $single_location->location_city; ?>, <?php echo $single_location->state->short_name; ?></li>
                                        <li class="profile-location-li-zip"><?php echo ($single_location->city_state_only == 0) ? $single_location->location_zip_code : '&nbsp;'; ?></li>
                                        <?php if($single_location->location_phone != null && $single_location->location_phone != "" && $single_location->is_phone_number_visible == 1){ ?>
                                            <li class="profile-location-li"><a href="tel:<?php echo $single_location->location_phone; ?>" class="button-01 primary"><?php echo $single_location->location_phone; ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                        <div class="profile-section" id="services">
                            <h2>Services</h2>
                            <div class="service-card">
                                <div class="service-header">
                                    <h3 class="white-text">Service Title</h3>
                                    <h3 class="white-text">Service Price</h3>
                                </div>
                                <div class="service-body">
                                    <h3>Description</h3>
                                    <!-- Start 450 Character Limit -->
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum eree.</p>
                                    <!-- End 450 Character Limit -->
                                    <hr class="divider">
                                    <h3>Available Dates</h3>
                                    <h4>10/28/22</h4>
                                </div>
                                <div class="service-footer">
                                    <a href=""class="button-link-text white-text">
                                        <div class="button-01 primary">
                                            Message
                                        </div>
                                    </a>
                                    <a href="" class="button-link-text">
                                        <div class="button-02">
                                            Call (863) 482-9988
                                        </div>
                                    </a>
                                    <a href="" class="button-link-text white-text">
                                        <div class="button-01 primary">
                                            Buy
                                        </div>
                                    </a>
                                </div>   
                            </div>
                        </div>
                        <div class="profile-section" id="photos">
                            <h2>Photos</h2>
                            <div class="gallery-container">
                                <div class="photo-thumbnail-container"></div>
                            </div>
                        </div>
                        <div class="profile-section" id="videos">
                            <h2>Videos</h2>
                            <div class="gallery-container">
                                <iframe width="300" height="169" style="margin-bottom: 2rem;" src="https://www.youtube.com/embed/Ns3xgdCtCIA" title="YouTube video player" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="profile-section" id="audio">
                            <h2>Audio</h2>
                            <div class="gallery-container">
                                <div class="audio-thumbnail-container">
                                    <iframe width="100%" height="300" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/20637752&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true"></iframe><div style="font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;"><a href="https://soundcloud.com/shenole" title="shenole" target="_blank" style="color: #cccccc; text-decoration: none;">shenole</a> · <a href="https://soundcloud.com/shenole/hope" title="Hope" target="_blank" style="color: #cccccc; text-decoration: none;">Hope</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-section" id="reviews">
                            <h2>Reviews</h2>
                            <div class="main-review-container">
                                <div class="main-review-heading-container">
                                    <div class="main-review-rating">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>images/star.png" alt="Review star">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>images/star.png" alt="Review star">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>images/star.png" alt="Review star">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>images/star.png" alt="Review star">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>images/star.png" alt="Review star">
                                    </div>
                                    <div class="main-review-heading">
                                        Reviewed On 10/29/22
                                    </div>
                                    <div class="main-review-heading">
                                       By Anthony MacDonald
                                    </div>
                                </div>
                                <div class="main-review-text">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                </div>
                                <hr class="divider">
                                <div class="spacer-1rem"></div>
                            </div>
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
        <?php 
        MyHelpers::includeWithVariables('./layouts/footer.php', [], $print = true);
        ?>
	</div>
	<script src="<?php echo SITE_LINK; ?>js/jquery.min.js"></script>
	<script src="<?php echo SITE_LINK; ?>js/pages/vendor.js"></script>
	<script src="./js/custom.js"></script>
</body>
</html>