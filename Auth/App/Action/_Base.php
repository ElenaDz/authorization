<?php
namespace  Auth\App\Action;

use Auth\App\Model\Users;
use Auth\Sys\Routing;
use \Auth\SYS\Views;

abstract class _Base
{
	protected static function showLayout($title, $content)
    {
        echo Views::get(
            __DIR__ . '/../View/Layout/Main.php',
            [
                'title' => $title,
                'content' =>  $content
            ]
        );
    }

	public static function getUrl(array $params = []): string
	{
		return Routing::getUrl(static::class, $params);
	}
}