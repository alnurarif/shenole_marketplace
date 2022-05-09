<?php

namespace Shenole_project\models;

use Illuminate\Database\Eloquent\Model;

class Majestic extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = "majestic";
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
    public function ad_setting(){
        return $this->hasOne(Majestic_ad_setting::class);
    }
}