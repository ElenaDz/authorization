<?php
namespace Auth\Sys;

class Autoload
{
	public static function register()
	{
	    spl_autoload_register(
	        function ($class_name)
	        {
				if (substr($class_name, 0, 5) !== 'Auth\\') return;

	            $file_path = __DIR_AUTH_ROOT__.'\\'.str_replace('\\', '/', $class_name).'.php';

		        $file_realpath = realpath($file_path);
				if (substr($file_realpath, 0, strlen(__DIR_AUTH_ROOT__)) !== __DIR_AUTH_ROOT__) return;

				if ( ! file_exists($file_path))
				{
					if (Request::isDevelopment())
					{
						throw new \Exception(
							sprintf(
								'File not found "%s"',
								$file_path
							)
						);
					}

					return;
				}

	            include $file_path;
	        }
	    );
	}
}