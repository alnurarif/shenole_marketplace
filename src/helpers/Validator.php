<?php 
namespace Shenole_project\helpers;

class Validator{
	public static function isValidemail($str) {
		return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}
	public static function isStrongPassword($str, $min = 3, $max = 12){
		$returnVal = true;

		if ( strlen($str) < $min ) {
			$returnVal = false;
		}

		if ( strlen($str) > $max ) {
			$returnVal = false;
		}

		if ( !preg_match("#[0-9]+#", $str) ) {
			$returnVal = false;
		}

		if ( !preg_match("#[a-z]+#", $str) ) {
			$returnVal = false;
		}

		if ( !preg_match("#[A-Z]+#", $str) ) {
			$returnVal = false;
		}

		if ( !preg_match("/[\'^£$%&*()}{@#~?><>,|=_+!-]/", $str) ) {
			$returnVal = false;
		}

		return $returnVal;
	}
}