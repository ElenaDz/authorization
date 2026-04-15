<?php

namespace Auth\App\Action\Admin;


use Auth\Sys\Views;

class Users extends _BaseAdmin
{
    public function __invoke()
    {
		// fixme сделай 10 для тестирования
        $limit = 200;

        $users = \Auth\App\Model\Users::getWithOffset($limit, 0);

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