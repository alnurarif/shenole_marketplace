<?php

namespace Shenole_project\models;
use Illuminate\Database\Eloquent\Model;

class Vendor_membership_level extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = "vendor_membership_levels";
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    // protected $fillable = [
    //     'title', 
    //     'body', 
    //     'created_by'
    // ];

    public $timestamps = false;


    public function validate(){
        $errors = [
            'errors_number' => 0,
            'name' => '',
            'price' => '',
            'billing_cycle' => '',
            'number_of_service_categories' => '',
            'enable_ads' => '',
            'enable_wishlist' => '',
            'enable_analytics' => '',
            'photo_gallery_limit' => '',
            'video_gallery_limit' => '',
            'audio_gallery_limit' => '',
            'keyword_char_limit' => '',
            'number_of_locations' => '',
            'travel_limit' => '',
            'number_of_services' => ''
        ];

        if($this->name == ""){
            $errors['name'] = 'Required!';
            $errors['errors_number']++;
        }
        if($this->price == ""){
            $errors['price'] = "Required!";
            $errors['errors_number']++;
        }
        if($this->billing_cycle == ""){
            $errors['billing_cycle'] = "Required!";
            $errors['errors_number']++;
        }
        if($this->number_of_service_categories == ""){
            $errors['number_of_service_categories'] = "Required!";
            $errors['errors_number']++;
        }
        if($this->photo_gallery_limit == ""){
            $errors['photo_gallery_limit'] = "Required!";
            $errors['errors_number']++;
        }
        if($this->video_gallery_limit == ""){
            $errors['video_gallery_limit'] = "Required!";
            $errors['errors_number']++;
        }
        if($this->audio_gallery_limit == ""){
            $errors['audio_gallery_limit'] = "Required!";
            $errors['errors_number']++;
        }
        if($this->keyword_char_limit == ""){
            $errors['keyword_char_limit'] = "Required!";
            $errors['errors_number']++;
        }
        if($this->number_of_locations == ""){
            $errors['number_of_locations'] = "Required!";
            $errors['errors_number']++;
        }
        if($this->travel_limit == ""){
            $errors['travel_limit'] = "Required!";
            $errors['errors_number']++;
        }
        if($this->number_of_services == ""){
            $errors['number_of_services'] = "Required!";
            $errors['errors_number']++;
        }    
        return $errors;
    }
}