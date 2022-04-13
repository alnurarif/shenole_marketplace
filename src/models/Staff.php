<?php

namespace Shenole_project\models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = "staff";
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
    public function state(){
        return $this->belongsTo(State::class);
    }
    public function staff_setting(){
        return $this->hasOne(Staff_setting::class);
    }
}