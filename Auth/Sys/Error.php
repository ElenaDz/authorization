<?php

namespace Sys;

class Error
{
    public static function showError($msg, $code = 500)
    {
        http_response_code($code);

        echo htmlspecialchars($msg);

        exit;
    }

	public static function showError404($url)
	{
		self::showError("Error 404 {$url} ", 404);
	}
}