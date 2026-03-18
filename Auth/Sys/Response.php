<?php
namespace Auth\Sys;

class Response
{
	public static function redirect($url, $permanent = false)
	{
		header('Location: ' . $url, true, $permanent ? 301 : 302);
		exit();
	}


	// fixme переместить в Request думаю понятно почему
    public static function isAjax()
    {
        if (array_key_exists('HTTP_X_REQUESTED_WITH', @$_SERVER))
        {
            return @$_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        }

        return false;
    }
}