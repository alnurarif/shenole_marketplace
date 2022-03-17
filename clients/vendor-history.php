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
                                <hr class="divider">
                                <h3>Total Paid To Vendor:</h3>
                                <h3 class="primary-text">$780.00</h3>
                                <div class="spacer-1rem"></div>
                                <h3>Total Messages:</h3>
                                <h3 class="primary-text">43</h3>
                                <div class="spacer-1rem"></div>
                                <h3>Total Services Booked:</h3>
                                <h3 class="primary-text">2</h3>
                                <div class="spacer-1rem"></div>
                            </div>
                        </div>
                        <div class="vendor-profile-nav">
                            <div class="profile-nav-link">Transactions</div>
                            <div class="profile-nav-link">Messages</div>
                            <div class="profile-nav-link">Bookings</div>
                        </div>
                        <div class="profile-section" id="transactions">
                            <h2>Transactions</h2>
                            <div class="custom-table-container-01">
                                <div class="custom-table-heading-01">
                                    <div class="table-row-content-container-date">
                                        date
                                    </div>
                                    <div class="table-row-content-container-item">
                                        item
                                    </div>
                                    <div class="table-row-content-container-qty">
                                        quantity
                                    </div>
                                    <div class="table-row-content-container-price">
                                        price
                                    </div>
                                </div>
                                <div class="custom-table-row-01">
                                    <div class="table-row-content-container-date">
                                        09/28/22
                                    </div>
                                    <div class="table-row-content-container-item">
                                        Lorem ipsum dolor sit amet consectetur adipiscing elit
                                    </div>
                                    <div class="table-row-content-container-qty">
                                        2
                                    </div>
                                    <div class="table-row-content-container-price">
                                        $8,995.00
                                    </div>
                                </div>
                                <div class="custom-table-row-01">
                                    <div class="table-row-content-container-date">
                                        09/28/22
                                    </div>
                                    <div class="table-row-content-container-item">
                                        Lorem ipsum dolor sit amet consectetur adipiscing elit
                                    </div>
                                    <div class="table-row-content-container-qty">
                                        2
                                    </div>
                                    <div class="table-row-content-container-price">
                                        $8,995.00
                                    </div>
                                </div>
                                <div class="custom-table-row-01">
                                    <div class="table-row-content-container-date">
                                        09/28/22
                                    </div>
                                    <div class="table-row-content-container-item">
                                        Lorem ipsum dolor sit amet consectetur adipiscing...
                                    </div>
                                    <div class="table-row-content-container-qty">
                                        2
                                    </div>
                                    <div class="table-row-content-container-price">
                                        $8,995.00
                                    </div>
                                </div>
                            </div>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="pagination-container">
                                <div class="pagination-link-current">1</div>
                                <a href="" class="white-text"><div class="pagination-link">2</div></a>
                            </div>
                        </div>
                        <div class="profile-section" id="messages">
                            <h2>Messages</h2>
                            <div class="listing-search-header">
                                <form action="" class="full-width-form">
                                    <div class="form-input-container">
                                        <div class="form-input-search">
                                            <label for="keywords" class="input-label">Comma Separated Keywords</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="keyword-vendor-search" class="search-input" placeholder="party band">
                                            </div>
                                        </div>
                                        <div class="form-input-select">
                                            <label for="category" class="input-label">Vendor Location</label>
                                            <div class="spacer-10px"></div>
                                            <select name="" id="" class="search-select">
                                                <option value="">Select State</option>
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
                                            <label for="sort-by-date" class="input-label">Sort By Date</label>
                                            <div class="spacer-10px"></div>
                                            <select name="sort-by-date" id="sort-by-date" class="search-select">
                                                <option value="">Sort Messages</option>
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
                        </div>
                        <div class="profile-section" id="bookings">
                            <h2>Bookings</h2>
                            <div class="custom-table-container-01">
                                <div class="custom-table-heading-01">
                                    <div class="table-row-content-container-date">
                                        date
                                    </div>
                                    <div class="table-row-content-container-booking">
                                        booking
                                    </div>
                                </div>
                                <div class="custom-table-row-01">
                                    <div class="table-row-content-container-date">
                                        09/28/22
                                    </div>
                                    <div class="table-row-content-container-booking">
                                        Lorem ipsum dolor sit amet consectetur adipiscing...
                                    </div>
                                </div>
                                <div class="custom-table-row-01">
                                    <div class="table-row-content-container-date">
                                        09/28/22
                                    </div>
                                    <div class="table-row-content-container-booking">
                                        Lorem ipsum dolor sit amet consectetur adipiscing...
                                    </div>
                                </div>
                                <div class="custom-table-row-01">
                                    <div class="table-row-content-container-date">
                                        09/28/22
                                    </div>
                                    <div class="table-row-content-container-booking">
                                        Lorem ipsum dolor sit amet consectetur adipiscing...
                                    </div>
                                </div>
                            </div>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="pagination-container">
                                <div class="pagination-link-current">1</div>
                                <a href="" class="white-text"><div class="pagination-link">2</div></a>
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
        MyHelpers::includeWithVariables('../layouts/footer.php', [], $print = true);
        ?>
	</div>
	<script src="../js/jquery.min.js"></script>
	<script src="../js/pages/index_page.js"></script>
</body>
</html>