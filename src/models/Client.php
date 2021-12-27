<?php

namespace Shenole_project\models;

use Illuminate\Database\Eloquent\Model;
use Shenole_project\models\State;
class Client extends Model{
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
    public function state(){
        return $this->belongsTo(State::class);
    }
}