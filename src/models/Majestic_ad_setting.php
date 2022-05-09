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
}