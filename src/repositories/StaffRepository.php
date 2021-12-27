<?php 
namespace Shenole_project\repositories;
use Shenole_project\interfaces\LoginChecker;
use Shenole_project\models\Staff;
class StaffRepository implements LoginChecker{
	public function checkLoginByToken($token){
		$staff = Staff::where('login_token','=', $token)->first();
		return Staff::where('login_token', '=', $token)->exists();
	}
}