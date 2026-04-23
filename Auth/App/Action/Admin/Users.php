<?php

namespace Auth\App\Action\Admin;


use Auth\Sys\Routing;
use Auth\Sys\Views;
use PHPMailer\PHPMailer\Exception;

class Users extends _BaseAdmin
{
    const POST_NAME_Q = 'q';
    const GET_NAME_USER_ID_FIRST = 'user_id_first';
    const LIMIT = 10;


	// fixme $limit не используется
    public function __invoke($limit = 10, $q = '', $user_id_first = null)
    {
		// fixme если добавить задержку несколько секунд и покликать на кнопку "Загрузка ..." будет отправлено несколько запросов
		//sleep(2);

        $has_not_activated_users = ! empty(\Auth\App\Model\Users::getNotActivated());

        $q = $_POST[self::POST_NAME_Q] ?? $q;

		// todo проверка что $user_id_first есть в БД, если нет то 404 страница

        $users = \Auth\App\Model\Users::getNew2($q,self::LIMIT + 1, $user_id_first);

        if (empty($users)) {
			// todo все исключения которые ты добавляешь должны быть протестированы, протестируй это
            throw new Exception('Пользователи не найдены', 404);
        }

        $last_user = end($users);

		// fixme наличие user_id_first должно говорить о том что есть еще пользователи, не нужна $has_users_more
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