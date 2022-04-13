<?php

namespace Shenole_project\models;
use Shenole_project\models\Client;
use Illuminate\Database\Eloquent\Model;

class Client_setting extends Model{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = "client_settings";
    public $timestamps = false;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'client_paypal_email', 
    ];
    public function client(){
        return $this->belongsTo(Client::class,'client_id','id');
    }
}