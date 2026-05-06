<?php

namespace Auth\App\Action\Admin;

use Auth\Sys\Request;
use Auth\Sys\Response;
use Auth\Sys\Routing;
use Auth\Sys\Views;
use Exception;

class Users extends _BaseAdmin
{
    const POST_NAME_Q = 'q';
    const GET_NAME_USER_ID_FIRST = 'user_id_first';
    const GET_NAME_Q = 'q';
    const LIMIT = 10;


    public function __invoke($q = '', $user_id_first = null)
    {
        $q = $_POST[self::POST_NAME_Q] ?? $q;

        $users = \Auth\App\Model\Users::getNew(self::LIMIT + 1, $user_id_first, $q);

        if (empty($users) && ! Request::isAjax()) {
            Response::setStatusCode(404);
        }

        $user_id_first = $users[self::LIMIT] ? $users[self::LIMIT]->getID() : null;
        if ($user_id_first) {
            array_pop($users);
        }

	    $has_not_activated_users = ! empty(\Auth\App\Model\Users::getNotActivated());

        $users_count = \Auth\App\Model\Users::getCount();

        $content = Views::get(
            __DIR__ . '/../../View/Admin/Users.php',
            [
                'users' => $users,
                'limit' => self::LIMIT,
                'q' => $q,
	            'user_id_first' => $user_id_first,
                'has_not_activated_users' => $has_not_activated_users,
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