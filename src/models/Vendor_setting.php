<?php

namespace Shenole_project\models;
use Shenole_project\models\Vendor;
use Illuminate\Database\Eloquent\Model;

class Vendor_setting extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = "vendor_settings";
    public $timestamps = false;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'vendor_paypal_email', 
    ];
    public function vendor(){
        return $this->belongsTo(Vendor::class,'vendor_id','id');
    }
}