<?php

namespace Shenole_project\models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = "master_vendor_categories";
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
    public function vendors(){
        return $this->belongsTo(Vendor::class);
    }
}