<?php

namespace Auth\App\Action\Admin;


use Auth\Sys\Routing;
use Auth\Sys\Views;
use Exception;


class Users extends _BaseAdmin
{
    const POST_NAME_Q = 'q';
    const GET_NAME_USER_ID_FIRST = 'user_id_first';
    const GET_NAME_Q = 'q';
    const LIMIT = 10;

	// fixme $limit не используется ok
    public function __invoke($q = '', $user_id_first = null)
    {
		// fixme если добавить задержку несколько секунд и покликать на кнопку "Загрузка ..." будет отправлено несколько запросов ok
        // sleep(2);

        $has_not_activated_users = ! empty(\Auth\App\Model\Users::getNotActivated());

        $q = $_POST[self::POST_NAME_Q] ?? $q;

        $users = \Auth\App\Model\Users::getNew(self::LIMIT + 1, $user_id_first, $q);

        // todo если пользователей нет в БД, ещё никто не зарегистрирован, как вывести ПУСТО в таблице, если выпадает в ошибку?
        if (empty($users)) {
			// todo все исключения которые ты добавляешь должны быть протестированы, протестируй это
            throw new Exception('Пользователи не найдены');
        }

		// fixme наличие user_id_first должно говорить о том что есть еще пользователи, не нужна $has_users_more ok
        $user_id_first = $users[self::LIMIT] ? $users[self::LIMIT]->getID() : null;

        // todo проверка что $user_id_first есть в БД, если нет то 404 страница

        if ($user_id_first) {
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