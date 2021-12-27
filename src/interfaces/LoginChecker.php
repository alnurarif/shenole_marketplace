<?php 
namespace Shenole_project\interfaces;

interface LoginChecker{
	public function checkLoginByToken($token);
}