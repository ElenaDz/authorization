<?php

namespace  Auth\App\Action;

use \Auth\SYS\Views;

abstract class _Base
{
    protected static function showLayout($title, $content)
    {
        echo Views::get(
            __DIR__.'/../View/Layout/Main.php',
            [
                'title' => $title,
                'content' =>  $content
            ]
        );
    }
}