<?php

namespace Shenole_project\models;
use Shenole_project\models\State;
use Illuminate\Database\Eloquent\Model;

class Vendor_location extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = "vendor_locations";
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
        return $this->belongsTo(State::class,'location_state','id');
    }
}