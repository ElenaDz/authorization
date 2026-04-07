<?php

namespace Auth\App\Action\Admin;


use Auth\App\Model\Users;
use Auth\Sys\Views;

class ListUsers extends _BaseAdmin
{
    public function __invoke()
    {
        $users = Users::getAll();

        $content = Views::get(
            __DIR__ . '/../../View/UsersAdmin.php',
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