<?php
session_start();
require "../start.php";
use Shenole_project\models\Vendor;
use Shenole_project\models\Staff;
use Shenole_project\helpers\Validator;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\StaffRepository;
use Shenole_project\helpers\MyHelpers;

$isStaffLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'staff', new StaffRepository);

if(!$isStaffLoggedIn){
	header("Location: ".SITE_LINK_STAFF."login.php");
    exit();
}

if($_POST){
	$vendor_id = $_POST['vendor_id'];
	if(isset($_POST['vendor_detail_show_operation'])){
		$vendor = Vendor::find($vendor_id);
	}
	if(isset($_POST['vendor_login_operation'])){
		$vendor = Vendor::find($vendor_id);

		$generator = new RandomStringGenerator;
		$tokenLength = 60;
		$login_token = $generator->generate($tokenLength);

		$vendor->login_token = $login_token;
		$vendor->save();


		$before_login_session_array = isset($_SESSION['logged_in_as']) ? $_SESSION['logged_in_as'] : [];
		$_SESSION['logged_in_as'] = [];
		$login_session_array = [
			"logged_in_user_type" => 'vendor',
			"looged_in_token" => $vendor->login_token
		];

		$found = false;
		foreach($before_login_session_array as &$single){
			if($single['logged_in_user_type'] == 'vendor'){
				$found = true;
				$single = $login_session_array;
			}
			if($single['logged_in_user_type'] == 'client'){
				$single = [
					"logged_in_user_type" => 'client',
					"looged_in_token" => ''
				];
			}
		}
		if(!$found){
			array_push($before_login_session_array, $login_session_array);
		}

		$_SESSION['logged_in_as'] = $before_login_session_array;
		
		header("Location: ".SITE_LINK."vendors/dashboard.php");
    	exit();
	}
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
                    <div class="vendor-profile-container">
                        <div class="vendor-profile-header-container">
                            <div class="vendor-profile-main-photo">
    
                            </div>
                            <div class="vendor-profile-main-overview">
                                <div class="flex-container space-between">
                                    <h4>Account Status: <span class="stop-text" id="status-text">Inactive</span></h4>
                                    <h4><a href="">Login As User</a></h4>
                                </div>
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
                            <div class="profile-nav-link">Status</div>
                            <div class="profile-nav-link">Transactions</div>
                            <div class="profile-nav-link">Bookings</div>
                            <div class="profile-nav-link">Payment History<span class="overdue-one major-alert white-text" id="overdue-alert-one">Overdue</span></div>
                            <div class="profile-nav-link">Notes</div>
                        </div>
                        <div class="profile-section" id="status-vendor">
                            <h2>Status</h2>
                            <div class="vendor-profile-header-container">
                                <form action="" class="full-width-form">
                                    <h3>Set User Status</h3>
                                    <div class="form-input-container">
                                        <div class="form-input-search">
                                            <label for="html">Active</label>
                                            <input type="radio" id="active" name="active" value="active">
                                        </div>
                                        <div class="form-input-search">
                                            <label for="html">Inactive</label>
                                            <input type="radio" id="inactive" name="inactive" value="inactive">
                                        </div>
                                        <div class="form-input-search">
                                            <label for="html">Suspended</label>
                                            <input type="radio" id="suspended" name="suspended" value="suspended">
                                        </div>
                                    </div>
                                    <div class="form-submit-container">
                                        <imput type="button" class="button-01 white-text" id="staff-client-status-submit">Submit</imput>
                                    </div>
                                </form>
                            </div>
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
                        <div class="profile-section" id="transactions">
                            <h2>Payment History</h2>
                            <div class="custom-table-container-01">
                                <div class="custom-table-heading-01">
                                    <div class="table-row-content-container-date">
                                        date
                                    </div>
                                    <div class="table-row-content-container-item">
                                        item
                                    </div>
                                    <!-- <div class="table-row-content-container-qty">
                                        quantity
                                    </div> -->
                                    <div class="table-row-content-container-price">
                                        price
                                    </div>
                                </div>
                                <div class="custom-table-row-01">
                                    <div class="table-row-content-container-date">
                                        09/28/22
                                    </div>
                                    <div class="table-row-content-container-item">
                                        Monthly Subscription<span class="overdue-one major-alert white-text" id="overdue-alert-one">Overdue</span>
                                    </div>
                                    <!-- <div class="table-row-content-container-qty">
                                        2
                                    </div> -->
                                    <div class="table-row-content-container-price">
                                        $19.95
                                    </div>
                                </div>
                                <div class="custom-table-row-01">
                                    <div class="table-row-content-container-date">
                                        09/28/22
                                    </div>
                                    <div class="table-row-content-container-item">
                                        Ad Space (1 month)<span class="overdue-one major-alert white-text" id="overdue-alert-one">Overdue</span>
                                    </div>
                                    <!-- <div class="table-row-content-container-qty">
                                        2
                                    </div> -->
                                    <div class="table-row-content-container-price">
                                        $12.00
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
                        <div class="profile-section" id="notes-vendor">
                            <h2>Notes</h2>
                            <form action="" class="full-width-form">
                                <span id="vendor-description-character-countdown">2500</span> characters left
                                <div class="wysiwyg-container">
                                    <textarea class="full-width-textarea modal-textarea"></textarea>
                                </div>
                                <div class="spacer-20"></div>
                                <div class="form-submit-container-modal">
                                    <imput type="button" class="button-01 white-text" id="client-support-submit">Submit</imput>
                                </div>
                            </form>
                            <div class="custom-table-container-01">
                                <div class="custom-table-heading-01">
                                    <div class="table-row-content-container-date">
                                        date
                                    </div>
                                    <div class="table-row-content-container-item">
                                        content
                                    </div>
                                    <div class="table-row-content-container-price">
                                        action
                                    </div>
                                </div>
                                <div class="custom-table-row-01">
                                    <div class="table-row-content-container-date">
                                        09/30/22
                                    </div>
                                    <div class="table-row-content-container-item">
                                        Lorem ipsum dolor sit amet...
                                    </div>
                                    <div class="table-row-content-container-price">
                                        <a href="">
                                            <div class="button-03 white-text">
                                                view
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="custom-table-row-01">
                                    <div class="table-row-content-container-date">
                                        09/28/22
                                    </div>
                                    <div class="table-row-content-container-item">
                                        Lorem ipsum dolor sit amet...
                                    </div>
                                    <div class="table-row-content-container-price">
                                        <a href="">
                                            <div class="button-03 white-text">
                                                view
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="custom-table-row-01">
                                    <div class="table-row-content-container-date">
                                        08/28/22
                                    </div>
                                    <div class="table-row-content-container-item">
                                        Lorem ipsum dolor sit amet...
                                    </div>
                                    <div class="table-row-content-container-price">
                                        <a href="">
                                            <div class="button-03 white-text">
                                                view
                                            </div>
                                        </a>
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
            </section>
		</div>
		<div class="system-modal" id="vendor-notes-modal">
            <div class="modal-content">
                <div class="flex-container space-between">
                    <h4 class="white-text">Note Date: <span class="white-text">02/19/22</span></h4>
                    <img src="../images/icons/close.png" class="modal-close-icon" alt="Icon to close the modal.">
                </div>
                <div class="modal-note-window-display">
                    
                </div>
            </div>
        </div>
		<?php 
        MyHelpers::includeWithVariables('../layouts/footer.php', [], $print = true);
        ?>
	</div>
	<script src="../js/jquery.min.js"></script>
</body>
</html>