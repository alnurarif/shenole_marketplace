<?php

namespace Shenole_project\models;
use Shenole_project\models\Category;
use Illuminate\Database\Eloquent\Model;

class Vendor_category extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = "vendor_categories";
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
    public $timestamps = false;
    public function category(){
        return $this->belongsTo(Category::class);
    }
}