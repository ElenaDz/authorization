<?php

namespace Auth\App\Action\Admin;


use Auth\Sys\Views;

class Users extends _BaseAdmin
{
    public function __invoke()
    {
        $users = \Auth\App\Model\Users::getAll();

        $content = Views::get(
            __DIR__ . '/../../View/Admin/Users.php',
            [
                'users' => $users
            ]
        );

        self::showLayout(
            'Таблица пользователей',
            $content
        );
    }
}