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
                    <div class="profile-section" id="messages">
                        <h2>Messages</h2>
                        <div class="listing-search-header">
                            <form action="" class="full-width-form">
                                <div class="form-input-container">
                                    <div class="form-input-search">
                                        <label for="keywords" class="input-label">Comma Separated Keywords or Vendor Name</label>
                                        <div class="spacer-10px"></div>
                                        <div>
                                            <input type="text" id="keyword-vendor-search" class="search-input" placeholder="party band">
                                        </div>
                                    </div>
                                    <div class="form-input-select">
                                        <label for="category" class="input-label">Vendor Category</label>
                                        <div class="spacer-10px"></div>
                                        <select name="" id="" class="search-select">
                                            <option value="">Select Category</option>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-input-select">
                                        <label for="sort" class="input-label">Sort Alphabetically</label>
                                        <div class="spacer-10px"></div>
                                        <select name="" id="" class="search-select">
                                            <option value="">Sort Vendors</option>
                                            <option value="a-z">A - Z</option>
                                            <option value="z-a">Z - A</option>
                                        </select>
                                    </div>
                                    <div class="form-input-select">
                                        <label for="sort" class="input-label">Sort By Date</label>
                                        <div class="spacer-10px"></div>
                                        <select name="" id="" class="search-select">
                                            <option value="">Sort Vendors</option>
                                            <option value="newest">Newest First</option>
                                            <option value="oldest">Oldest First</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-submit-container">
                                    <input type="submit" class="button-01 primary white-text" value="Search Messages">
                                </div>
                            </form>
                        </div>
                        <div class="message-screen">
                            <div class="message-date-container">
                                <hr>
                                <div class="date-button">
                                    <span class="message-date">09 / 27 / 2022</span>
                                </div>
                                <hr>
                            </div>
                            <div class="message-container">
                                <div class="message-sidebar">
                                    <img src="" alt="" class="fluid-image message-profile-pic">
                                </div>
                                <div class="message-display-body">
                                    <h3 class="primary-text">Vendor Name</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                                    <div class="respond-button-container">
                                        <div class="respond-button">Respond</div>
                                    </div>
                                </div>
                                <div class="message-sidebar">
                                    <span class="message-time">12:00<br>PM</span>
                                </div>
                            </div>
                            <div class="message-container">
                                <div class="message-sidebar">
                                    <img src="" alt="" class="fluid-image message-profile-pic">
                                </div>
                                <div class="message-display-body">
                                    <h3>Client Username</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                                </div>
                                <div class="message-sidebar">
                                    <span class="message-time">1:45<br>PM</span>
                                </div>
                            </div>
                            <div class="message-date-container">
                                <hr>
                                <div class="date-button">
                                    <span class="message-date">09 / 28 / 2022</span>
                                </div>
                                <hr>
                            </div>
                            <div class="message-container">
                                <div class="message-sidebar">
                                    <img src="" alt="" class="fluid-image message-profile-pic">
                                </div>
                                <div class="message-display-body">
                                    <h3 class="primary-text">Vendor Name</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                    <div class="respond-button-container">
                                        <div class="respond-button">Respond</div>
                                    </div>
                                </div>
                                <div class="message-sidebar">
                                    <span class="message-time">10:58<br>AM</span>
                                </div>
                            </div>
                        </div>
                        <div class="spacer-10px"></div>
                        <div class="spacer-10px"></div>
                        <div class="spacer-10px"></div>
                        <div class="spacer-10px"></div>
                        <form action="" class="full-width-form">
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <h3>Message To Vendors</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="keywords" class="input-label">Enter Recipient Vendor</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input type="text" id="keyword-vendor-search" class="search-input" placeholder="example: photographer X">
                                    </div>
                                    <div class="spacer-10px"></div>
                                    <div class="form-input-select">
                                        <label for="category" class="input-label">Vendor Category</label>
                                        <div class="spacer-10px"></div>
                                        <select name="" id="" class="search-select">
                                            <option value="">Select Category</option>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="spacer-10px"></div>
                                    <div class="form-submit-container">
                                        <a href="" class="button-03 button-link-text white-text">Find Vendor</a>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location" class="input-label">Vendor List</label>
                                    <div class="spacer-10px"></div>
                                    <div class="list-container">
                                        <ul class="category-ul">
                                            <li class="category-li">
                                                <div class="vendor-list-photo">
                                                    <img src="" alt="" class="fluid-image">
                                                </div>
                                                <div class="spacer-10px"></div>
                                                <div>photographer X</div><br><div class="multi-button-container">
                                                    <button class="small-button primary white-text">Select</button>
                                                </div>
                                            </li>
                                            <li class="category-li">
                                                <div class="vendor-list-photo">
                                                    <img src="" alt="" class="fluid-image">
                                                </div>
                                                <div class="spacer-10px"></div>
                                                    <div>Photographer X</div><br><div class="multi-button-container">
                                                <button class="small-button primary white-text">Select</button>
                                                </div>
                                            </li>
                                            <li class="category-li">
                                                <div class="vendor-list-photo">
                                                    <img src="" alt="" class="fluid-image">
                                                </div>
                                                <div class="spacer-10px"></div>
                                                    <div>Photographer x</div><br><div class="multi-button-container">
                                                <button class="small-button primary white-text">Select</button>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <span id="vendor-description-character-countdown">2500</span> characters left
                            <div class="wysiwyg-container">
                                <textarea class="full-width-textarea"></textarea>
                            </div>
                            <h3>Message going to VENDOR NAME HERE</h3>
                            <div class="article-container" id="client-vendor-message">
                                <!-- Start 2500 Character Limit -->
                                <p>THIS IS WHERE THE TEXT FROM THE TEXTAREA ABOVE APPEARS AFTER THE "SEND" BUTTON IS CLICKED ON.</p>
                                <!-- End 2500 Character Limit -->
                            </div>
                            <div class="spacer-20"></div>
                            <div class="form-submit-container">
                                <imput type="button" class="button-01 white-text" id="client-message-send">Send</imput>
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