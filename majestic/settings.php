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
use Shenole_project\models\Majestic_ad_setting;
use Shenole_project\models\Vendor;
use Shenole_project\models\Vendor_membership_level;
use Shenole_project\models\Ad_keyword_quantity_pricing_setting;
use Shenole_project\models\Ad_category_quantity_pricing_setting;
use Shenole_project\models\Ad_location_quantity_pricing_setting;
use Shenole_project\models\Ad_banner_quantity_pricing_setting;


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
    if(isset($_POST['add_edit_ads_operation'])){
        $keyword_quantity_price_infos = (isset($_POST['keyword_quantity_price_infos'])) ? $_POST['keyword_quantity_price_infos'] : null ;
        $category_quantity_price_infos = (isset($_POST['category_quantity_price_infos'])) ? $_POST['category_quantity_price_infos'] : null ;
        $location_quantity_price_infos = (isset($_POST['location_quantity_price_infos'])) ? $_POST['location_quantity_price_infos'] : null ;
        $banner_quantity_price_infos = (isset($_POST['banner_quantity_price_infos'])) ? $_POST['banner_quantity_price_infos'] : null ;
        if($majestic->ad_setting === null){
            // echo "<pre>";
            // var_dump($keyword_quantity_price_infos);
            // exit;

            $ad_setting_object = new Majestic_ad_setting;
            $ad_setting_object->client_ad_display_price = $_POST['client_ad_display_price'];
            $ad_setting_object->vendor_one_day_ad_price = $_POST['vendor_one_day_ad_price'];
            $ad_setting_object->vendor_one_week_ad_price = $_POST['vendor_one_week_ad_price'];
            $ad_setting_object->vendor_one_month_ad_price = $_POST['vendor_one_month_ad_price'];
            $ad_setting_object->vendor_three_months_ad_price = $_POST['vendor_three_months_ad_price'];
            $ad_setting_object->vendor_six_months_ad_price = $_POST['vendor_six_months_ad_price'];
            $ad_setting_object->vendor_one_year_ad_price = $_POST['vendor_one_year_ad_price'];
            $ad_setting_object->keyword_qty_price = $_POST['keyword_qty_price'];
            $ad_setting_object->category_qty_price = $_POST['category_qty_price'];
            $ad_setting_object->location_qty_price = $_POST['location_qty_price'];
            $ad_setting_object->banner_qty_price = $_POST['banner_qty_price'];
            $ad_setting_object->banner_top_left_price = $_POST['banner_top_left_price'];
            $ad_setting_object->banner_top_right_price = $_POST['banner_top_right_price'];
            $ad_setting_object->banner_bottom_left_price = $_POST['banner_bottom_left_price'];
            $ad_setting_object->banner_bottom_right_price = $_POST['banner_bottom_right_price'];
            $ad_setting_object->banners_two_top_price = $_POST['banners_two_top_price'];
            $ad_setting_object->banners_two_bottom_price = $_POST['banners_two_bottom_price'];
            $ad_setting_object->banners_all_four_price = $_POST['banners_all_four_price'];
            $ad_setting_object->keyword_qty = $_POST['keyword_qty'];
            $ad_setting_object->category_qty = $_POST['category_qty'];
            $ad_setting_object->location_qty = $_POST['location_qty'];
            $ad_setting_object->banner_qty = $_POST['banner_qty'];
                
            $ad_setting_object->vendor_one_day_ad = (isset($_POST['vendor_one_day_ad'])) ? 1 : 0 ;
            $ad_setting_object->vendor_one_week_ad = (isset($_POST['vendor_one_week_ad'])) ? 1 : 0 ;
            $ad_setting_object->vendor_one_month_ad = (isset($_POST['vendor_one_month_ad'])) ? 1 : 0 ;
            $ad_setting_object->vendor_three_months_ad = (isset($_POST['vendor_three_months_ad'])) ? 1 : 0 ;
            $ad_setting_object->vendor_six_months_ad = (isset($_POST['vendor_six_months_ad'])) ? 1 : 0 ;
            $ad_setting_object->vendor_one_year_ad = (isset($_POST['vendor_one_year_ad'])) ? 1 : 0 ;
            
            $ad_setting = $majestic->ad_setting()->save($ad_setting_object);
            

            Ad_keyword_quantity_pricing_setting::where('ad_setting_id',$ad_setting->id)->delete();
            foreach($keyword_quantity_price_infos as $key=>$single_info){
                $single_info = explode('|||',$single_info);
                $ad_keyword_quantity_pricing_setting = new Ad_keyword_quantity_pricing_setting;
                $ad_keyword_quantity_pricing_setting->ad_setting_id = $ad_setting->id;
                $ad_keyword_quantity_pricing_setting->quantity = $single_info[0];
                $ad_keyword_quantity_pricing_setting->price = $single_info[1];
                $ad_keyword_quantity_pricing_setting->save();
            }

            Ad_category_quantity_pricing_setting::where('ad_setting_id',$ad_setting->id)->delete();
            foreach($category_quantity_price_infos as $key=>$single_info){
                $single_info = explode('|||',$single_info);
                $ad_category_quantity_pricing_setting = new Ad_category_quantity_pricing_setting;
                $ad_category_quantity_pricing_setting->ad_setting_id = $ad_setting->id;
                $ad_category_quantity_pricing_setting->quantity = $single_info[0];
                $ad_category_quantity_pricing_setting->price = $single_info[1];
                $ad_category_quantity_pricing_setting->save();
            }

            Ad_location_quantity_pricing_setting::where('ad_setting_id',$ad_setting->id)->delete();
            foreach($location_quantity_price_infos as $key=>$single_info){
                $single_info = explode('|||',$single_info);
                $ad_location_quantity_pricing_setting = new Ad_location_quantity_pricing_setting;
                $ad_location_quantity_pricing_setting->ad_setting_id = $ad_setting->id;
                $ad_location_quantity_pricing_setting->quantity = $single_info[0];
                $ad_location_quantity_pricing_setting->price = $single_info[1];
                $ad_location_quantity_pricing_setting->save();
            }

            Ad_banner_quantity_pricing_setting::where('ad_setting_id',$ad_setting->id)->delete();
            foreach($banner_quantity_price_infos as $key=>$single_info){
                $single_info = explode('|||',$single_info);
                $ad_banner_quantity_pricing_setting = new Ad_banner_quantity_pricing_setting;
                $ad_banner_quantity_pricing_setting->ad_setting_id = $ad_setting->id;
                $ad_banner_quantity_pricing_setting->quantity = $single_info[0];
                $ad_banner_quantity_pricing_setting->price = $single_info[1];
                $ad_banner_quantity_pricing_setting->save();
            }
        }else{
            $majestic->ad_setting->client_ad_display_price = $_POST['client_ad_display_price'];
            $majestic->ad_setting->vendor_one_day_ad_price = $_POST['vendor_one_day_ad_price'];
            $majestic->ad_setting->vendor_one_week_ad_price = $_POST['vendor_one_week_ad_price'];
            $majestic->ad_setting->vendor_one_month_ad_price = $_POST['vendor_one_month_ad_price'];
            $majestic->ad_setting->vendor_three_months_ad_price = $_POST['vendor_three_months_ad_price'];
            $majestic->ad_setting->vendor_six_months_ad_price = $_POST['vendor_six_months_ad_price'];
            $majestic->ad_setting->vendor_one_year_ad_price = $_POST['vendor_one_year_ad_price'];
            $majestic->ad_setting->keyword_qty_price = $_POST['keyword_qty_price'];
            $majestic->ad_setting->category_qty_price = $_POST['category_qty_price'];
            $majestic->ad_setting->location_qty_price = $_POST['location_qty_price'];
            $majestic->ad_setting->banner_qty_price = $_POST['banner_qty_price'];
            $majestic->ad_setting->banner_top_left_price = $_POST['banner_top_left_price'];
            $majestic->ad_setting->banner_top_right_price = $_POST['banner_top_right_price'];
            $majestic->ad_setting->banner_bottom_left_price = $_POST['banner_bottom_left_price'];
            $majestic->ad_setting->banner_bottom_right_price = $_POST['banner_bottom_right_price'];
            $majestic->ad_setting->banners_two_top_price = $_POST['banners_two_top_price'];
            $majestic->ad_setting->banners_two_bottom_price = $_POST['banners_two_bottom_price'];
            $majestic->ad_setting->banners_all_four_price = $_POST['banners_all_four_price'];
            $majestic->ad_setting->keyword_qty = $_POST['keyword_qty'];
            $majestic->ad_setting->category_qty = $_POST['category_qty'];
            $majestic->ad_setting->location_qty = $_POST['location_qty'];
            $majestic->ad_setting->banner_qty = $_POST['banner_qty'];
                
            $majestic->ad_setting->vendor_one_day_ad = (isset($_POST['vendor_one_day_ad'])) ? 1 : 0 ;
            $majestic->ad_setting->vendor_one_week_ad = (isset($_POST['vendor_one_week_ad'])) ? 1 : 0 ;
            $majestic->ad_setting->vendor_one_month_ad = (isset($_POST['vendor_one_month_ad'])) ? 1 : 0 ;
            $majestic->ad_setting->vendor_three_months_ad = (isset($_POST['vendor_three_months_ad'])) ? 1 : 0 ;
            $majestic->ad_setting->vendor_six_months_ad = (isset($_POST['vendor_six_months_ad'])) ? 1 : 0 ;
            $majestic->ad_setting->vendor_one_year_ad = (isset($_POST['vendor_one_year_ad'])) ? 1 : 0 ;

            $majestic->ad_setting->save();
            Ad_keyword_quantity_pricing_setting::where('ad_setting_id',$majestic->ad_setting->id)->delete();
            foreach($keyword_quantity_price_infos as $key=>$single_info){
                $single_info = explode('|||',$single_info);
                $ad_keyword_quantity_pricing_setting = new Ad_keyword_quantity_pricing_setting;
                $ad_keyword_quantity_pricing_setting->ad_setting_id = $majestic->ad_setting->id;
                $ad_keyword_quantity_pricing_setting->quantity = $single_info[0];
                $ad_keyword_quantity_pricing_setting->price = $single_info[1];
                $ad_keyword_quantity_pricing_setting->save();
            }

            Ad_category_quantity_pricing_setting::where('ad_setting_id',$majestic->ad_setting->id)->delete();
            foreach($category_quantity_price_infos as $key=>$single_info){
                $single_info = explode('|||',$single_info);
                $ad_category_quantity_pricing_setting = new Ad_category_quantity_pricing_setting;
                $ad_category_quantity_pricing_setting->ad_setting_id = $majestic->ad_setting->id;
                $ad_category_quantity_pricing_setting->quantity = $single_info[0];
                $ad_category_quantity_pricing_setting->price = $single_info[1];
                $ad_category_quantity_pricing_setting->save();
            }

            Ad_location_quantity_pricing_setting::where('ad_setting_id',$majestic->ad_setting->id)->delete();
            foreach($location_quantity_price_infos as $key=>$single_info){
                $single_info = explode('|||',$single_info);
                $ad_location_quantity_pricing_setting = new Ad_location_quantity_pricing_setting;
                $ad_location_quantity_pricing_setting->ad_setting_id = $majestic->ad_setting->id;
                $ad_location_quantity_pricing_setting->quantity = $single_info[0];
                $ad_location_quantity_pricing_setting->price = $single_info[1];
                $ad_location_quantity_pricing_setting->save();
            }

            Ad_banner_quantity_pricing_setting::where('ad_setting_id',$majestic->ad_setting->id)->delete();
            foreach($banner_quantity_price_infos as $key=>$single_info){
                $single_info = explode('|||',$single_info);
                $ad_banner_quantity_pricing_setting = new Ad_banner_quantity_pricing_setting;
                $ad_banner_quantity_pricing_setting->ad_setting_id = $majestic->ad_setting->id;
                $ad_banner_quantity_pricing_setting->quantity = $single_info[0];
                $ad_banner_quantity_pricing_setting->price = $single_info[1];
                $ad_banner_quantity_pricing_setting->save();
            }
            
        }
        $majestic = Majestic::where('login_token',$login_token)->with('ad_setting')->first();
        $show_ad_section = true;
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
    if(isset($_POST['add_paypal_operation'])){
        $majestic->paypal_client_id = trim($_POST['paypal_client_id']);
		$majestic->paypal_secret_id = trim($_POST['paypal_secret_id']);
		$majestic->save();
        $show_paypal_section = true;
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
// echo "<pre>";
// var_dump($majestic->ad_setting->keyword_quantity_pricing_settings->count()); 
// var_dump($majestic->ad_setting->banner_quantity_pricing_settings->count()); 
// var_dump($majestic->ad_setting->category_quantity_pricing_settings->count()); 
// var_dump($majestic->ad_setting->location_quantity_pricing_settings->count()); 
// exit;

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
                    <div class="profile-section" id="paypal" data-show-initially="<?php echo (isset($show_paypal_section))? '1' : '0' ; ?>">
                        <h2>Paypal</h2>
                        <div class="full-width-form">
                            <form method="post">
                                <div class="form-input-container">
                                    <div class="form-input-search">
                                        <input type="hidden" name="add_paypal_operation" value="1"/>
                                        <label for="paypal-client-id" class="input-label">Paypal Client ID</label>
                                        <div class="spacer-10px"></div>
                                        <div>
                                            <input value="<?php echo $majestic->paypal_client_id; ?>" name="paypal_client_id" type="text" id="paypal-client-id" class="search-input" placeholder="Place your Paypal client ID here">
                                        </div>
                                        <div class="spacer-10px"></div>
                                    </div>
                                    <div class="form-input-search">
                                        <label for="paypal-secret-id" class="input-label">Paypal Secret ID</label>
                                        <div class="spacer-10px"></div>
                                        <div>
                                            <input value="<?php echo $majestic->paypal_secret_id; ?>" name="paypal_secret_id" type="text" id="paypal-secret-id" class="search-input" placeholder="Place your Paypal secret ID here">
                                        </div>
                                        <div class="spacer-10px"></div>
                                    </div>
                                </div>
                                <div class="form-submit-container">
                                    <button type="submit" class="button-01 white-text" id="majestic-paypal-submit">Submit</button>
                                </div>
                            </form>
                        </div>
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
                    <div class="profile-section" id="ads" data-show-initially="<?php echo (isset($show_ad_section))? '1' : '0' ; ?>">
                        <h2>Ads</h2>
                        <form method="post" class="full-width-form">
                            <input type="hidden" name="add_edit_ads_operation">
                            <h3>Client: Ad Display Pricing</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="client-ad-display" class="input-label">Set Client Ad Display Price&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="client_ad_display_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->client_ad_display_price : ''; ?>" type="number" id="client-ad-display" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                            </div>
                            <h3>Vendor: Campaign Length Pricing</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="campaign-length-1day" class="input-label">1 Day</label>
                                    <div class="spacer-10px"></div>
                                    <input name="vendor_one_day_ad" <?php echo ($majestic->ad_setting != null && $majestic->ad_setting->vendor_one_day_ad == 1) ? 'checked' : ''; ?> type="checkbox" id="campaign-length-1day">
                                    <div class="spacer-10px"></div>
                                    <label for="campaign-price-1day" class="input-label">Set Campaign Length Price&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="vendor_one_day_ad_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->vendor_one_day_ad_price : ''; ?>" type="number" id="campaign-price-1day" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="campaign-length-1week" class="input-label">1 Week</label>
                                    <div class="spacer-10px"></div>
                                    <input name="vendor_one_week_ad" <?php echo ($majestic->ad_setting != null && $majestic->ad_setting->vendor_one_week_ad == 1) ? 'checked' : ''; ?> type="checkbox" id="campaign-length-1week">
                                    <div class="spacer-10px"></div>
                                    <label for="campaign-price-1week" class="input-label">Set Campaign Length Price&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="vendor_one_week_ad_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->vendor_one_week_ad_price : ''; ?>" type="number" id="campaign-price-1week" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="campaign-length-1month" class="input-label">1 Month</label>
                                    <div class="spacer-10px"></div>
                                    <input name="vendor_one_month_ad" <?php echo ($majestic->ad_setting != null && $majestic->ad_setting->vendor_one_month_ad == 1) ? 'checked' : ''; ?> type="checkbox" id="campaign-length-1month">
                                    <div class="spacer-10px"></div>
                                    <label for="campaign-price-1month" class="input-label">Set Campaign Length Price&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="vendor_one_month_ad_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->vendor_one_month_ad_price : ''; ?>" type="number" id="campaign-price-1month" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="campaign-length-3months" class="input-label">3 Months</label>
                                    <div class="spacer-10px"></div>
                                    <input name="vendor_three_months_ad" <?php echo ($majestic->ad_setting != null && $majestic->ad_setting->vendor_three_months_ad == 1) ? 'checked' : ''; ?> type="checkbox" id="campaign-length-3months">
                                    <div class="spacer-10px"></div>
                                    <label for="campaign-price-3months" class="input-label">Set Campaign Length Price&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="vendor_three_months_ad_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->vendor_three_months_ad_price : ''; ?>" type="number" id="campaign-price-3months" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="campaign-length-6months" class="input-label">6 Months</label>
                                    <div class="spacer-10px"></div>
                                    <input name="vendor_six_months_ad" <?php echo ($majestic->ad_setting != null && $majestic->ad_setting->vendor_six_months_ad == 1) ? 'checked' : ''; ?> type="checkbox" id="campaign-length-6months">
                                    <div class="spacer-10px"></div>
                                    <label for="campaign-price-6months" class="input-label">Set Campaign Length Price&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="vendor_six_months_ad_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->vendor_six_months_ad_price : ''; ?>" type="number" id="campaign-price-6months" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="campaign-length-1year" class="input-label">1 Year</label>
                                    <div class="spacer-10px"></div>
                                    <input name="vendor_one_year_ad" <?php echo ($majestic->ad_setting != null && $majestic->ad_setting->vendor_one_year_ad == 1) ? 'checked' : ''; ?> type="checkbox" id="campaign-length-1year">
                                    <div class="spacer-10px"></div>
                                    <label for="campaign-price-1year" class="input-label">Set Campaign Length Price&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="vendor_one_year_ad_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->vendor_one_year_ad_price : ''; ?>" type="number" id="campaign-price-1year" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                            </div>
                            <h3>Vendor: Keyword Quantity & Pricing</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="Keyword-quantity" class="input-label">Set Keyword Quantity</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="keyword_qty" type="number" id="keyword_quantity" class="search-input" placeholder="example: 10">
                                    </div>
                                    <div class="spacer-10px"></div>
                                    <div id="hidden_keyword_quantity_price_input" class="display_none">
                                        <?php foreach($majestic->ad_setting->keyword_quantity_pricing_settings as $key=>$single_quantity_pricing_setting){ ?>
                                            <?php $full_keyword_info_joined = $single_quantity_pricing_setting->quantity.'|||'.$single_quantity_pricing_setting->price; ?>
                                            <input type="hidden" value="<?php echo $full_keyword_info_joined; ?>" name="keyword_quantity_price_infos[]" id="keyword_quantity_price_infos_input_<?php echo $key+1; ?>">
                                        <?php } ?>  
                                    </div>
                                    <div class="form-submit-container">
                                        <button type="button" class="button-03 button-link-text white-text" id="add_keyword_info_to_list">Add Keyword Settings</button>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="keyword-pricing" class="input-label">Set Keyword Pricing&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="keyword_qty_price" type="number" id="keyword_price" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location" class="input-label">Keyword Quantity & Pricing List</label>
                                    <div class="spacer-10px"></div>
                                    <div class="list-container">
                                        <ul class="category-ul" id="added_keyword_quantity_pricing_list">
                                        <?php foreach($majestic->ad_setting->keyword_quantity_pricing_settings as $key=>$single_quantity_pricing_setting){ ?>
                                            <li class="category-li single_added_keyword_quantity_pricing" id="single_added_keyword_quantity_pricing_<?php echo $key+1;?>">
                                                <div class="display_none keyword_quantity_pricing_object">
                                                    <span class="keyword_quantity"><?php echo $single_quantity_pricing_setting->quantity; ?></span>
                                                    <span class="keyword_price"><?php echo $single_quantity_pricing_setting->price; ?></span>
                                                </div>
                                                <div>
                                                    <span><?php echo $single_quantity_pricing_setting->quantity; ?></span> keywords | 
                                                    <span>$<?php echo $single_quantity_pricing_setting->price; ?></span>
                                                </div>
                                                 
                                                <div class="delete_button_container"><button type="button" class="small-button primary white-text delete_keyword_quantity_pricing_from_list" id="delete_keyword_quantity_pricing_<?php echo $key+1; ?>">Delete</button></div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    </div>
                                </div>
                            </div>
                            <h3>Vendor: Category Quantity & Pricing</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="category-quantity" class="input-label">Set Category Quantity</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="category_qty" type="number" id="category_quantity" class="search-input" placeholder="example: 10">
                                    </div>
                                    <div class="spacer-10px"></div>
                                    <div id="hidden_category_quantity_price_input" class="display_none">
                                        <?php foreach($majestic->ad_setting->category_quantity_pricing_settings as $key=>$single_quantity_pricing_setting){ ?>
                                            <?php $full_category_info_joined = $single_quantity_pricing_setting->quantity.'|||'.$single_quantity_pricing_setting->price; ?>
                                            <input type="hidden" value="<?php echo $full_category_info_joined; ?>" name="category_quantity_price_infos[]" id="category_quantity_price_infos_input_<?php echo $key+1; ?>">
                                        <?php } ?>  
                                    </div>
                                    <div class="form-submit-container">
                                        <button type="button" class="button-03 button-link-text white-text" id="add_category_info_to_list">Add Category Settings</button>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="category-quantity-price" class="input-label">Set Category Pricing&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="category_qty_price" type="number" id="category_price" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location" class="input-label">Category Quantity & Pricing List</label>
                                    <div class="spacer-10px"></div>
                                    <div class="list-container">
                                        <ul class="category-ul" id="added_category_quantity_pricing_list">
                                        <?php foreach($majestic->ad_setting->category_quantity_pricing_settings as $key=>$single_quantity_pricing_setting){ ?>
                                            <li class="category-li single_added_category_quantity_pricing" id="single_added_category_quantity_pricing_<?php echo $key+1;?>">
                                                <div class="display_none category_quantity_pricing_object">
                                                    <span class="category_quantity"><?php echo $single_quantity_pricing_setting->quantity; ?></span>
                                                    <span class="category_price"><?php echo $single_quantity_pricing_setting->price; ?></span>
                                                </div>
                                                <div>
                                                    <span><?php echo $single_quantity_pricing_setting->quantity; ?></span> categories | 
                                                    <span>$<?php echo $single_quantity_pricing_setting->price; ?></span>
                                                </div>
                                                <br>
                                                <div class="delete_button_container"><button type="button" class="small-button primary white-text delete_category_quantity_pricing_from_list" id="delete_category_quantity_pricing_<?php echo $key+1; ?>"">Delete</button></div>
                                            </li>
                                        <?php } ?>
                                        
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <h3>Vendor: Location Quantity & Pricing</h3>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="location-quantity" class="input-label">Set Location Quantity</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="location_qty" type="number" id="location_quantity" class="search-input" placeholder="example: 10">
                                    </div>
                                    <div class="spacer-10px"></div>
                                    <div id="hidden_location_quantity_price_input" class="display_none">
                                        <?php foreach($majestic->ad_setting->location_quantity_pricing_settings as $key=>$single_quantity_pricing_setting){ ?>
                                            <?php $full_location_info_joined = $single_quantity_pricing_setting->quantity.'|||'.$single_quantity_pricing_setting->price; ?>
                                            <input type="hidden" value="<?php echo $full_location_info_joined; ?>" name="location_quantity_price_infos[]" id="location_quantity_price_infos_input_<?php echo $key+1; ?>">
                                        <?php } ?>  
                                    </div>
                                    <div class="form-submit-container">
                                        <button type="button" class="button-03 button-link-text white-text" id="add_location_info_to_list">Add Location Settings</button>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location-quantity-price" class="input-label">Set Location Pricing&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="location_qty_price" type="number" id="location_price" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="location" class="input-label">Location Quantity & Pricing List</label>
                                    <div class="spacer-10px"></div>
                                    <div class="list-container">
                                        <ul class="category-ul" id="added_location_quantity_pricing_list">
                                        <?php foreach($majestic->ad_setting->location_quantity_pricing_settings as $key=>$single_quantity_pricing_setting){ ?>
                                            <li class="category-li single_added_location_quantity_pricing" id="single_added_location_quantity_pricing_<?php echo $key+1;?>">
                                                <div class="display_none location_quantity_pricing_object">
                                                    <span class="location_quantity"><?php echo $single_quantity_pricing_setting->quantity; ?></span>
                                                    <span class="location_price"><?php echo $single_quantity_pricing_setting->price; ?></span>
                                                </div>
                                                <div>
                                                    <span><?php echo $single_quantity_pricing_setting->quantity; ?></span> Locations | 
                                                    <span>$<?php echo $single_quantity_pricing_setting->price; ?></span></div>
                                                <br>
                                                <div class="delete_button_container"><button class="small-button primary white-text delete_location_quantity_pricing_from_list" id="delete_location_quantity_pricing_<?php echo $key+1; ?>">Delete</button></div>
                                            </li>
                                        <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <h3>Banner Placement Pricing</h3>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="banner-quantity" class="input-label">Set Banner Quantity</label>
                                    <div class="spacer-10px"></div>
                                    <div>
                                        <input name="banner_qty" type="number" id="banner_quantity" class="search-input" placeholder="example: 2">
                                    </div>
                                    <div class="spacer-10px"></div>
                                    <div id="hidden_banner_quantity_price_input" class="display_none">
                                        <?php foreach($majestic->ad_setting->banner_quantity_pricing_settings as $key=>$single_quantity_pricing_setting){ ?>
                                            <?php $full_banner_info_joined = $single_quantity_pricing_setting->quantity.'|||'.$single_quantity_pricing_setting->price; ?>
                                            <input type="hidden" value="<?php echo $full_banner_info_joined; ?>" name="banner_quantity_price_infos[]" id="banner_quantity_price_infos_input_<?php echo $key+1; ?>">
                                        <?php } ?>  
                                    </div>
                                    <div class="form-submit-container">
                                        <button type="button" class="button-03 button-link-text white-text" id="add_banner_info_to_list">Add Banner Settings</a>
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="banner-quantity-price" class="input-label">Set Banner Pricing&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="banner_qty_price" type="number" id="banner_price" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="banner" class="input-label">Banner Quantity & Pricing List</label>
                                    <div class="spacer-10px"></div>
                                    <div class="list-container">
                                        <ul class="category-ul" id="added_banner_quantity_pricing_list">
                                        <?php foreach($majestic->ad_setting->banner_quantity_pricing_settings as $key=>$single_quantity_pricing_setting){ ?>
                                            <li class="category-li single_added_banner_quantity_pricing" id="single_added_banner_quantity_pricing_<?php echo $key+1;?>">
                                                <div class="display_none banner_quantity_pricing_object">
                                                    <span class="banner_quantity"><?php echo $single_quantity_pricing_setting->quantity; ?></span>
                                                    <span class="banner_price"><?php echo $single_quantity_pricing_setting->price; ?></span>
                                                </div>
                                                <div>
                                                    <span><?php echo $single_quantity_pricing_setting->quantity; ?></span> Banners | 
                                                    <span>$<?php echo $single_quantity_pricing_setting->price; ?></span>
                                                </div>
                                                <br>
                                                <div class="delete_button_container"><button type="button" class="small-button primary white-text delete_banner_quantity_pricing_from_list" id="delete_banner_quantity_pricing_<?php echo $key+1; ?>">Delete</button></div>
                                            </li>
                                        <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="top-left" class="input-label">Place Ad In Top Left Banner</label>
                                    <div class="spacer-10px"></div>
                                    <img src="../images/main-images/top-left.jpg" alt="Place ad in top left banner." class="fluid-image" id="top-left">
                                    <label for="majestic-top-left-placement-price" class="input-label">Set Placement Pricing&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="banner_top_left_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->banner_top_left_price : ''; ?>" type="number" id="majestic-top-left-placement-price" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="top-right" class="input-label">Place Ad In Top Right Banner</label>
                                    <div class="spacer-10px"></div>
                                    <img src="../images/main-images/top-right.jpg" alt="Place ad in top right banner." class="fluid-image" id="top-right">
                                    <label for="majestic-top-right-price" class="input-label">Set Placement Pricing&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="banner_top_right_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->banner_top_right_price : ''; ?>" type="number" id="majestic-top-right-price" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="bottom-left" class="input-label">Place Ad In Bottom Left Banner</label>
                                    <div class="spacer-10px"></div>
                                    <img src="../images/main-images/bottom-left.jpg" alt="Place ad in bottom left banner." class="fluid-image" id="bottom-left">
                                    <label for="majestic-bottom-left-price" class="input-label">Set Placement Pricing&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="banner_bottom_left_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->banner_bottom_left_price : ''; ?>" type="number" id="majestic-bottom-left-price" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="gottom-right" class="input-label">Place Ad In Bottom Right Banner</label>
                                    <div class="spacer-10px"></div>
                                    <img src="../images/main-images/bottom-right.jpg" alt="Place ad in bottom right banner." class="fluid-image" id="bottom-right">
                                    <label for="majestic-bottom-right-price" class="input-label">Set Placement Pricing&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="banner_bottom_right_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->banner_bottom_right_price : ''; ?>" type="number" id="majestic-bottom-right-price" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="two-top" class="input-label">Place Ad In Two Top Banners</label>
                                    <div class="spacer-10px"></div>
                                    <img src="../images/main-images/two-top.jpg" alt="Place ad in two top banners." class="fluid-image" id="two-top">
                                    <label for="majestic-two-top-price" class="input-label">Set Placement Pricing&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="banners_two_top_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->banners_two_top_price : ''; ?>" type="number" id="majestic-two-top-price" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                                <div class="form-input-search">
                                    <label for="two-bottom" class="input-label">Place Ad In Two Bottom Banners</label>
                                    <div class="spacer-10px"></div>
                                    <img src="../images/main-images/two-bottom.jpg" alt="Place ad in two bottom banners." class="fluid-image" id="two-bottom">
                                    <label for="majestic-two-bottom-price" class="input-label">Set Placement Pricing&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="banners_two_bottom_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->banners_two_bottom_price : ''; ?>" type="number" id="majestic-two-bottom-price" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                            </div>
                            <div class="form-input-container">
                                <div class="form-input-search">
                                    <label for="all-banners" class="input-label">Place Ad In All Banners</label>
                                    <div class="spacer-10px"></div>
                                    <img src="../images/main-images/all-banners.jpg" alt="Place ad in all banners." class="fluid-image" id="all-banners">
                                    <label for="majestic-all-banners-price" class="input-label">Set Placement Pricing&nbsp;&nbsp;&nbsp;&nbsp;<i>($0.00 will set to free)</i></label>
                                    <div class="spacer-10px"></div>
                                    <div class="flex-container">
                                        <b>$</b>&nbsp;<input name="banners_all_four_price" value="<?php echo ($majestic->ad_setting != null) ? $majestic->ad_setting->banners_all_four_price : ''; ?>" type="number" id="majestic-all-banners-price" class="search-input" placeholder="example: 2.99">
                                    </div>
                                </div>
                            </div>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="form-submit-container">
                                <button type="submit" class="button-01 white-text" name="majestic-ad-settings-submit" id="majestic-ad-settings-submit">Submit</button>
                            </div>
                        </form>
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
    <script src="../js/custom.js"></script>
</body>
</html>