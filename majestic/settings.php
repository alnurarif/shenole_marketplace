<?php
session_start();
require "../start.php";
use Shenole_project\models\Client;
use Shenole_project\models\Category;
use Shenole_project\helpers\Validator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\repositories\MajesticRepository;
use Shenole_project\helpers\MyHelpers;
use Shenole_project\models\Majestic;
use Shenole_project\models\Vendor;
use Shenole_project\models\Vendor_membership_level;

$isMajesticLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'majestic', new MajesticRepository);

if(!$isMajesticLoggedIn){
	header("Location: ".SITE_LINK_MAJESTIC."login.php");
    exit();
}
$login_token = UserHelper::getLoginTokenByUserType($_SESSION, 'majestic');
$majestic = Majestic::where('login_token',$login_token)->first();
$errors = [
	'errors_number' => 0,
	'categories' => '',
	'website_name' => '',
	'copyright_year' => '',
	'homepage_url' => '',
	'facebook_url' => '',
	'instagram_url' => '',
	'youtube_url' => ''

];
if($_POST){
    
    if(isset($_POST['add_edit_membership_level_operation'])){
        
        $errors = [];
        $vendor_membership_level = (isset($_POST['membership_level_id'])) ? Vendor_membership_level::where('id',$_POST['membership_level_id'])->first() : new Vendor_membership_level;
        $vendor_membership_level->name = $_POST['name'];
        $vendor_membership_level->price = $_POST['price'];
        $vendor_membership_level->billing_cycle = $_POST['billing_cycle'];
        $vendor_membership_level->number_of_service_categories = $_POST['number_of_service_categories'];
        $vendor_membership_level->enable_ads = isset($_POST['enable_ads']) ? 1 : 0 ;
        $vendor_membership_level->enable_wishlist = isset($_POST['enable_wishlist']) ? 1 : 0 ;
        $vendor_membership_level->enable_analytics = isset($_POST['enable_analytics']) ? 1 : 0 ;
        $vendor_membership_level->photo_gallery_limit = $_POST['photo_gallery_limit'];
        $vendor_membership_level->video_gallery_limit = $_POST['video_gallery_limit'];
        $vendor_membership_level->audio_gallery_limit = $_POST['audio_gallery_limit'];
        $vendor_membership_level->keyword_char_limit = $_POST['keyword_char_limit'];
        $vendor_membership_level->number_of_locations = $_POST['number_of_locations'];
        $vendor_membership_level->travel_limit = $_POST['travel_limit'];
        $vendor_membership_level->number_of_services = $_POST['number_of_services'];
        $errors = $vendor_membership_level->validate();
        
        $membership_level = $vendor_membership_level;

        if($errors['errors_number'] == 0){
            $vendor_membership_level->save();
            header("Location: ".SITE_LINK_MAJESTIC."settings.php");
            exit();
        }
        $show_membership_section = true;
    }
	if(isset($_POST['delete_category_operation'])){
		Category::findOrFail($_POST['vendor_category_id'])->delete();
	}
	if(isset($_POST['add_categories_operation'])){
		$cateogry = new Category;
		$categories = $_POST['categories'];
		$categories_array = array_map('trim', explode(',', $categories));
		$found_categories_name = '';

		// var_dump($categories_array);die;
		if($_POST['categories'] == ""){
			$errors['errors_number'] += 1;
			$errors['categories'] = 'This field cannot be empty.';
		}else{
			foreach($categories_array as $single_value){
				$single_category = Category::where('name', '=', $single_value)->first();		
				if (Category::where('name', '=', $single_value)->exists()){
					$errors['errors_number'] += 1;
					$found_categories_name .= $single_value.', ';
				}
			}
			$errors['categories'] = ($found_categories_name != "") ? $found_categories_name.' name(s) are already exist' : '';
		}

		if($errors['errors_number'] == 0){
			$data = [];
			foreach($categories_array as $single_value){
				$name_array = ["name" => $single_value];
				array_push($data, $name_array);
			}
			Category::insert($data);
			header("Location: ".SITE_LINK_MAJESTIC."settings.php");
			exit();
		}
	}
	if(isset($_POST['add_details_operation'])){
		
		$majestic->website_name = trim($_POST['website_name']);
		$majestic->copyright_year = trim($_POST['copyright_year']);
		$majestic->homepage_url = trim($_POST['homepage_url']);
		$majestic->facebook_url = trim($_POST['facebook_url']);
		$majestic->instagram_url = trim($_POST['instagram_url']);
		$majestic->youtube_url = trim($_POST['youtube_url']);
		$majestic->save();
	}
}
if(isset($_GET['membership_level'])){
    $membership_level = Vendor_membership_level::where('id',$_GET['membership_level'])->first();
    $show_membership_section = true;
}
if(isset($_GET['delete_membership_level'])){
    Vendor_membership_level::where('id',$_GET['delete_membership_level'])->delete();
    Vendor::where('membership_level',$_GET['delete_membership_level'])->update(['membership_level' => null]);
    $show_membership_section = true;
}
$vendor_categories = Category::get();

