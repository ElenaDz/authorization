<?php
namespace Auth\App\Service;

use Auth\App\Entity\User;

class Auth
{
	private static $user;


	public static function isAuthorized()
	{
		return false;
	}


	public static function getUser()
	{
		if ( ! self::isAuthorized()) return null;

		return new User();
	}
}