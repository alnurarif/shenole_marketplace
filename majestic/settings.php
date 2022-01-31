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

$vendor_categories = Category::get();

$show_category_list = '';
foreach($vendor_categories as $vendor_category){
	$show_category_list .= '<li class="category-li">';
		$show_category_list .= '<div>'.$vendor_category->name.'</div>';
		$show_category_list .= '<div>';
			$show_category_list .= '<form method="post">';
				$show_category_list .= '<input type="hidden" name="delete_category_operation" value="'.$vendor_category->id.'"/>';
				$show_category_list .= '<input type="hidden" name="vendor_category_id" value="'.$vendor_category->id.'"/>';
				$show_category_list .= '<button class="small-button primary white-text">Delete</button>';
			$show_category_list .= '</form>';
		$show_category_list .= '</div>';
	$show_category_list .= '</li>';
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
								<form method="post" class="full-width-form">
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
                    </div>
                    <div class="profile-section" id="ads">
                        <h2>Ads</h2>
                    </div>
                    <div class="profile-section" id="memberships">
                        <h2>Memberships</h2>
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
</body>
</html>