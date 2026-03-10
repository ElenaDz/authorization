<?php
namespace Auth\Sys;

class Response
{
	public static function redirect($url, $permanent = false)
	{
		header('Location: ' . $url, true, $permanent ? 301 : 302);
		exit();
	}

    public static function isAjax()
    {
        return @$_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
}