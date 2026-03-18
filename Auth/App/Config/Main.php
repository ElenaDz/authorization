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

}