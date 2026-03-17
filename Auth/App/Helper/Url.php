<?php
namespace Auth\APP\Helper;

use Auth\Sys\Request;

class Url
{
	public static function getUrlAbsolute($url)
	{
		return $_SERVER['HTTP_ORIGIN'].$url;
	}

	// fixme где url и где ip совершенно не связанные вещи используй функцию что я дал ниже
	/** @see Request::getIpRemote() */
    public static function getIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
             $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}