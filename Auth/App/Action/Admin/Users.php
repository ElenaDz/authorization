<?php

namespace Auth\App\Action\Admin;


use Auth\Sys\Views;

class Users extends _BaseAdmin
{
    const POST_NAME_PART_EMAIL = 'part_email';
    const POST_NAME_OFFSET = 'offset';
    const POST_NAME_ID = 'id';
    const POST_NAME_IS_ACTIVATED = 'is_activated';
    const LIMIT = 2;
    public function __invoke()
    {
		// fixme сделай 10 для тестирования ok
        $offset = ! empty($_POST[self::POST_NAME_OFFSET]) ? $_POST[self::POST_NAME_OFFSET] : 0;

        $limit = ! empty($offset) ? self::LIMIT + $offset : self::LIMIT;

        $part_email = ! empty($_POST[self::POST_NAME_PART_EMAIL]) ? $_POST[self::POST_NAME_PART_EMAIL] : null;

        if ( ! empty($part_email)) {
            $users = \Auth\App\Model\Users::getAllByPartEmail($part_email, $limit);
        } else {
            $users = \Auth\App\Model\Users::getAllWithLimit($limit);
        }

        $content = Views::get(
            __DIR__ . '/../../View/Admin/Users.php',
            [
                'users' => $users,
                'limit' => self::LIMIT,
                'part_email' => $part_email
            ]
        );

        self::showLayout(
            'Таблица пользователей',
            $content
        );
    }
}