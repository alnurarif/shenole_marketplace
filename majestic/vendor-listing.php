<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Vendor;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\MajesticRepository;
use Shenole_project\helpers\MyHelpers;

$isMajesticLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'majestic', new MajesticRepository);

if(!$isMajesticLoggedIn){
	header("Location: ".SITE_LINK_MAJESTIC."login.php");
    exit();
}

$vendors = Vendor::get();

$show_all_vendors = '';
foreach($vendors as $vendor){
	$status = '';
	if($vendor->account_status == 'A'){
		$status = 'Active';
	}elseif($vendor->account_status == 'I'){
		$status = 'Inactive';
	}elseif($vendor->account_status == 'S'){
		$status = 'Suspended';
	}
	$show_all_vendors .= '<tr>';
		$show_all_vendors .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$vendor->first_name.'</td>';
		$show_all_vendors .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$vendor->last_name.'</td>';
		$show_all_vendors .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$vendor->email.'</td>';
		$show_all_vendors .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$status.'</td>';
		$show_all_vendors .= '<td class="fs_14 lh_22 text_dark_ash textcenter">';
			$show_all_vendors .= '<form action="vendor-details.php" method="post" class="floatright">';
				$show_all_vendors .= '<input type="hidden" name="vendor_detail_show_operation" value="1"/>';
				$show_all_vendors .= '<input type="hidden" name="vendor_id" value="'.$vendor->id.'"/>';
				$show_all_vendors .= '<button type="submit" class="fs_14 lh_22 w_100 textcenter display_inline_block font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer">See Details</button>';
			$show_all_vendors .= '</form>';
			$show_all_vendors .= '<form action="vendor-edit.php" method="post" class="floatleft">';
				$show_all_vendors .= '<input type="hidden" name="vendor_id" value="'.$vendor->id.'"/>';
				$show_all_vendors .= '<input type="hidden" name="vendor_edit_show_operation" value="1"/>';
				$show_all_vendors .= '<button type="submit" class="fs_14 lh_22 w_100 textcenter display_inline_block font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer">Edit</button>';
			$show_all_vendors .= '</form>';
	$show_all_vendors .= '</tr>';
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
                                <div class="form-input-search">
                                    <label for="location" class="input-label">Location City, State</label>
                                    <div class="spacer-10px"></div>
                                    <input type="text" id="keyword-vendor-search" class="search-input" placeholder="Orlando, FL">
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-select">
                                    <label for="distance" class="input-label">Distance From Location</label>
                                    <div class="spacer-10px"></div>
                                    <select name="" id="" class="search-select">
                                        <option value="">Select Distance</option>
                                        <option value="">Up to 10 Miles</option>
                                        <option value="">Up to 25 Miles</option>
                                        <option value="">Up to 50 Miles</option>
                                        <option value="">Up to 75 Miles</option>
                                        <option value="">Up to 100 Miles</option>
                                        <option value="">Up to 150 Miles</option>
                                        <option value="">Up to 200 Miles</option>
                                        <option value="">Up to 250 Miles</option>
                                        <option value="">Up to 500 Miles</option>
                                        <option value="">Up to 750 Miles</option>
                                        <option value="">Up to 1,000 Miles</option>
                                        <option value="">Continental US</option>
                                        <option value="">All 50 States</option>
                                    </select>
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
                                    <label for="star-rating" class="input-label">Star Rating</label>
                                    <div class="spacer-10px"></div>
                                    <select name="" id="" class="search-select">
                                        <option value="">Select Rating</option>
                                        <option value="1-star">1 Star</option>
                                        <option value="2-star">2 Star</option>
                                        <option value="3-star">3 Star</option>
                                        <option value="4-star">4 Star</option>
                                        <option value="5-star">5 Star</option>
                                    </select>
                                </div>
                                <div class="form-input-select">
                                    <label for="sort" class="input-label">Sort</label>
                                    <div class="spacer-10px"></div>
                                    <select name="" id="" class="search-select">
                                        <option value="">Sort Vendors</option>
                                        <option value="a-z">A - Z</option>
                                        <option value="z-a">Z - A</option>
                                        <option value="$">$</option>
                                        <option value="$$$">$$$</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-submit-container">
                                <input type="submit" class="button-01 primary white-text" value="Search">
                            </div>
                        </form>
                    </div>
                    <div class="listing-content-container">
                        <article class="listing-card">
                            <div class="listing-card-image">
                                <img class="listing-image" src="" alt="">
                            </div>
                            <div class="listing-card-vendor-name-container">
                                <h3 class="listing-card-vendor-name">
                                    <a href="" class="listing-card-vendor-name-link">Vendor's Name Here And It May Be A Long Name</a>
                                </h3>
                            </div>
                            <div class="listing-card-vendor-rating-container">
                                <!-- <div class="listing-card-star-rating">
                                    <img class="review-star-full-small" src="./images/icons/star.png" alt="Review star">
                                    <img class="review-star-full-small" src="./images/icons/star.png" alt="Review star">
                                    <img class="review-star-full-small" src="./images/icons/star.png" alt="Review star">
                                    <img class="review-star-full-small" src="./images/icons/star.png" alt="Review star">
                                    <img class="review-star-full-small" src="./images/icons/star.png" alt="Review star">
                                </div>
                                <div class="listing-card-review-info">
                                    <span class="review-star-count">5</span>
                                    <span class="review-counter">(1)</span>
                                </div> -->
                                <div class="listing-card-review-info margin-auto">
                                    <span class="review-star-count secondary-text">No Rating Yet</span>
                                </div>
                            </div>
                            <ul class="listing-card-ul-01">
                                <li class="listing-card-li-01"><img class="listing-card-li-icon" src="../images/icons/maps-and-flags.png">Tampa, FL</li>
                                <li class="listing-card-li-01"><img class="listing-card-li-icon" src="../images/icons/user.png">Musician</li>
                            </ul>
                            <div class="listing-card-text-box">
                                <p class="listing-card-description-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magnaw...</p>
                            </div>
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