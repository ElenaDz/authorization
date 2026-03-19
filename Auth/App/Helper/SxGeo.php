<?php
namespace Auth\APP\Helper;

use Auth\Sys\Request;

class SxGeo
{
	public static function getCountryByIp($ip)
	{
        $path = Request::isDevelopment() ? __DIR__ .'/../../vendor/SxGeo/SxGeo.php' : __DIR__.'/узнать и заполнить';

        require_once($path);

        $sx_geo = new \SxGeo();

        return $sx_geo->getCountry($ip);
	}
}