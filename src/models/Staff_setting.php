<?php

namespace Shenole_project\models;
use Shenole_project\models\Staff;
use Illuminate\Database\Eloquent\Model;

class Staff_setting extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = "staff_settings";
    public $timestamps = false;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'staff_paypal_email', 
    ];
    public function staff(){
        return $this->belongsTo(Staff::class,'staff_id','id');
    }
}