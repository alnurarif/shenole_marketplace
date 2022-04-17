<?php
session_start();
require "../start.php";
use Shenole_project\repositories\VendorRepository;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\models\Vendor_location;
use Shenole_project\models\Vendor_category;
use Shenole_project\utils\ImageUploader;
use Shenole_project\helpers\UserHelper;
use Shenole_project\helpers\MyHelpers;
use Shenole_project\helpers\Validator;
use Shenole_project\models\Category;
use Shenole_project\models\Vendor;
use Shenole_project\models\State;
use \Gumlet\ImageResize;

$isVendorLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'vendor', new VendorRepository);

if(!$isVendorLoggedIn){
	header("Location: ".SITE_LINK."login.php");
    exit();
}
$login_token = UserHelper::getLoginTokenByUserType($_SESSION, 'vendor');
$errors = [
	'errors_number' => 0,
	'profile_pic' => '',
    'vendor_categories' => '',
    'vendor_locations' => '',
    'keywords' => ''
];
$travel_limit_array = [
    "not-applicable" => 'Not Applicable',
    "5" => '5 miles',
    "10" => '10 miles',
    "15" => '15 miles',
    "25" => '25 miles',
    "50" => '50 miles',
    "75" => '75 miles',
    "100" => '100 miles',
    "150" => '150 miles',
    "200" => '200 miles',
    "300" => '300 miles',
    "400" => '400 miles',
    "500" => '500 miles',
    "750" => '750 miles',
    "1000" => '1,000 miles',
    "continental-us" => 'Continental US',
    "50-states" => 'All 50 States'
];
if($_POST){
    // echo "<pre>";
    // var_dump($_POST);
    // exit;
    if(isset($_POST['update_vendor_description'])){
        $vendor = Vendor::where('login_token',$login_token)->first();
        $vendor->vendor_description = $_POST['description_with_html_tags'];
        $vendor->save();
    }
    if(isset($_POST['operation_add_update_overview'])){
    	$vendor = Vendor::where('login_token',$login_token)->first();
        $vendor_categories = (isset($_POST['vendor_categories'])) ? $_POST['vendor_categories'] : null ;
        $vendor_locations = (isset($_POST['vendor_locations'])) ? $_POST['vendor_locations'] : null ;
        if($_FILES['profile_pic']['name'] == ''){
    		$errors['profile_pic'] = '';
    	}else{
    		$size = $_FILES['profile_pic']['size'];
    		
    		list($width, $height, $type, $attr) = getimagesize($_FILES['profile_pic']['tmp_name']);
    		
    		if($width > 766 && $height != 511){
    			$errors['profile_pic'] = "Dimention must be 766px x 511px";
                $errors['errors_number'] ++;
            }
    		if($size > 80000){
    			$errors['profile_pic'] = "Maximum 80kb is allowed";
                $errors['errors_number'] ++;
    		}
    		
    	
        }
        if($vendor->vendor_membership_level->number_of_service_categories < count($vendor_categories)){
            $errors['vendor_categories'] = "Your category list number exceed your package limit";
            $errors['errors_number']++;
        }
        if($vendor->vendor_membership_level->number_of_locations < count($vendor_locations)){
            $errors['vendor_locations'] = "Your location list number exceed your package limit";
            $errors['errors_number']++;
        }
        if(strlen($_POST['keywords']) > $vendor->vendor_membership_level->keyword_char_limit){
            $errors['keywords'] = "Your keywords characters number exceed your package limit";
            $errors['errors_number']++;
        }

    	if($errors['errors_number'] == 0){
            
            if($_FILES['profile_pic']['name'] != ''){
                if (file_exists(SITE_ROOT.'images/vendors/'.$vendor->profile_photo)) {
                    chmod(SITE_ROOT.'images/vendors/'.$vendor->profile_photo, 0644);
                    unlink(SITE_ROOT.'images/vendors/'.$vendor->profile_photo);
                } 
                $imageObject = new ImageResize($_FILES['profile_pic']['tmp_name']);
                $imageUploaderObject = new ImageUploader($_FILES['profile_pic'], $imageObject);
                $imageUploaderObject->setRoot(SITE_ROOT);
                $imageUploaderObject->setPath('images/vendors/');
                $imageUploaderObject->setLevel('../');
                $image_name = $imageUploaderObject->imageUpload();
            }
            
            
            

    		if($_FILES['profile_pic']['name'] != '') $vendor->profile_photo = $image_name;
    		$vendor->company_name = trim($_POST['company_name']);
    		$vendor->travel_distance = trim($_POST['travel_distance']);
    		$vendor->starting_fee = trim($_POST['starting_fee']);
    		$vendor->keywords = trim($_POST['keywords']);

    		$vendor->save();

            Vendor_category::where('vendor_id',$vendor->id)->delete();
            foreach($vendor_categories as $key=>$single_category){
                $vendor_category = new Vendor_category;
                $vendor_category->vendor_id = $vendor->id;
                $vendor_category->category_id = $single_category;
                $vendor_category->is_primary = ($key == 0) ? 1 : 0;
                $vendor_category->save();
            }
            Vendor_location::where('vendor_id',$vendor->id)->delete();
            foreach($vendor_locations as $key=>$single_location){
                $location_array = explode('|||',$single_location);
                $vendor_location = new Vendor_location;
                $vendor_location->vendor_id = $vendor->id;
                $vendor_location->street_address_1 = $location_array[0];
                $vendor_location->street_address_2 =$location_array[1];
                $vendor_location->location_city = $location_array[2];
                $vendor_location->location_state = $location_array[3];
                $vendor_location->location_zip_code = $location_array[4];
                $vendor_location->location_phone = $location_array[5];
                $vendor_location->city_state_only = $location_array[6];
                $vendor_location->is_phone_number_visible = $location_array[7];
                $vendor_location->is_primary = ($key == 0) ? 1 : 0;
                $vendor_location->save();
            }
        }

    }

}
$vendor = Vendor::where('login_token',$login_token)->with('categories','locations','locations.state','vendor_membership_level')->first();
$categories = Category::get();
$states = State::get();

