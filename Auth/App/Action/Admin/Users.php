<?php

namespace Auth\App\Action\Admin;


use Auth\Sys\Routing;
use Auth\Sys\Views;
use PHPMailer\PHPMailer\Exception;

class Users extends _BaseAdmin
{
    const POST_NAME_Q = 'q';
    const GET_NAME_USER_ID_FIRST = 'user_id_first';
	// fixme не вижу последнего пользователя на последней странице (тест пользователей - 20, limit - 10) ok
	// fixme вижу кнопку "показать еще" на последней странице (тест пользователей - 20, limit - 10)
    const LIMIT = 3;

	// fixme убрать у $user_id_first 10000 должно быть null ок
    public function __invoke($limit = 10, $q = '', $user_id_first = null)
    {
        $has_not_activated_users = ! empty(\Auth\App\Model\Users::getNotActivated());

        $q = $_POST[self::POST_NAME_Q] ?? $q;

        $users = \Auth\App\Model\Users::getNew2($q,self::LIMIT + 1, $user_id_first);

        if (empty($users)) {
            throw new Exception('Пользователей не найдены', 404);
        }

		// fixme лишняя проверка, ты выше кидаешь исключение если пусто ок

        $last_user = end($users);

        $user_id_first = $last_user->getId();

        $has_users_more = count($users) > self::LIMIT;

        if ($has_users_more) {
            array_pop($users);
        }

        $users_count = \Auth\App\Model\Users::getCount();

        $content = Views::get(
            __DIR__ . '/../../View/Admin/Users.php',
            [
                'users' => $users,
                'limit' => self::LIMIT,
                'q' => $q,
                'has_not_activated_users' => $has_not_activated_users,
                'user_id_first' => $user_id_first,
                'has_users_more' => $has_users_more,
                'users_count' => $users_count
            ]
        );

        self::showLayout(
            'Таблица пользователей',
            $content
        );
    }
    public static function getUrl(array $params = []): string
    {
        return Routing::getUrl(static::class, $params);
    }
}