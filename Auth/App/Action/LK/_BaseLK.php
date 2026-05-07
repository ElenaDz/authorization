<?php

namespace Auth\App\Action\LK;

use Auth\App\Action\_Base;
use Auth\Sys\Routing;
use Auth\Sys\Views;

class _BaseLK extends _Base
{
    protected static function showLayout($title, $content)
    {
        echo Views::get(
            __DIR__ . '/../../View/Layout/MainLK.php',
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