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


?>
<!DOCTYPE html>
<html lang="en">
<?php MyHelpers::includeWithVariables('./layouts/head_section.php', [], $print = true); ?>
<body>
	<div class="genesis-container">
		<?php 
		MyHelpers::includeWithVariables('./layouts/top_nav.php', ['isClientLoggedIn' => $isClientLoggedIn, 'isVendorLoggedIn' => $isVendorLoggedIn], $print = true);
		?>
		<div class="main-body-content">
            <section>
                <div class="ad-space-type01-desktop">

                </div>
                <!-- <div class="ad-space-type01-mobile">

                </div> -->
                <div class="content-container-center">
                    <div class="vendor-profile-container">
                        <div class="vendor-profile-header-container">
                            <div class="vendor-profile-main-photo">
    
                            </div>
                            <div class="vendor-profile-main-overview">
                                <div class="profile-header-title">
                                    Vendor's Name Here And It May Be A Long Name
                                </div>
                                <div class="profile-sub-header">
                                    <h4>Musician from Tampa, FL</h4>
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
                                    <div class="overview-icon-text">Will travel up to 50 miles.</div>
                                </div>
                                <div class="profile-header-icon-description-container">
                                    <img src="<?php echo SITE_LINK; ?>images/money.png" alt="Travel range." class="icon-primary">
                                    <div class="overview-icon-text">Starting at $300 per event.</div>
                                </div>
                                <hr class="divider">
                                <a href="" class="button-link-text">
                                    <div class="button-02">
                                        Call (863) 482-9988
                                    </div>
                                </a>
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
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
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
                <div class="ad-space-type01-desktop">
                    
                </div>
            </section>
        </div>
	</div>
	<script src="<?php echo SITE_LINK; ?>js/jquery.min.js"></script>
	<script src="<?php echo SITE_LINK; ?>js/pages/vendor.js"></script>
</body>
</html>