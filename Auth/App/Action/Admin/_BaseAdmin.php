<?php
namespace  Auth\App\Action\Admin;

use Auth\App\Action\_Base;
use \Auth\SYS\Views;

abstract class _BaseAdmin extends _Base
{
	protected static function showLayout($title, $content)
    {
        echo Views::get(
            __DIR__ . '/../../View/Layout/Admin.php',
            [
                'title' => $title,
                'content' =>  $content
            ]
        );
    }
}