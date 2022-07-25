<?php

namespace Shenole_project\models;

use Illuminate\Database\Eloquent\Model;
use Shenole_project\models\Vendor_draft_campaign_keyword;
use Shenole_project\models\Vendor_draft_campaign_category;
use Shenole_project\models\Vendor_draft_campaign_location;

class Campaign_draft extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = "campaign_drafts";
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
    public function keywords(){
        return $this->hasMany(Vendor_draft_campaign_keyword::class,'draft_id');
    }
    public function categories(){
        return $this->hasMany(Vendor_draft_campaign_category::class,'draft_id');
    }
    public function locations(){
        return $this->hasMany(Vendor_draft_campaign_location::class,'draft_id');
    }
}