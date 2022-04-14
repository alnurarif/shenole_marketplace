<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Client;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\StaffRepository;
use Shenole_project\helpers\MyHelpers;

$isStaffLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'staff', new StaffRepository);

if(!$isStaffLoggedIn){
	header("Location: ".SITE_LINK_STAFF."login.php");
    exit();
}

$clients = Client::get();

$show_all_clients = '';
foreach($clients as $client){
	$status = '';
	if($client->account_status == 'A'){
		$status = 'Active';
	}elseif($client->account_status == 'I'){
		$status = 'Inactive';
	}elseif($client->account_status == 'S'){
		$status = 'Suspended';
	}
	$show_all_clients .= '<tr>';
		$show_all_clients .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$client->first_name.'</td>';
		$show_all_clients .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$client->last_name.'</td>';
		$show_all_clients .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$client->email.'</td>';
		$show_all_clients .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$status.'</td>';
		$show_all_clients .= '<td class="fs_14 lh_22 text_dark_ash textcenter">';
			$show_all_clients .= '<form action="client-details.php" method="post" class="floatright">';
				$show_all_clients .= '<input type="hidden" name="client_detail_show_operation" value="1"/>';
				$show_all_clients .= '<input type="hidden" name="client_id" value="'.$client->id.'"/>';
				$show_all_clients .= '<button type="submit" class="fs_14 lh_22 w_100 textcenter display_inline_block font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer">See Details</button>';
			$show_all_clients .= '</form>';
			$show_all_clients .= '<form action="client-edit.php" method="post" class="floatleft">';
				$show_all_clients .= '<input type="hidden" name="client_id" value="'.$client->id.'"/>';
				$show_all_clients .= '<input type="hidden" name="client_edit_show_operation" value="1"/>';
				$show_all_clients .= '<button type="submit" class="fs_14 lh_22 w_100 textcenter display_inline_block font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer">Edit</button>';
			$show_all_clients .= '</form>';
	$show_all_clients .= '</tr>';
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
            </section>
            <div class="pagination-container">
                <div class="pagination-link-current">1</div>
                <a href="" class="white-text"><div class="pagination-link">2</div></a>
            </div>
		</div>
		<?php 
        MyHelpers::includeWithVariables('../layouts/footer.php', [], $print = true);
        ?>
	</div>
	<script src="../js/jquery.min.js"></script>
	<script src="../js/pages/index_page.js"></script>
    <?php 
	MyHelpers::includeWithVariables('../layouts/common_footer.php', [], $print = true);
	?>
</body>
</html>