$show_category_list = '';
foreach($vendor_categories as $vendor_category){
	$show_category_list .= '<li class="category-li">';
		$show_category_list .= '<div>'.$vendor_category->name.'</div>';
		$show_category_list .= '<br>';
		$show_category_list .= '<div>';
			$show_category_list .= '<form method="post">';
				$show_category_list .= '<input type="hidden" name="delete_category_operation" value="'.$vendor_category->id.'"/>';
				$show_category_list .= '<input type="hidden" name="vendor_category_id" value="'.$vendor_category->id.'"/>';
				$show_category_list .= '<button class="small-button primary white-text">Delete</button>';
			$show_category_list .= '</form>';
		$show_category_list .= '</div>';
	$show_category_list .= '</li>';
}

$vendor_membership_level_list = Vendor_membership_level::get();
$show_membership_level_section = '';
foreach($vendor_membership_level_list as $single_level){
    $show_membership_level_section .= '<li class="category-li">';
        $show_membership_level_section .= '<div>'.$single_level->name.'</div>';
        $show_membership_level_section .= '<br>';
        $show_membership_level_section .= '<div class="multi-button-container">';
            $show_membership_level_section .= '<a href="'.SITE_LINK_MAJESTIC.'settings.php?membership_level='.$single_level->id.'" class="small-button primary white-text">Edit</a>';
            $show_membership_level_section .= '<a href="'.SITE_LINK_MAJESTIC.'settings.php?delete_membership_level='.$single_level->id.'" class="small-button primary white-text">Delete</a>';
        $show_membership_level_section .= '</div>';
    $show_membership_level_section .= '</li>';    
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
            <section>
                <div class="content-container-center">
                    <div class="vendor-profile-nav">
                        <div class="profile-nav-link" id="tab_account">Account</div>
                        <div class="profile-nav-link" id="tab_paypal">Paypal</div>
                        <div class="profile-nav-link" id="tab_global">Global</div>
                        <div class="profile-nav-link" id="tab_ads">Ads</div>
                        <div class="profile-nav-link" id="tab_memberships">Memberships</div>
                    </div>
                    <div class="profile-section" id="account">
                        <h2>Account</h2>
                    </div>
                    <div class="profile-section" id="paypal">
                        <h2>Paypal</h2>
                    </div>
                    <div class="profile-section" id="global">
                        <h2>Global</h2>
                        <div class="full-width-form">
                        	<!-- <form action="" class="full-width-form"> -->
                            <h3>Vendor Categories</h3>

                            <div class="form-input-container">
                        		<form method="post">
	                                <div class="form-input-search">
	                                	<input type="hidden" name="add_categories_operation" value="1"/>
	                                    <label for="category" class="input-label">Enter Comma Separated Categories 
	                                    <?php if($errors['categories'] != ""){?>
											<span class="fs_12 lh_20 text_error ml_10"><?php echo $errors['categories']; ?></span>
										<?php } ?>
										</label>
	                                    <div class="spacer-10px"></div>
	                                    <div>
	                                        <input type="text" name="categories" id="category-vendor-search" class="search-input" placeholder="example: Musician, Caterer, Bartender">
	                                    </div>
	                                    <div class="spacer-10px"></div>
	                                    <div class="form-submit-container">
	                                        <button type="submit" class="button-03 button-link-text white-text cursor_pointer">Add Categories</button>
	                                    </div>
	                                </div>
	                            </form>
								
                                <div class="form-input-search">
									<label for="location" class="input-label">Category List</label>
                                    <div class="spacer-10px"></div>
                                    <div class="list-container">
										<ul class="category-ul">
                                        	<?php echo $show_category_list; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-submit-container">
								<imput type="button" class="button-01 white-text" id="majestic-vendor-categories-submit">Submit</imput>
                            </div>
                        </div>
						<!-- </form> -->
						<div class="full-width-form">
							<form method="post">
								<input type="hidden" name="add_details_operation" value="1"/>
								<h3>Variables</h3>
								<div class="form-input-container">
									<div class="form-input-search">
										<label for="website-name" class="input-label">Website Name</label>
										<div class="spacer-10px"></div>
										<div>
											<input value="<?php echo $majestic->website_name; ?>" name="website_name" type="text" id="website-name" class="search-input" placeholder="example: Event One Stop">
										</div>
										<div class="spacer-10px"></div>
									</div>
									<div class="form-input-search">
										<label for="copyright-year" class="input-label">Copyright Year</label>
										<div class="spacer-10px"></div>
										<div>
											<input value="<?php echo $majestic->copyright_year; ?>" name="copyright_year" type="text" id="copyright-year" class="search-input" placeholder="example: 2022">
										</div>
										<div class="spacer-10px"></div>
									</div>
									<div class="form-input-search">
										<label for="homepage-url" class="input-label">Homepage URL</label>
										<div class="spacer-10px"></div>
										<div>
											<input value="<?php echo $majestic->homepage_url; ?>" name="homepage_url" type="text" id="homepage-url" class="search-input" placeholder="example: https://eventonestop.com">
										</div>
										<div class="spacer-10px"></div>
									</div>
								</div>
								<div class="form-input-container">
									<div class="form-input-search">
										<label for="facebook-page" class="input-label">Facebook Page URL</label>
										<div class="spacer-10px"></div>
										<div>
											<input value="<?php echo $majestic->facebook_url; ?>" name="facebook_url" type="text" id="facebook-page" class="search-input" placeholder="example: https://facebook.com/eventonestop">
										</div>
										<div class="spacer-10px"></div>
									</div>
									<div class="form-input-search">
										<label for="instagram-page" class="input-label">Instagram Page</label>
										<div class="spacer-10px"></div>
										<div>
											<input value="<?php echo $majestic->instagram_url; ?>" name="instagram_url" type="text" id="instagram-page" class="search-input" placeholder="example: https://instagram.com/eventonestop">
										</div>
										<div class="spacer-10px"></div>
									</div>
									<div class="form-input-search">
										<label for="youtube-page" class="input-label">Youtube Page</label>
										<div class="spacer-10px"></div>
										<div>
											<input value="<?php echo $majestic->youtube_url; ?>" name="youtube_url" type="text" id="youtube-page" class="search-input" placeholder="example: https://youtube.com/eventonestop">
										</div>
										<div class="spacer-10px"></div>
									</div>
								</div>
								<div class="form-submit-container">
									<button type="submit" class="button-01 white-text" id="majestic-global-variables-submit">Submit</button>
								</div>
							</form>
						</div>
                    </div>
                    <div class="profile-section" id="ads">
                        <h2>Ads</h2>
                    </div>
                    <div class="profile-section" id="memberships" data-show-initially="<?php echo (isset($show_membership_section))? '1' : '0' ; ?>">
                        <h2>Memberships</h2>
						<form method="post" class="full-width-form">
                            <!-- MEMBERSHIP LEVEL 1 -->
                            <input type="hidden" name="add_edit_membership_level_operation">
                            <?php if(isset($membership_level->id)){?>
                            <input type="hidden" name="membership_level_id" value="<?php echo $membership_level->id; ?>">
                            <?php } ?>
                            <h3>Create Membership Levels</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="name" class="input-label">Enter Membership Level Name 
                                        <?php echo ($errors['errors_number'] > 0 && $errors['name'] != "") ? '<span style="color:firebrick;font-size:12px;">'.$errors['name'].'</span>' : '' ; ?></label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input value="<?php echo (isset($membership_level->name)) ? $membership_level->name : '' ; ?>" type="text" name="name" id="name" class="search-input" placeholder="example: Bronze Membership">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location" class="input-label">Membership List (Limit 4 Memberships)</label>
                                    <div class="spacer-10px"></div>
                                    <div class="list-container">
                                        <ul class="category-ul">
                                            <?php echo $show_membership_level_section; ?> 
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="price" class="input-label">Set Membership Price&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i>
                                    <?php echo ($errors['errors_number'] > 0 && $errors['price'] != "") ? '<span style="color:firebrick;font-size:12px;">'.$errors['price'].'</span>' : '' ; ?></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input value="<?php echo (isset($membership_level->price)) ? $membership_level->price : '' ; ?>" type="number" step="0.01" name="price" id="price" class="search-input" placeholder="example: 25.99">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="billing_cycle" class="input-label">Set Membership Billing Cycle
                                    <?php echo ($errors['errors_number'] > 0 && $errors['billing_cycle'] != "") ? '<span style="color:firebrick;font-size:12px;">'.$errors['billing_cycle'].'</span>' : '' ; ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <select name="billing_cycle" id="billing_cycle" class="search-input">
                                            <option class="select-option-01" value="">Select A Period</option>
                                            <option value="D" class="select-option-01" <?php echo (isset($membership_level->billing_cycle) && $membership_level->billing_cycle == 'D') ? 'selected' : '' ; ?>>Daily</option>
                                            <option value="W" class="select-option-01" <?php echo (isset($membership_level->billing_cycle) && $membership_level->billing_cycle == 'W') ? 'selected' : '' ; ?>>Weekly</option>
                                            <option value="M" class="select-option-01" <?php echo (isset($membership_level->billing_cycle) && $membership_level->billing_cycle == 'M') ? 'selected' : '' ; ?>>Monthly</option>
                                            <option value="Y" class="select-option-01" <?php echo (isset($membership_level->billing_cycle) && $membership_level->billing_cycle == 'Y') ? 'selected' : '' ; ?>>Yearly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="number_of_service_categories" class="input-label">How Many Service Categories
                                    <?php echo ($errors['errors_number'] > 0 && $errors['number_of_service_categories'] != "") ? '<span style="color:firebrick;font-size:12px;">'.$errors['number_of_service_categories'].'</span>' : '' ; ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <select name="number_of_service_categories" id="number_of_service_categories" class="search-input">
                                            <option class="select-option-01" value="">Select A Number</option>
                                            <option value="0" class="select-option-01" <?php echo (isset($membership_level->number_of_service_categories) && $membership_level->number_of_service_categories == '0') ? 'selected' : '' ; ?>>0</option>
                                            <option value="1" class="select-option-01" <?php echo (isset($membership_level->number_of_service_categories) && $membership_level->number_of_service_categories == '1') ? 'selected' : '' ; ?>>1</option>
                                            <option value="2" class="select-option-01" <?php echo (isset($membership_level->number_of_service_categories) && $membership_level->number_of_service_categories == '2') ? 'selected' : '' ; ?>>2</option>
                                            <option value="3" class="select-option-01" <?php echo (isset($membership_level->number_of_service_categories) && $membership_level->number_of_service_categories == '3') ? 'selected' : '' ; ?>>3</option>
                                            <option value="4" class="select-option-01" <?php echo (isset($membership_level->number_of_service_categories) && $membership_level->number_of_service_categories == '4') ? 'selected' : '' ; ?>>4</option>
                                            <option value="5" class="select-option-01" <?php echo (isset($membership_level->number_of_service_categories) && $membership_level->number_of_service_categories == '5') ? 'selected' : '' ; ?>>5</option>
                                            <option value="6" class="select-option-01" <?php echo (isset($membership_level->number_of_service_categories) && $membership_level->number_of_service_categories == '6') ? 'selected' : '' ; ?>>6</option>
                                            <option value="7" class="select-option-01" <?php echo (isset($membership_level->number_of_service_categories) && $membership_level->number_of_service_categories == '7') ? 'selected' : '' ; ?>>7</option>
                                            <option value="8" class="select-option-01" <?php echo (isset($membership_level->number_of_service_categories) && $membership_level->number_of_service_categories == '8') ? 'selected' : '' ; ?>>8</option>
                                            <option value="9" class="select-option-01" <?php echo (isset($membership_level->number_of_service_categories) && $membership_level->number_of_service_categories == '9') ? 'selected' : '' ; ?>>9</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <div>
                                        <label for="enable_ads" class="input-label">Enable Advertisements</label>
                                        <div class="spacer-10px"></div>
                                        <input type="checkbox" name="enable_ads" id="enable_ads" <?php echo (isset($membership_level->enable_ads) && $membership_level->enable_ads == '1') ? 'checked' : ''; ?>>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <div>
                                        <label for="enable_wishlist" class="input-label">Enable Wishlist</label>
                                        <div class="spacer-10px"></div>
                                        <input type="checkbox" name="enable_wishlist" id="enable_wishlist" <?php echo (isset($membership_level->enable_wishlist) && $membership_level->enable_wishlist == '1') ? 'checked' : ''; ?>>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <div>
                                        <label for="enable_analytics" class="input-label">Enable Analytics</label>
                                        <div class="spacer-10px"></div>
                                        <input type="checkbox" name="enable_analytics" id="enable_analytics" <?php echo (isset($membership_level->enable_analytics) && $membership_level->enable_analytics == '1') ? 'checked' : ''; ?>>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="photo_gallery_limit" class="input-label">Set Photo Gallery Upload Limit (Max = 48)
                                    <?php echo ($errors['errors_number'] > 0 && $errors['photo_gallery_limit'] != "") ? '<span style="color:firebrick;font-size:12px;">'.$errors['photo_gallery_limit'].'</span>' : '' ; ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <input value="<?php echo (isset($membership_level->photo_gallery_limit)) ? $membership_level->photo_gallery_limit : '' ; ?>" type="number" name="photo_gallery_limit" id="photo_gallery_limit" class="search-input" placeholder="example: 48">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="video_gallery_limit" class="input-label">Set Embeded Video Limit (Max = 48)
                                    <?php echo ($errors['errors_number'] > 0 && $errors['video_gallery_limit'] != "") ? '<span style="color:firebrick;font-size:12px;">'.$errors['video_gallery_limit'].'</span>' : '' ; ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <input value="<?php echo (isset($membership_level->video_gallery_limit)) ? $membership_level->video_gallery_limit : '' ; ?>" type="number" name="video_gallery_limit" id="video_gallery_limit" class="search-input" placeholder="example: 48">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="audio_gallery_limit" class="input-label">Set Embeded Audio Limit (Max = 48)
                                    <?php echo ($errors['errors_number'] > 0 && $errors['audio_gallery_limit'] != "") ? '<span style="color:firebrick;font-size:12px;">'.$errors['audio_gallery_limit'].'</span>' : '' ; ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <input value="<?php echo (isset($membership_level->audio_gallery_limit)) ? $membership_level->audio_gallery_limit : '' ; ?>" type="number" name="audio_gallery_limit" id="audio_gallery_limit" class="search-input" placeholder="example: 48">
                                    </div>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <div class="form-input-search">
                                        <label for="keyword_char_limit" class="input-label">Set Keyword Character Limit (Max = 420)
                                        <?php echo ($errors['errors_number'] > 0 && $errors['keyword_char_limit'] != "") ? '<span style="color:firebrick;font-size:12px;">'.$errors['keyword_char_limit'].'</span>' : '' ; ?>
                                        </label>
                                        <div class="spacer-10px"></div>
                                        <div class="flex-container">
                                            <input value="<?php echo (isset($membership_level->keyword_char_limit)) ? $membership_level->keyword_char_limit : '' ; ?>" type="number" name="keyword_char_limit" id="keyword_char_limit" class="search-input" placeholder="example: 420">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="number_of_locations" class="input-label">How Many Locations
                                    <?php echo ($errors['errors_number'] > 0 && $errors['number_of_locations'] != "") ? '<span style="color:firebrick;font-size:12px;">'.$errors['number_of_locations'].'</span>' : '' ; ?>
                                    </label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <select name="number_of_locations" id="number_of_locations" class="search-input">
                                            <option class="select-option-01" value="">Select A Number</option>
                                            <option value="0" class="select-option-01" <?php echo (isset($membership_level->number_of_locations) && $membership_level->number_of_locations == '0') ? 'selected' : '' ; ?>>0</option>
                                            <option value="1" class="select-option-01" <?php echo (isset($membership_level->number_of_locations) && $membership_level->number_of_locations == '1') ? 'selected' : '' ; ?>>1</option>
                                            <option value="2" class="select-option-01" <?php echo (isset($membership_level->number_of_locations) && $membership_level->number_of_locations == '2') ? 'selected' : '' ; ?>>2</option>
                                            <option value="3" class="select-option-01" <?php echo (isset($membership_level->number_of_locations) && $membership_level->number_of_locations == '3') ? 'selected' : '' ; ?>>3</option>
                                            <option value="4" class="select-option-01" <?php echo (isset($membership_level->number_of_locations) && $membership_level->number_of_locations == '4') ? 'selected' : '' ; ?>>4</option>
                                            <option value="5" class="select-option-01" <?php echo (isset($membership_level->number_of_locations) && $membership_level->number_of_locations == '5') ? 'selected' : '' ; ?>>5</option>
                                            <option value="6" class="select-option-01" <?php echo (isset($membership_level->number_of_locations) && $membership_level->number_of_locations == '6') ? 'selected' : '' ; ?>>6</option>
                                            <option value="7" class="select-option-01" <?php echo (isset($membership_level->number_of_locations) && $membership_level->number_of_locations == '7') ? 'selected' : '' ; ?>>7</option>
                                            <option value="8" class="select-option-01" <?php echo (isset($membership_level->number_of_locations) && $membership_level->number_of_locations == '8') ? 'selected' : '' ; ?>>8</option>
                                            <option value="9" class="select-option-01" <?php echo (isset($membership_level->number_of_locations) && $membership_level->number_of_locations == '9') ? 'selected' : '' ; ?>>9</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <div>
                                        <label for="travel_limit" class="input-label">Set Travel Distance Limit
                                        <?php echo ($errors['errors_number'] > 0 && $errors['travel_limit'] != "") ? '<span style="color:firebrick;font-size:12px;">'.$errors['travel_limit'].'</span>' : '' ; ?>
                                        </label>
                                        <div class="spacer-10px"></div>
                                        <div>
                                            <select name="travel_limit" id="travel_limit" class="search-input">
                                                <option value="" class="select-option-01">Select A Distance Limit</option>
                                                <option value="not-applicable" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == 'not-applicable') ? 'selected' : '' ; ?>>Not Applicable</option>
                                                <option value="5-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '5-miles') ? 'selected' : '' ; ?>>5 miles</option>
                                                <option value="10-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '10-miles') ? 'selected' : '' ; ?>>10 miles</option>
                                                <option value="15-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '15-miles') ? 'selected' : '' ; ?>>15 miles</option>
                                                <option value="25-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '25-miles') ? 'selected' : '' ; ?>>25 miles</option>
                                                <option value="50-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '50-miles') ? 'selected' : '' ; ?>>50 miles</option>
                                                <option value="75-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '75-miles') ? 'selected' : '' ; ?>>75 miles</option>
                                                <option value="100-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '100-miles') ? 'selected' : '' ; ?>>100 miles</option>
                                                <option value="150-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '150-miles') ? 'selected' : '' ; ?>>150 miles</option>
                                                <option value="200-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '200-miles') ? 'selected' : '' ; ?>>200 miles</option>
                                                <option value="300-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '300-miles') ? 'selected' : '' ; ?>>300 miles</option>
                                                <option value="400-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '400-miles') ? 'selected' : '' ; ?>>400 miles</option>
                                                <option value="500-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '500-miles') ? 'selected' : '' ; ?>>500 miles</option>
                                                <option value="750-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '750-miles') ? 'selected' : '' ; ?>>750 miles</option>
                                                <option value="1000-miles" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '1000-miles') ? 'selected' : '' ; ?>>1,000 miles</option>
                                                <option value="continental-us" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == 'continental-us') ? 'selected' : '' ; ?>>Continental US</option>
                                                <option value="50-states" class="select-option-01" <?php echo (isset($membership_level->travel_limit) && $membership_level->travel_limit == '50-states') ? 'selected' : '' ; ?>>All 50 States</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <div>
                                        <label for="number_of_services" class="input-label">Set Limit For Number Of Services Sold (Max = 50)
                                        <?php echo ($errors['errors_number'] > 0 && $errors['number_of_services'] != "") ? '<span style="color:firebrick;font-size:12px;">'.$errors['number_of_services'].'</span>' : '' ; ?>
                                        </label>
                                        <div class="spacer-10px"></div>
                                        <div class="flex-container">
                                            <input value="<?php echo (isset($membership_level->number_of_services)) ? $membership_level->number_of_services : '' ; ?>" type="number" name="number_of_services" id="number_of_services" class="search-input" placeholder="example: 50">
                                        </div>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-submit-container">
                                <?php if(isset($membership_level->id)){ ?>
                                    <button type="submit" class="button-01 white-text" name="membership-settings-submit" id="membership-settings-submit">Update</button>
                                    <a href="<?php echo SITE_LINK_MAJESTIC; ?>settings.php" class="button-01 white-text">Cancel</a>
                                <?php }else{ ?>
                                    <button type="submit" class="button-01 white-text" name="membership-settings-submit" id="membership-settings-submit">Submit</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
		</div>
		<?php 
        MyHelpers::includeWithVariables('../layouts/footer.php', [], $print = true);
        ?>
	</div>
	<script src="<?php echo SITE_LINK; ?>js/jquery.min.js"></script>
	<script src="<?php echo SITE_LINK; ?>js/pages/majestic/settings.js"></script>
    <?php 
	MyHelpers::includeWithVariables('../layouts/common_footer.php', [], $print = true);
	?>
</body>
</html>