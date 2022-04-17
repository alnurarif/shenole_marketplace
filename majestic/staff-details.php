<?php
session_start();
require "../start.php";
use Shenole_project\models\Staff;
use Shenole_project\models\Majestic;
use Shenole_project\helpers\Validator;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\MajesticRepository;
use Shenole_project\helpers\MyHelpers;

$isMajesticLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'majestic', new MajesticRepository);

if(!$isMajesticLoggedIn){
	header("Location: ".SITE_LINK_MAJESTIC."login.php");
    exit();
}

if($_POST){
	$staff_id = $_POST['staff_id'];
	if(isset($_POST['staff_detail_show_operation'])){
		$staff = Staff::find($staff_id);
	}
	if(isset($_POST['staff_login_operation'])){
		$staff = Staff::find($staff_id);

		$generator = new RandomStringGenerator;
		$tokenLength = 60;
		$login_token = $generator->generate($tokenLength);

		$staff->login_token = $login_token;
		$staff->save();


		$before_login_session_array = isset($_SESSION['logged_in_as']) ? $_SESSION['logged_in_as'] : [];
		$_SESSION['logged_in_as'] = [];
		$login_session_array = [
			"logged_in_user_type" => 'staff',
			"looged_in_token" => $staff->login_token
		];

		$found = false;
		foreach($before_login_session_array as &$single){
			if($single['logged_in_user_type'] == 'staff'){
				$found = true;
				$single = $login_session_array;
			}
		}
		if(!$found){
			array_push($before_login_session_array, $login_session_array);
		}
		$_SESSION['logged_in_as'] = $before_login_session_array;
		
		header("Location: ".SITE_LINK."staff/dashboard.php");
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
		MyHelpers::includeWithVariables('../layouts/top_nav.php', ['isMajesticLoggedIn' => $isMajesticLoggedIn], $print = true);
		?>
		<div class="main-body-content">
			<section class="section-type-01">
                <div class="content-container-center">
                    <div class="vendor-profile-container">
                        <div class="vendor-profile-header-container">
                            <div class="vendor-profile-main-photo">
                                <img src="../images/main-images/tech-staff.jpg" class="fluid-image" alt="Photo showing people of various races and genders.">
                            </div>
                            <div class="vendor-profile-main-overview">
                                <div class="flex-container space-between">
                                    <h4>Account Status: <span class="stop-text" id="status-text">Inactive</span></h4>
                                    <h4><a href="">Login As User</a></h4>
                                </div>
                                <div class="profile-header-title-client">
                                    Staff Member's Username
                                </div>
                                <hr class="divider">
                                <h3>Full Name:</h3>
                                <h3 class="primary-text"><span>Jonathon</span> <span>Rictersmidt</span></h3>
                                <div class="spacer-1rem"></div>
                                <h3>Location:</h3>
                                <h3 class="primary-text"><span>Miami</span>, <span>FL</span></h3>
                                <div class="spacer-1rem"></div>
                                <h3>Total Support Tickets Answered:</h3>
                                <h3 class="primary-text">2</h3>
                                <div class="spacer-1rem"></div>
                            </div>
                        </div>
                        <div class="vendor-profile-nav">
                            <div class="profile-nav-link" id="tag_status">Status</div>
                            <div class="profile-nav-link" id="tag_messages">Messages</div>
                            <div class="profile-nav-link" id="tag_support_tickets">Support Tickets</div>
                            <div class="profile-nav-link" id="tag_notes">Notes</div>
                        </div>
                        <div class="profile-section" id="status">
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
                        <div class="profile-section" id="messages">
                            <h2>Messages</h2>
                            <div class="listing-search-header">
                                <form action="" class="full-width-form">
                                    <div class="form-input-container">
                                        <div class="form-input-search">
                                            <label for="keywords" class="input-label">Comma Separated Keywords or Staff Members</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="keyword-vendor-search" class="search-input" placeholder="john smith">
                                            </div>
                                        </div>
                                        <div class="form-input-select">
                                            <label for="category" class="input-label">Staff Location</label>
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
                                                <option value="">Sort Staff</option>
                                                <option value="majestic-user">Majestic User</option>
                                                <option value="a-z">A - Z</option>
                                                <option value="z-a">Z - A</option>
                                            </select>
                                        </div>
                                        <div class="form-input-select">
                                            <label for="sort" class="input-label">Sort By Date</label>
                                            <div class="spacer-10px"></div>
                                            <select name="" id="" class="search-select">
                                                <option value="">Sort Staff</option>
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
                                        <h3 class="primary-text">Staff Name</h3>
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
                                        <h3>Majestic Username</h3>
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
                                        <h3 class="primary-text">Staff Name</h3>
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
                        </div>
                        <div class="profile-section" id="support_tickets">
                            <h2>Support Tickets</h2>
                            <div class="custom-table-container-01">
                                <div class="custom-table-heading-01">
                                    <div class="table-row-content-container-date">
                                        date
                                    </div>
                                    <div class="table-row-content-container-item">
                                        Ticket
                                    </div>
                                    <div class="table-row-content-container-price">
                                        action
                                    </div>
                                </div>
                                <div class="custom-table-row-01">
                                    <div class="table-row-content-container-date">
                                        09/28/22
                                    </div>
                                    <div class="table-row-content-container-item">
                                        <b>#A9A00001</b>&nbsp;&nbsp;Lorem ipsum dolor sit amet...
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
                                        <b>#A8B00001</b>&nbsp;&nbsp;Lorem ipsum dolor sit amet...
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
                                        <b>#C9H88008</b>&nbsp;&nbsp;Lorem ipsum dolor sit amet...
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
                        <div class="profile-section" id="notes">
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
		<div class="system-modal" id="client-notes-modal">
			<div class="modal-content">
				<div class="flex-container space-between">
					<h4 class="white-text">Note Date: <span class="white-text">02/19/22</span></h4>
					<img src="../images/icons/close.png" class="modal-close-icon" alt="Icon to close the modal.">
				</div>
				<div class="modal-note-window-display">
					
				</div>
			</div>
		</div> 
		<div class="system-modal" id="support-ticket-modal">
			<div class="modal-content">
				<div class="flex-container space-between">
					<h4 class="white-text">Support Ticket: #<span class="white-text">A9A00001</span></h4>
					<img src="../images/icons/close.png" class="modal-close-icon" alt="">
				</div>
				<div class="modal-message-window-receive">
					
				</div>
				<form action="" class="full-width-form modal-form">
					<span id="vendor-description-character-countdown">2500</span> characters left
					<div class="wysiwyg-container">
						<textarea class="full-width-textarea modal-textarea"></textarea>
					</div>
					<div class="spacer-20"></div>
					<div class="form-submit-container-modal">
						<imput type="button" class="button-01 white-text" id="client-support-submit">Submit</imput>
					</div>
				</form>
			</div>
		</div>

		<?php 
        MyHelpers::includeWithVariables('../layouts/footer.php', [], $print = true);
        ?>
	</div>
	<script src="<?php echo SITE_LINK; ?>js/jquery.min.js"></script>
    <script src="<?php echo SITE_LINK; ?>js/pages/majestic/staff-details.js"></script>
    <script src="../js/custom.js"></script>
</body>
</html>