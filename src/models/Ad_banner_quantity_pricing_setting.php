<?php

namespace Shenole_project\models;
use Shenole_project\models\Majestic_ad_setting;
use Illuminate\Database\Eloquent\Model;

class Ad_banner_quantity_pricing_setting extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = "ad_banner_quantity_pricing_settings";
    public $timestamps = false;
    public function ad_setting(){
        return $this->belongsTo(Majestic_ad_setting::class,'ad_setting_id','id');
    }
}