<?php 
namespace Shenole_project\helpers;

use Shenole_project\interfaces\LoginChecker;

class UserHelper{
	public static function isUserLoggedIn($session, $user_type, LoginChecker $loginChecker){
		$logged_in_as_array = isset($session['logged_in_as']) ? $session['logged_in_as'] : [];
		foreach($logged_in_as_array as $logged_in_as){
			if($logged_in_as['logged_in_user_type'] == $user_type && $loginChecker->checkLoginByToken($logged_in_as['looged_in_token'])){
				
				return true;
			}
		}
		return false;
	}
	public static function getLoginTokenByUserType($session, $user_type){
		$login_token = '';
		$logged_in_as_array = isset($session['logged_in_as']) ? $session['logged_in_as'] : [];
		foreach($logged_in_as_array as $logged_in_as){
			if($logged_in_as['logged_in_user_type'] == $user_type){
				$login_token = $logged_in_as['looged_in_token'];
			}
		}
		return $login_token;
	}

}