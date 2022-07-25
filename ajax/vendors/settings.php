<?php
session_start();
require "../../start.php";
use \Gumlet\ImageResize;
use Shenole_project\models\Client;
use Shenole_project\models\Vendor;
use Shenole_project\helpers\Validator;
use Shenole_project\helpers\MyHelpers;
use Shenole_project\helpers\UserHelper;
use Shenole_project\utils\ImageUploader;
use Shenole_project\models\Campaign_draft;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\repositories\StaffRepository;
use Shenole_project\repositories\ClientRepository;
use Shenole_project\repositories\VendorRepository;
use Shenole_project\repositories\MajesticRepository;
use Shenole_project\models\Vendor_draft_campaign_keyword;
use Shenole_project\models\Vendor_draft_campaign_category;
use Shenole_project\models\Vendor_draft_campaign_location;

if(isset($_POST['operation_name']) && $_POST['operation_name'] == 'ad_submission'){
    $error_number = 0;
    $errors = [];
    $messages = [];
    $data = null;
    
    $keywords = json_decode($_POST['keywords_array']);
    $categories = json_decode($_POST['categories_array']);
    $locations = json_decode($_POST['locations_array']);

    if($_POST['campaign_length'] == ""){
        array_push($errors, 'Campaign length is not selected!');
        $error_number++;
    }
    if(count($keywords) == 0){
        array_push($errors, 'No keywords added!');
        $error_number++;
    }
    if(count($categories) == 0){
        array_push($errors, 'No categories added!');
        $error_number++;
    }
    if(count($locations) == 0){
        array_push($errors, 'No locations added!');
        $error_number++;
    }
    if($_POST['banners_quantity'] == 0){
        array_push($errors, 'Banner number is not selected!');
        $error_number++;
    }
    if($_POST['selected_position'] == ''){
        array_push($errors, 'No position is selected!');
        $error_number++;
    }
    if(!isset($_FILES['file'])){
        array_push($errors, '160px by 600px Banner is required!');
        $error_number++;
    }
    if(!isset($_FILES['file2'])){
        array_push($errors, '728px by 90px Banner is required!');
        $error_number++;
    }
    if(!isset($_FILES['file3'])){
        array_push($errors, '468px by 60px Banner is required!');
        $error_number++;
    }
    $response = null;
    if($error_number == 0){
        $campaign_length = trim($_POST['campaign_length']);
        $campaign_price = trim($_POST['campaign_price']);
        $keywords_length = trim($_POST['keywords_length']);
        $keywords_price = trim($_POST['keywords_price']);
        $categories_quantity = trim($_POST['categories_quantity']);
        $categories_price = trim($_POST['categories_price']);
        $locations_quantity = trim($_POST['locations_quantity']);
        $locations_price = trim($_POST['locations_price']);
        $banners_quantity = trim($_POST['banners_quantity']);
        $banners_price = trim($_POST['banners_price']);
        $keywords_array = $keywords;
        $categories_array = $categories;
        $locations_array = $locations;
        $ad_campaign_total = trim($_POST['ad_campaign_total']);
        $selected_position = trim($_POST['selected_position']);
        $selected_position_price = trim($_POST['selected_position_price']);


        $vendor = Vendor::where('login_token',$_POST['login_token'])->with('vendor_setting')->first();
        
        $bannerOneObject = new ImageResize($_FILES['file']['tmp_name']);
        $imageUploaderObjectOne = new ImageUploader($_FILES['file'], $bannerOneObject);
        $imageUploaderObjectOne->setRoot(SITE_ROOT);
        $imageUploaderObjectOne->setPath('images/vendors/banners/drafts/');
        $imageUploaderObjectOne->setLevel('../../');
        $imageUploaderObjectOne->setImageSize(160, 600);
        $banner_one_name = $imageUploaderObjectOne->imageUpload();
    
        $bannerTwoObject = new ImageResize($_FILES['file2']['tmp_name']);
        $imageUploaderObjectTwo = new ImageUploader($_FILES['file2'], $bannerTwoObject);
        $imageUploaderObjectTwo->setRoot(SITE_ROOT);
        $imageUploaderObjectTwo->setPath('images/vendors/banners/drafts/');
        $imageUploaderObjectTwo->setLevel('../../');
        $imageUploaderObjectOne->setImageSize(728, 90);
        $banner_two_name = $imageUploaderObjectTwo->imageUpload();
    
        $bannerThreeObject = new ImageResize($_FILES['file3']['tmp_name']);
        $imageUploaderObjectThree = new ImageUploader($_FILES['file3'], $bannerThreeObject);
        $imageUploaderObjectThree->setRoot(SITE_ROOT);
        $imageUploaderObjectThree->setPath('images/vendors/banners/drafts/');
        $imageUploaderObjectThree->setLevel('../../');
        $imageUploaderObjectOne->setImageSize(468, 60);
        $banner_three_name = $imageUploaderObjectThree->imageUpload();
        
        
        $draft = new Campaign_draft;
        $draft->campaign_length = $campaign_length;
        $draft->campaign_price = $campaign_price;
        $draft->keywords_length = $keywords_length;
        $draft->keywords_price = $keywords_price;
        $draft->categories_quantity = $categories_quantity;
        $draft->categories_price = $categories_price;
        $draft->locations_quantity = $locations_quantity;
        $draft->locations_price = $locations_price;
        $draft->banners_quantity = $banners_quantity;
        $draft->banners_price = $banners_price;
        $draft->ad_campaign_total = $ad_campaign_total;
        $draft->selected_position = $selected_position;
        $draft->selected_position_price = $selected_position_price;
        $draft->banner_1 = $banner_one_name;
        $draft->banner_2 = $banner_two_name;
        $draft->banner_3 = $banner_three_name;
        $draft->vendor_id = $vendor->id;


        if($draft->save()){
            foreach($keywords_array as $keyword){
                $vendor_draft_campaign_keyword = new Vendor_draft_campaign_keyword;
                $vendor_draft_campaign_keyword->vendor_id = $vendor->id;
                $vendor_draft_campaign_keyword->draft_id = $draft->id;
                $vendor_draft_campaign_keyword->keyword = $keyword;
                $vendor_draft_campaign_keyword->save();
            }
            foreach($categories_array as $category){
                $vendor_draft_campaign_category = new Vendor_draft_campaign_category;
                $vendor_draft_campaign_category->vendor_id = $vendor->id;
                $vendor_draft_campaign_category->draft_id = $draft->id;
                $vendor_draft_campaign_category->category = $category;
                $vendor_draft_campaign_category->save();
            }
            foreach($locations_array as $location){
                $vendor_draft_campaign_location = new Vendor_draft_campaign_location;
                $vendor_draft_campaign_location->vendor_id = $vendor->id;
                $vendor_draft_campaign_location->draft_id = $draft->id;
                $vendor_draft_campaign_location->location = $location;
                $vendor_draft_campaign_location->save();
            }
        }



        array_push($messages, 'Successfully done!');
        $data = Campaign_draft::where('id',$draft->id)->with('keywords', 'categories', 'locations')->first();
    }
    if($error_number > 0){
        $response = ['success' => false, 'errors' => $errors];
    }else{
        $response = ['success' => true, 'data' => $data, 'messages' => $messages];
    }

    echo json_encode($response);
    

}