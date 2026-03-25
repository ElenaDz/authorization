<?php
namespace Auth\Sys;

class Request
{
	public static function isDevelopment()
	{
		return file_exists(__DIR__.'/../_development');
	}

	public static function getIpRemote()
	{
        $remote_addr = null;

//        if (@$_SERVER["HTTP_CF_CONNECTING_IP"])
//        {
//            $remote_addr = @$_SERVER["HTTP_CF_CONNECTING_IP"];
//
//        } elseif ( ! empty(@$_SERVER['HTTP_X_FORWARDED_FOR']))
//        {
//            $remote_addr = (
//            ( strpos(@$_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false )
//                ? explode(',', @$_SERVER['HTTP_X_FORWARDED_FOR'])[0]
//                : @$_SERVER['HTTP_X_FORWARDED_FOR']
//            );
//        }
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $remote_addr = $_SERVER["HTTP_CF_CONNECTING_IP"];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $x_forwarded = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $remote_addr = (strpos($x_forwarded, ',') !== false)
                ? trim(explode(',', $x_forwarded)[0])
                : $x_forwarded;
        } else {
            $remote_addr = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }

        if ($remote_addr) {
            $_SERVER['REMOTE_ADDR'] = $remote_addr;
        }

        return $_SERVER['REMOTE_ADDR'];
	}


    public static function isAjax()
    {
        if (array_key_exists('HTTP_X_REQUESTED_WITH', @$_SERVER))
        {
            return @$_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        }

        return false;
    }
}