// $vendor = Vendor::get();
// echo "<pre>";
// var_dump($vendor->vendor_membership_level);exit;
// foreach($vendor as $v_single){
// 	var_dump($v_single);
// }
// die;
// var_dump();
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
                    <div class="vendor-profile-container">
                        <div class="vendor-profile-nav">
                            <div class="profile-nav-link" id="tab_overview">Overview</div>
                            <div class="profile-nav-link" id="tab_description">Description</div>
                            <div class="profile-nav-link" id="tab_services">Services</div>
                            <div class="profile-nav-link" id="tab_photos">Photos</div>
                            <div class="profile-nav-link" id="tab_videos">Vidoes</div>
                            <div class="profile-nav-link" id="tab_audio">Audio</div>
                            <div class="profile-nav-link" id="tab_reviews">Reviews</div>
                        </div>
                        <div class="profile-section" id="overview">
                            <h2>Overview</h2>
                            <div class="vendor-profile-header-container">
                                <form method="post" class="full-width-form" enctype="multipart/form-data">
                                    <input type="hidden" name="operation_add_update_overview" value="1"/>
                                    <div class="edit-vendor-profile-main-photo">
                                        <img onerror="this.src='<?php echo SITE_LINK; ?>images/no_image.jpg'" class="full h_full" src="<?php echo SITE_LINK; ?><?php echo ($vendor->profile_photo == "" || $vendor->profile_photo == null) ? "/images/no_image.jpg" : "/images/vendors/".$vendor->profile_photo;  ?>" alt="profile_picture" id="active_profile_picture"/>
                                    </div>
                                    <h3>Profile Photo Upload</h3>
                                    <div>
                                        <label for="profile-photo" class="input-label">Profile Photo <?php echo ($errors['profile_pic'] != "") ? '<span class="text_error">'.$errors['profile_pic'].'</span>' : '' ?></label>
                                        <div class="spacer-10px"></div>
                                        <div>
                                            <input type="file" id="profile-photo" class="search-input" placeholder="Profile Photo" accept="image/png, image/gif, image/jpeg" name="profile_pic"/>
                                        </div>
                                        <div class="spacer-10px"></div>
                                        <div class="spacer-10px"></div>
                                    </div>
                                    <div class="spacer-10px"></div>
                                    <div class="spacer-10px"></div>
                                    <div class="spacer-10px"></div>
                                    <h3>Basic information</h3>    
                                    <div class="form-input-container">
                                        <div>
                                            <label for="company-name" class="input-label">Company Name</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" name="company_name" value="<?php echo (isset($vendor->company_name)) ? $vendor->company_name : ''; ?>" id="company-name" class="search-input" placeholder="Company Name">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                        </div>
                                        <div>
                                            <label for="travel-distance" class="input-label">What's The Farthest You Will Travel</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <select name="travel_distance" id="travel-distance" class="search-input">
                                                    <?php foreach($travel_limit_array as $key=>$single_travel_limit){ ?>
                                                        <option value="<?php echo $key; ?>" class="select-option-01" <?php echo (isset($vendor->travel_distance) && $vendor->travel_distance == $key) ? 'selected' : '' ; ?>><?php echo $single_travel_limit; ?></option>
                                                        <?php if($key == $vendor->vendor_membership_level->travel_limit) break; ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                        </div>
                                        <div>
                                            <label for="location" class="input-label">What's Your Starting Fee</label>
                                            <div class="spacer-10px"></div>
                                            <div class="flex-container">
                                                <b>$</b><input type="text" name="starting_fee" value="<?php echo (isset($vendor->starting_fee)) ? $vendor->starting_fee : ''; ?>" id="starting-fee" class="search-input" placeholder="example: 300.00">
                                            </div>
                                        </div>
                                    </div>
                                    <h3>Categories</h3>    
                                    <div class="form-input-container">
                                        <div class="form-input-search">
                                            <label for="category" class="input-label">Primary Category</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <select name="category" id="category" class="search-input">
                                                    <?php foreach($categories as $single_category){ ?>
                                                        <option value="<?php echo $single_category->id; ?>" class="select-option-01"><?php echo $single_category->name; ?></option>
                                                    <?php } ?>
                                                    
                                                </select>
                                            </div>
                                            <div id="hidden_category_input" class="display_none">
                                                <?php foreach($vendor->categories as $key=>$single_category){ ?>
                                                    <input type="hidden" value="<?php echo $single_category->category_id; ?>" name="vendor_categories[]" id="vendor_category_input_<?php echo $key+1; ?>">
                                                <?php } ?>    
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <div class="form-submit-container">
                                                <button type="button" id="add_category_button" class="button-03 button-link-text white-text cursor_pointer">Add Category</button>
                                            </div>
                                        </div>
                                        <div class="form-input-search">
                                            <label for="location" class="input-label">Category List &nbsp;&nbsp;<i>(Primary is in green)</i> <?php echo ($errors['vendor_categories'] != "") ? '<span class="text_error">'.$errors['vendor_categories'].'</span>' : '' ?></label>
                                            <div class="spacer-10px"></div>
                                            <div class="list-container">
                                                <ul class="category-ul" id="added_category_list">
                                                    <?php foreach($vendor->categories as $key=>$single_category){ ?>
                                                        <li class="category-li single_added_category" id="single_vendor_category_<?php echo $key+1;?>">
                                                            <div <?php echo ($key== 0) ? 'class="primary-selection"': ''; ?>><?php echo $single_category->category->name;?></div>
                                                            <br>
                                                            <div class="multi-button-container">
                                                                <?php if($key != 0){ ?>
                                                                <button type="button" class="small-button primary white-text make_primary_from_list" id="make_primary_category_<?php echo $key+1;?>">Make Primary</button>
                                                                <?php } ?>
                                                                <button type="button" class="small-button primary white-text delete_category_from_list" id="delete_vendor_category_<?php echo $key+1;?>">Delete</button>
                                                            </div>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="spacer-10px"></div>
                                    <div class="spacer-10px"></div>
                                    <h3>Locations</h3> 
                                    <div class="form-input-container">
                                        <div class="form-input-search">
                                            <div>
                                                <label for="street-address1-01" class="input-label">Location Street Address 1</label>
                                                <div class="spacer-10px"></div>
                                                <div>
                                                    <input type="text" class="search-input" placeholder="Street Address 1" id="location_street_address_1">
                                                </div>
                                                <div class="spacer-10px"></div>
                                                <div class="spacer-10px"></div>
                                                <label for="street-address2-01" class="input-label">Location Street Address 2</label>
                                                <div class="spacer-10px"></div>
                                                <div>
                                                    <input type="text" class="search-input" placeholder="Street Address 2" id="location_street_address_2">
                                                </div>
                                                <div class="spacer-10px"></div>
                                                <div class="spacer-10px"></div>
                                                <span class="input-label">Location City/State/Zip</span>
                                                <div class="spacer-10px"></div>
                                                <div>
                                                    <input type="text" class="search-input" placeholder="City" id="location_city">
                                                </div>
                                                <div class="spacer-10px"></div>
                                                <div class="profile-state-zip">
                                                    <div>
                                                        <select name="state-01" class="search-input-mini" id="location_state">
                                                        <?php foreach($states as $single_state){ ?>
                                                            <option value="<?php echo $single_state->id; ?>" class="select-option-01"><?php echo $single_state->short_name; ?></option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <div>
                                                            <input type="text" class="search-input-mini" placeholder="Zip" id="location_zip">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="spacer-10px"></div>
                                                <div class="spacer-10px"></div>
                                                <label for="phone-01" class="input-label">Phone Number</label>
                                                <div class="spacer-10px"></div>
                                                <div>
                                                    <input type="tel" class="search-input" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="example: 123-456-7890" id="location_phone_number">
                                                </div>
                                                <div class="spacer-10px"></div>
                                                <div class="spacer-10px"></div>
                                            </div>
                                            <div id="hidden_location_input" class="display_none">
                                                <?php foreach($vendor->locations as $key=>$single_location){ ?>
                                                    <?php $full_location_joined = $single_location->street_address_1.'|||'.$single_location->street_address_2.'|||'.$single_location->location_city.'|||'.$single_location->location_state.'|||'.$single_location->location_zip_code.'|||'.$single_location->location_phone.'|||'.$single_location->city_state_only.'|||'.$single_location->is_phone_number_visible; ?>
                                                    <input type="hidden" value="<?php echo $full_location_joined; ?>" name="vendor_locations[]" id="vendor_location_input_<?php echo $key+1; ?>">
                                                <?php } ?>  
                                            </div>
                                            <div class="form-submit-container">
                                                <button type="button" class="button-03 button-link-text white-text cursor_pointer" id="add_location_to_list">Add Location</button>
                                            </div>
                                        </div>
                                        <div class="form-input-search">
                                            <label for="location" class="input-label">Location List &nbsp;&nbsp;<i>(Primary is in green)</i><?php echo ($errors['vendor_locations'] != "") ? '<span class="text_error">'.$errors['vendor_locations'].'</span>' : '' ?></label>
                                            <div class="spacer-10px"></div>
                                            <div class="list-container-tall">
                                                <ul class="category-ul" id="added_location_list">
                                                <?php foreach($vendor->locations as $key=>$single_location){ ?>

                                                <li class="category-li single_added_location" id="single_vendor_location_<?php echo $key+1; ?>">
                                                    <?php if($key == 0){ ?>
                                                        <div class="primary-location">
                                                    <?php }else{ ?>
                                                        <div class="aux-location">
                                                    <?php } ?>
                                                        <div class="display_none location_object">
                                                            <span class="location_street_address_1"><?php echo $single_location->street_address_1; ?></span>
                                                            <span class="location_street_address_2"><?php echo $single_location->street_address_2; ?></span>
                                                            <span class="location_city"><?php echo $single_location->location_city; ?></span>
                                                            <span class="location_state_id"><?php echo $single_location->location_state; ?>}</span>
                                                            <span class="location_state_name"><?php echo $single_location->state->short_name; ?></span>
                                                            <span class="location_zip"><?php echo $single_location->location_zip_code; ?></span>
                                                            <span class="location_phone_number"><?php echo $single_location->location_phone; ?></span>
                                                        </div>
                                                        <span class="location-list-text street_address_1 <?php echo ($single_location->city_state_only == 1) ? 'display_none' : ''; ?>"><?php echo $single_location->street_address_1; ?></span><br>
                                                        <span class="location-list-text street_address_2 <?php echo ($single_location->city_state_only == 1) ? 'display_none' : ''; ?>"><?php echo $single_location->street_address_2; ?></span><br>
                                                        <span class="location-list-text city_state_name"><?php echo $single_location->location_city; ?>, <?php echo $single_location->state->short_name; ?></span><br>
                                                        <span class="location-list-text zip <?php echo ($single_location->city_state_only == 1) ? 'display_none' : ''; ?>"><?php echo $single_location->location_zip_code; ?></span><br>
                                                        <div class="spacer-10px"></div>
                                                        <div class="spacer-10px"></div>
                                                        <span class="location-list-text-phone phone_number <?php echo ($single_location->is_phone_number_visible == 0) ? 'display_none' : ''; ?>"><?php echo $single_location->location_phone; ?></span>
                                                    </div>
                                                    <br>
                                                    <div>
                                                        <label for="only-city-01" class="input-label">Only Show The City And State
                                                        </label>
                                                        <div class="spacer-10px"></div>
                                                        <input type="checkbox" name="only-city-01" class="show_city_and_state" id="only_show_city_and_state_<?php echo $key+1; ?>" <?php echo ($single_location->city_state_only == 1) ? 'checked' : ''; ?>>
                                                        <div class="spacer-10px"></div>
                                                        <div class="spacer-10px"></div>
                                                    </div>
                                                    <div>
                                                        <label for="show-phone-01" class="input-label">Show The Phone Number</label>
                                                        <div class="spacer-10px"></div>
                                                        <input type="checkbox" name="only-city-01" class="show_the_phone_number" id="show_the_phone_number_<?php echo $key+1; ?>" <?php echo ($single_location->is_phone_number_visible == 1) ? 'checked' : ''; ?>>
                                                        <div class="spacer-10px"></div>
                                                        <div class="spacer-10px"></div>
                                                    </div>
                                                    <div class="multi-button-container">
                                                        <?php if($key != 0){ ?>
                                                        <button type="button" class="small-button primary white-text make_primary_from_location_list" id="make_primary_location_<?php echo $key+1; ?>">Make Primary</button>
                                                        <?php } ?>
                                                        <button type="button" class="small-button primary white-text delete_location_from_list" id="delete_vendor_location_<?php echo $key+1; ?>">Delete</button>
                                                    </div>
                                                </li>

                                                    <?php } ?>  
                                                </ul>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="spacer-10px"></div>
                                    <div class="spacer-10px"></div>
                                    <h3>Keywords</h3>
                                    <div class="form-input-container">
                                        <div class="form-input-search">
                                            <label for="keywords" class="input-label">Enter Comma Separated Keywords<br>(<?php echo $vendor->vendor_membership_level->keyword_char_limit; ?> character limit...aprx 10 words) <?php echo ($errors['keywords'] != "") ? '<span class="text_error">'.$errors['keywords'].'</span>' : '' ?></label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input value="<?php echo $vendor->keywords; ?>" type="text" id="keyword-vendor-search" name="keywords" class="search-input" placeholder="example: party band, caterer, photographer">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="form-submit-container">
                                                <!-- <a href="" class="button-03 button-link-text white-text">Add Keywords</a> -->
                                            </div>
                                        </div>
                                        <div class="form-input-search">
                                            <label for="location" class="input-label">Keyword List</label>
                                            <div class="spacer-10px"></div>
                                            <div class="list-container">
                                                <ul class="category-ul" id="keyword_list">
                                                <?php 
                                                    $keywords = explode(',',$vendor->keywords);
                                                    foreach($keywords as $key=>$single_keyword){
                                                ?>
                                                <li class="category-li" id="keyword_${index+1}">
                                                    <div><?php echo $single_keyword; ?></div>
                                                    <br>
                                                    <div>
                                                        <button type="button" class="small-button primary white-text keyword_delete" id="keyword_delete_<?php echo $key+1; ?>">Delete</button>
                                                    </div>
                                                </li>
                                                <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-submit-container">
                                        <button type="submit" class="button-01 white-text" id="vendor-overview-submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="profile-section" id="description">
                            <h2>Description</h2>
                            <form method="post" class="full-width-form">
                                <input type="hidden" name="update_vendor_description" value="1">
                                <span id="vendor-description-character-countdown">2000</span> characters left
                                <div class="wysiwyg-container">
                                    <input type="hidden" name="description_with_html_tags" id="description_with_html_tags" value="<?php echo $vendor->vendor_description; ?>"/>
                                    <textarea class="full-width-textarea" id="description_textarea"><?php echo str_replace('</p>',PHP_EOL,str_replace('<p>','',$vendor->vendor_description)); ?></textarea>

                                </div>
                                <div class="spacer-20"></div>
                                <div class="article-container" id="vendor-description">
                                    <!-- Start 2000 Character Limit -->
                                    <?php echo $vendor->vendor_description; ?>
                                    <!-- End 2000 Character Limit -->
                                </div>
                                <div class="form-submit-container">
                                    <button type="submit" class="button-01 white-text" id="vendor-description-submit">Submit</button>
                                </div>
                            </form>
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
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>/images/star.png" alt="Review star">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>/images/star.png" alt="Review star">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>/images/star.png" alt="Review star">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>/images/star.png" alt="Review star">
                                        <img class="review-star-full" src="<?php echo SITE_LINK; ?>/images/star.png" alt="Review star">
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
        MyHelpers::includeWithVariables('../layouts/footer.php', [], $print = true);
        ?>
    </div>


	<script src="<?php echo SITE_LINK; ?>js/jquery.min.js"></script>
	<script src="<?php echo SITE_LINK; ?>js/pages/vendors/edit-profile.js"></script>
    <script src="../js/custom.js"></script>
    
</body>
</html>