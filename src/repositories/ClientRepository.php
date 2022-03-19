<?php 
namespace Shenole_project\repositories;
use Shenole_project\interfaces\LoginChecker;
use Shenole_project\models\Client;
class ClientRepository implements LoginChecker{
	public function checkLoginByToken($token){
		return Client::where('login_token', '=', $token)
		->where('login_token','!=','')
		->where('login_token','!=',null)->exists();
	}
}