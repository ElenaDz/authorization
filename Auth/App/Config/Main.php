<?php
namespace Auth\App\Config;

class Main
{
	public static function getDomain(): string
    {
		return 'drivemusic.me';
	}

	public static function getTitle(): string
    {
		return 'DriveMusic';
	}

    public static function isDev(): bool
    {
        return (bool)(__DIR__ . '/../../../dev.txt');
    }

    public static function pathSxGeo(): string
    {
        if (Main::isDev()) {
            $path = __DIR__ .'/../../vendor/SxGeo/SxGeo.php';
        } else {
            $path = __DIR__ .'1';
        }

        return $path;
    }
}