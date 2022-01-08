<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Vendor;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\utils\ImageUploader;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\VendorRepository;
use Shenole_project\helpers\MyHelpers;
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
];
if($_POST){
    if(isset($_POST['update_vendor_description'])){
        $vendor = Vendor::where('login_token',$login_token)->first();
        $vendor->vendor_description = $_POST['description_with_html_tags'];
        $vendor->save();
    }
    if(isset($_POST['operation_add_update_overview'])){
    	if($_FILES['profile_pic']['name'] == ''){
    		$errors['profile_pic'] = '';
    	}else{
    		$size = $_FILES['profile_pic']['size'];
    		
    		list($width, $height, $type, $attr) = getimagesize($_FILES['profile_pic']['tmp_name']);
    		
    		if($width > 766 && $height != 511){
    			$errors['profile_pic'] = "Dimention must be 766px x 511px";
    		}
    		if($size > 80000){
    			$errors['profile_pic'] = "Maximum 80kb is allowed";
    		}
    		
    	}

    	if($errors['errors_number'] == 0){
    		$imageObject = new ImageResize($_FILES['profile_pic']['tmp_name']);
    		$imageUploaderObject = new ImageUploader($_FILES['profile_pic'], $imageObject);
    		$imageUploaderObject->setRoot(SITE_ROOT);
    		$imageUploaderObject->setPath('images/vendors/');
    		$imageUploaderObject->setLevel('../');
    		$image_name = $imageUploaderObject->imageUpload();

    		$vendor = Vendor::where('login_token',$login_token)->first();
    		$vendor->profile_photo = $image_name;
    		$vendor->save();
    	}

    }

}
$vendor = Vendor::where('login_token',$login_token)->first();
// $vendor = Vendor::get();
// echo "<pre>";
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
                                                <input type="text" id="company-name" class="search-input" placeholder="Company Name">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                        </div>
                                        <div>
                                            <label for="travel-distance" class="input-label">What's The Farthest You Will Travel</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <select name="travel-distance" id="travel-distance" class="search-input">
                                                    <option value="not-applicable" class="select-option-01">Not Applicable</option>
                                                    <option value="5-miles" class="select-option-01">5 miles</option>
                                                    <option value="10-miles" class="select-option-01">10 miles</option>
                                                    <option value="15-miles" class="select-option-01">15 miles</option>
                                                    <option value="25-miles" class="select-option-01">25 miles</option>
                                                    <option value="50-miles" class="select-option-01">50 miles</option>
                                                    <option value="75-miles" class="select-option-01">75 miles</option>
                                                    <option value="100-miles" class="select-option-01">100 miles</option>
                                                    <option value="150-miles" class="select-option-01">150 miles</option>
                                                    <option value="200-miles" class="select-option-01">200 miles</option>
                                                    <option value="300-miles" class="select-option-01">300 miles</option>
                                                    <option value="400-miles" class="select-option-01">400 miles</option>
                                                    <option value="500-miles" class="select-option-01">500 miles</option>
                                                    <option value="750-miles" class="select-option-01">750 miles</option>
                                                    <option value="1000-miles" class="select-option-01">1,000 miles</option>
                                                    <option value="continental-us" class="select-option-01">Continental US</option>
                                                    <option value="50-states" class="select-option-01">All 50 States</option>
                                                </select>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                        </div>
                                        <div>
                                            <label for="location" class="input-label">What's Your Starting Fee</label>
                                            <div class="spacer-10px"></div>
                                            <div class="flex-container">
                                                <b>$</b><input type="text" id="starting-fee" class="search-input" placeholder="example: 300.00">
                                            </div>
                                        </div>
                                    </div>
                                    <h3>Categories</h3>    
                                    <div class="form-input-container">
                                        <div>
                                            <label for="travel-distance" class="input-label">Primary Category</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <select name="category-01" id="category-01" class="search-input">
                                                    <option value="" class="select-option-01">Select Category</option>
                                                    <option value="" class="select-option-01"></option>
                                                </select>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                        </div>
                                        <div>
                                            <label for="travel-distance" class="input-label">Category 2</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <select name="category-02" id="category-02" class="search-input">
                                                    <option value="" class="select-option-01">Select Category</option>
                                                    <option value="" class="select-option-01"></option>
                                                </select>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                        </div>
                                        <div>
                                            <label for="travel-distance" class="input-label">Category 3</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <select name="category-03" id="category-03" class="search-input">
                                                    <option value="" class="select-option-01">Select Category</option>
                                                    <option value="" class="select-option-01"></option>
                                                </select>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                        </div>
                                    </div>
                                    <div class="form-input-container">
                                        <div>
                                            <label for="travel-distance" class="input-label">Category 4</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <select name="category-04" id="category-04" class="search-input">
                                                    <option value="" class="select-option-01">Select Category</option>
                                                    <option value="" class="select-option-01"></option>
                                                </select>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                        </div>
                                        <div>
                                            <label for="travel-distance" class="input-label">Category 5</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <select name="category-05" id="category-05" class="search-input">
                                                    <option value="" class="select-option-01">Select Category</option>
                                                    <option value="" class="select-option-01"></option>
                                                </select>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                        </div>
                                        <div>
                                            <label for="travel-distance" class="input-label">Category 6</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <select name="category-02" id="category-02" class="search-input">
                                                    <option value="" class="select-option-01">Select Category</option>
                                                    <option value="" class="select-option-01"></option>
                                                </select>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                        </div>
                                    </div>
                                    <div class="form-input-container">
                                        <div>
                                            <label for="travel-distance" class="input-label">Category 7</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <select name="category-07" id="category-07" class="search-input">
                                                    <option value="" class="select-option-01">Select Category</option>
                                                    <option value="" class="select-option-01"></option>
                                                </select>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                        </div>
                                        <div>
                                            <label for="travel-distance" class="input-label">Category 8</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <select name="category-08" id="category-08" class="search-input">
                                                    <option value="" class="select-option-01">Select Category</option>
                                                    <option value="" class="select-option-01"></option>
                                                </select>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                        </div>
                                        <div>
                                            <label for="travel-distance" class="input-label">Category 9</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <select name="category-09" id="category-09" class="search-input">
                                                    <option value="" class="select-option-01">Select Category</option>
                                                    <option value="" class="select-option-01"></option>
                                                </select>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                        </div>
                                    </div>
                                    <h3>Locations</h3> 
                                    <div class="form-input-container">
                                        <!-- LOCATION 1 -->
                                        <div>
                                            <label for="street-address1-01" class="input-label">Primary Location Street Address 1</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address1-01" class="search-input" placeholder="Street Address 1">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="street-address2-01" class="input-label">Primary Location Street Address 2</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address2-01" class="search-input" placeholder="Street Address 2">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <span class="input-label">Primary Location City/State/Zip</span>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="city-01" class="search-input" placeholder="City">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="profile-state-zip">
                                                <div>
                                                    <select name="state-01" id="state-01" class="search-input-mini">
                                                        <option value="" class="select-option-01">State</option>
                                                        <option value="" class="select-option-01"></option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <div>
                                                        <input type="text" id="zip-01" class="search-input-mini" placeholder="Zip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="phone-01" class="input-label">Primary Phone Number</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="tel" id="phone-01" class="search-input" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="example: 123-456-7890">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <label for="only-city-01" class="input-label">Only Show The City</label>
                                                <div class="spacer-10px"></div>
                                                <input type="checkbox" name="only-city-01" id="only-city-01">
                                                <div class="spacer-10px"></div>
                                                <div class="spacer-10px"></div>
                                            </div>
                                        </div>
                                        <!-- LOCATION 2 -->
                                        <div>
                                            <label for="street-address1-02" class="input-label">Location #2 Street Address 1</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address1-02" class="search-input" placeholder="Street Address 1">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="street-address2-02" class="input-label">Location #2 Street Address 2</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address2-02" class="search-input" placeholder="Street Address 2">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <span class="input-label">Location #2 City/State/Zip</span>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="city-02" class="search-input" placeholder="City">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="profile-state-zip">
                                                <div>
                                                    <select name="state-02" id="state-02" class="search-input-mini">
                                                        <option value="" class="select-option-01">State</option>
                                                        <option value="" class="select-option-01"></option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <div>
                                                        <input type="text" id="zip-02" class="search-input-mini" placeholder="Zip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="phone-02" class="input-label">Location #2 Phone Number</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="tel" id="phone-02" class="search-input" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="example: 123-456-7890">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <label for="only-city-02" class="input-label">Only Show The City</label>
                                                <div class="spacer-10px"></div>
                                                <input type="checkbox" name="only-city-02" id="only-city-02">
                                                <div class="spacer-10px"></div>
                                                <div class="spacer-10px"></div>
                                            </div>
                                        </div>
                                        <!-- LOCATION 3 -->
                                        <div>
                                            <label for="street-address1-03" class="input-label">Location #3 Street Address 1</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address1-03" class="search-input" placeholder="Street Address 1">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="street-address2-03" class="input-label">Location #3 Street Address 2</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address2-03" class="search-input" placeholder="Street Address 2">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <span class="input-label">Location #3 City/State/Zip</span>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="city-03" class="search-input" placeholder="City">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="profile-state-zip">
                                                <div>
                                                    <select name="state-03" id="state-03" class="search-input-mini">
                                                        <option value="" class="select-option-01">State</option>
                                                        <option value="" class="select-option-01"></option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <div>
                                                        <input type="text" id="zip-03" class="search-input-mini" placeholder="Zip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="phone-03" class="input-label">Location #3 Phone Number</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="tel" id="phone-03" class="search-input" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="example: 123-456-7890">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <label for="only-city-03" class="input-label">Only Show The City</label>
                                                <div class="spacer-10px"></div>
                                                <input type="checkbox" name="only-city-03" id="only-city-03">
                                                <div class="spacer-10px"></div>
                                                <div class="spacer-10px"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-input-container">
                                        <!-- LOCATION 4 -->
                                        <div>
                                            <label for="street-address1-04" class="input-label">Location #4 Street Address 1</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address1-04" class="search-input" placeholder="Street Address 1">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="street-address2-04" class="input-label">Location #4 Street Address 2</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address2-04" class="search-input" placeholder="Street Address 2">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <span class="input-label">Location #4 City/State/Zip</span>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="city-04" class="search-input" placeholder="City">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="profile-state-zip">
                                                <div>
                                                    <select name="state-04" id="state-04" class="search-input-mini">
                                                        <option value="" class="select-option-01">State</option>
                                                        <option value="" class="select-option-01"></option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <div>
                                                        <input type="text" id="zip-04" class="search-input-mini" placeholder="Zip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="phone-04" class="input-label">Location #4 Phone Number</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="tel" id="phone-04" class="search-input" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="example: 123-456-7890">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <label for="only-city-04" class="input-label">Only Show The City</label>
                                                <div class="spacer-10px"></div>
                                                <input type="checkbox" name="only-city-04" id="only-city-04">
                                                <div class="spacer-10px"></div>
                                                <div class="spacer-10px"></div>
                                            </div>
                                        </div>
                                        <!-- LOCATION 5 -->
                                        <div>
                                            <label for="street-address1-05" class="input-label">Location #5 Street Address 1</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address1-05" class="search-input" placeholder="Street Address 1">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="street-address2-05" class="input-label">Location #5 Street Address 2</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address2-05" class="search-input" placeholder="Street Address 2">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <span class="input-label">Location #5 City/State/Zip</span>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="city-05" class="search-input" placeholder="City">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="profile-state-zip">
                                                <div>
                                                    <select name="state-05" id="state-05" class="search-input-mini">
                                                        <option value="" class="select-option-01">State</option>
                                                        <option value="" class="select-option-01"></option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <div>
                                                        <input type="text" id="zip-05" class="search-input-mini" placeholder="Zip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="phone-05" class="input-label">Location #5 Phone Number</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="tel" id="phone-05" class="search-input" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="example: 123-456-7890">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <label for="only-city-05" class="input-label">Only Show The City</label>
                                                <div class="spacer-10px"></div>
                                                <input type="checkbox" name="only-city-05" id="only-city-05">
                                                <div class="spacer-10px"></div>
                                                <div class="spacer-10px"></div>
                                            </div>
                                        </div>
                                        <!-- LOCATION 6 -->
                                        <div>
                                            <label for="street-address1-06" class="input-label">Location #6 Street Address 1</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address1-06" class="search-input" placeholder="Street Address 1">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="street-address2-06" class="input-label">Location #6 Street Address 2</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address2-06" class="search-input" placeholder="Street Address 2">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <span class="input-label">Location #6 City/State/Zip</span>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="city-06" class="search-input" placeholder="City">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="profile-state-zip">
                                                <div>
                                                    <select name="state-06" id="state-06" class="search-input-mini">
                                                        <option value="" class="select-option-01">State</option>
                                                        <option value="" class="select-option-01"></option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <div>
                                                        <input type="text" id="zip-06" class="search-input-mini" placeholder="Zip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="phone-06" class="input-label">Location #6 Phone Number</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="tel" id="phone-06" class="search-input" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="example: 123-456-7890">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <label for="only-city-06" class="input-label">Only Show The City</label>
                                                <div class="spacer-10px"></div>
                                                <input type="checkbox" name="only-city-06" id="only-city-06">
                                                <div class="spacer-10px"></div>
                                                <div class="spacer-10px"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-input-container">
                                        <!-- LOCATION 7 -->
                                        <div>
                                            <label for="street-address1-07" class="input-label">Location #7 Street Address 1</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address1-07" class="search-input" placeholder="Street Address 1">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="street-address2-07" class="input-label">Location #7 Street Address 2</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address2-07" class="search-input" placeholder="Street Address 2">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <span class="input-label">Location #7 City/State/Zip</span>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="city-07" class="search-input" placeholder="City">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="profile-state-zip">
                                                <div>
                                                    <select name="state-07" id="state-07" class="search-input-mini">
                                                        <option value="" class="select-option-01">State</option>
                                                        <option value="" class="select-option-01"></option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <div>
                                                        <input type="text" id="zip-07" class="search-input-mini" placeholder="Zip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="phone-07" class="input-label">Location #7 Phone Number</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="tel" id="phone-07" class="search-input" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="example: 123-456-7890">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <label for="only-city-07" class="input-label">Only Show The City</label>
                                                <div class="spacer-10px"></div>
                                                <input type="checkbox" name="only-city-07" id="only-city-07">
                                                <div class="spacer-10px"></div>
                                                <div class="spacer-10px"></div>
                                            </div>
                                        </div>
                                        <!-- LOCATION 8 -->
                                        <div>
                                            <label for="street-address1-08" class="input-label">Location #8 Street Address 1</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address1-08" class="search-input" placeholder="Street Address 1">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="street-address2-08" class="input-label">Location #8 Street Address 2</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address2-08" class="search-input" placeholder="Street Address 2">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <span class="input-label">Location #8 City/State/Zip</span>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="city-08" class="search-input" placeholder="City">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="profile-state-zip">
                                                <div>
                                                    <select name="state-08" id="state-08" class="search-input-mini">
                                                        <option value="" class="select-option-01">State</option>
                                                        <option value="" class="select-option-01"></option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <div>
                                                        <input type="text" id="zip-08" class="search-input-mini" placeholder="Zip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="phone-08" class="input-label">Location #8 Phone Number</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="tel" id="phone-08" class="search-input" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="example: 123-456-7890">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <label for="only-city-08" class="input-label">Only Show The City</label>
                                                <div class="spacer-10px"></div>
                                                <input type="checkbox" name="only-city-08" id="only-city-08">
                                                <div class="spacer-10px"></div>
                                                <div class="spacer-10px"></div>
                                            </div>
                                        </div>
                                        <!-- LOCATION 9 -->
                                        <div>
                                            <label for="street-address1-09" class="input-label">Location #9 Street Address 1</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address1-09" class="search-input" placeholder="Street Address 1">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="street-address2-09" class="input-label">Location #9 Street Address 2</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="street-address2-09" class="search-input" placeholder="Street Address 2">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <span class="input-label">Location #9 City/State/Zip</span>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="city-09" class="search-input" placeholder="City">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="profile-state-zip">
                                                <div>
                                                    <select name="state-09" id="state-09" class="search-input-mini">
                                                        <option value="" class="select-option-01">State</option>
                                                        <option value="" class="select-option-01"></option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <div>
                                                        <input type="text" id="zip-09" class="search-input-mini" placeholder="Zip">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <label for="phone-09" class="input-label">Location #9 Phone Number</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="tel" id="phone-09" class="search-input" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="example: 123-456-7890">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <label for="only-city-09" class="input-label">Only Show The City</label>
                                                <div class="spacer-10px"></div>
                                                <input type="checkbox" name="only-city-09" id="only-city-09">
                                                <div class="spacer-10px"></div>
                                                <div class="spacer-10px"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3>Keywords</h3>
                                    <div class="form-input-container">
                                        <div class="form-input-search">
                                            <label for="keywords" class="input-label">Enter Comma Separated Keywords (70 character limit...aprx 10 words)</label>
                                            <div class="spacer-10px"></div>
                                            <div>
                                                <input type="text" id="keyword-vendor-search" class="search-input" placeholder="example: party band, caterer, photographer">
                                            </div>
                                            <div class="spacer-10px"></div>
                                            <div class="form-submit-container">
                                                <a href="" class="button-03 button-link-text white-text">Add Keywords</a>
                                            </div>
                                        </div>
                                        <div class="form-input-search">
                                            <label for="location" class="input-label">Keyword List</label>
                                            <div class="spacer-10px"></div>
                                            <div class="list-container">
                                                <ul class="category-ul">
                                                    <li class="category-li"><div>Jazz</div><div><button class="small-button primary white-text">Delete</button></div></li>
                                                    <li class="category-li"><div>Blues</div><div><button class="small-button primary white-text">Delete</button></div></li>
                                                    <li class="category-li"><div>Funk</div><div><button class="small-button primary white-text">Delete</button></div></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-submit-container">
                                        <input type="submit" class="button-01 white-text" id="vendor-overview-submit" value="Submit"/>
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
    
</body>
</html>