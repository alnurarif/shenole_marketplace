<?php

namespace Shenole_project\models;
use Shenole_project\models\Majestic;
use Illuminate\Database\Eloquent\Model;

class Majestic_ad_setting extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = "majestic_ad_settings";
    public $timestamps = false;
    public function majestic(){
        return $this->belongsTo(Majestic::class,'majestic_id','id');
    }

    public function banner_quantity_pricing_settings(){
        return $this->hasMany(Ad_banner_quantity_pricing_setting::class,'ad_setting_id');
    }
    public function category_quantity_pricing_settings(){
        return $this->hasMany(Ad_category_quantity_pricing_setting::class,'ad_setting_id');
    }
    public function keyword_quantity_pricing_settings(){
        return $this->hasMany(Ad_keyword_quantity_pricing_setting::class,'ad_setting_id');
    }
    public function location_quantity_pricing_settings(){
        return $this->hasMany(Ad_location_quantity_pricing_setting::class,'ad_setting_id');
    }
}