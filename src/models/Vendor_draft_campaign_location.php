<?php

namespace Shenole_project\models;

use Illuminate\Database\Eloquent\Model;
use Shenole_project\models\Campaign_draft;

class Vendor_draft_campaign_location extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = "vendor_draft_campaign_locations";
    public $timestamps = false;
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
    public function draft(){
        return $this->belongsTo(Campaign_draft::class,'draft_id','id');
    }
}