<?php
namespace Auth\APP\Helper;

use Auth\Sys\Request;

class SxGeo
{
	public static function getCountryByIp($ip)
	{
        // fixme переписать на тернарный оператор OK
        $path = Request::isDevelopment() ? $path = __DIR__ .'/../../vendor/SxGeo/SxGeo.php' : __DIR__ .'1';

        require_once($path);

        $sx_geo = new \SxGeo();

        return $sx_geo->getCountry($ip);
	}
}