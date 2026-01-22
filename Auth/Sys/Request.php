<?php
namespace Auth\Sys;

class Request
{
	public static function isDevelopment()
	{
		return file_exists(__DIR__.'/../_development');
	}
}