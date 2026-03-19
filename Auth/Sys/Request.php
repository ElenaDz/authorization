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

		if (@$_SERVER['HTTP_X_FORWARDED_FOR'])
		{
			$remote_addr = (
				( strpos(@$_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false )
				? explode(',', @$_SERVER['HTTP_X_FORWARDED_FOR'])[0]
				: @$_SERVER['HTTP_X_FORWARDED_FOR']
			);

		} elseif (@$_SERVER["HTTP_CF_CONNECTING_IP"])
		{
			$remote_addr = @$_SERVER["HTTP_CF_CONNECTING_IP"];
		}

		if ($remote_addr) {
			@$_SERVER['REMOTE_ADDR'] = $remote_addr;
		}

		return @$_SERVER['REMOTE_ADDR'];
	}

    // fixme переместить в Request думаю понятно почему ok
    public static function isAjax()
    {
        if (array_key_exists('HTTP_X_REQUESTED_WITH', @$_SERVER))
        {
            return @$_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        }

        return false;
    }
}