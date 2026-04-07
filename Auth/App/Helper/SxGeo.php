<?php
namespace Auth\App\Helper;

use Auth\Sys\Request;

class SxGeo
{
	public static function getCountryByIp($ip)
	{
        $path = Request::isDevelopment()
            ? __DIR__ .'/../../vendor/SxGeo/SxGeo.php'
            : __DIR__ .'/../../../../engine/modules/SxGeo.php';

        require_once($path);

        $sx_geo = Request::isDevelopment()
            ? new \SxGeo()
            : new \SxGeo(__DIR__ .'/../../vendor/SxGeo/SxGeo.dat');

        return $sx_geo->getCountry($ip);
	}

    public static function getCityByIp($ip)
    {
        $path = Request::isDevelopment()
            ? __DIR__ .'/../../vendor/SxGeo/SxGeo.php'
            : __DIR__ .'/../../../../engine/modules/SxGeo.php';

        require_once($path);

        $sx_geo = Request::isDevelopment()
            ? new \SxGeo()
            : new \SxGeo(__DIR__ .'/../../vendor/SxGeo/SxGeo.dat');

        return $sx_geo->getCity($ip);
    }
}