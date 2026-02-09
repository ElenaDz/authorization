<?php
namespace Auth\APP\Helper;

class Url
{
	public static function getUrlAbsolute($url)
	{
		return $_SERVER['HTTP_ORIGIN'].$url;
	}
}