<?php

namespace Shenole_project\models;
use Shenole_project\models\Vendor_category;
use Shenole_project\models\Vendor_location;
use Shenole_project\models\Vendor_setting;
use Shenole_project\models\Vendor_membership_level;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    // protected $table = "customers";
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
    public function categories(){
        return $this->hasMany(Vendor_category::class);
    }
    public function locations(){
        return $this->hasMany(Vendor_location::class);
    }
    public function vendor_membership_level(){
        return $this->belongsTo(Vendor_membership_level::class,'membership_level','id');
    }
    public function vendor_setting(){
        return $this->hasOne(Vendor_setting::class);
    }
}