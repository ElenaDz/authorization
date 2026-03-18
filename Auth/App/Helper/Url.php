<?php
namespace Auth\APP\Helper;

use Auth\Sys\Request;

class Url
{
	public static function getUrlAbsolute($url)
	{
		return $_SERVER['HTTP_ORIGIN'].$url;
	}

	// fixme где url и где ip совершенно не связанные вещи используй функцию что я дал ниже ok
	/** @see Request::getIpRemote() */
}