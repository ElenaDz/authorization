<?php
namespace Auth\APP\Helper;

use Auth\Sys\Request;

class Url
{
	public static function getUrlAbsolute($url)
	{
		return $_SERVER['HTTP_ORIGIN'].$url;
	}
}