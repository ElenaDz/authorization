<?php

namespace Auth\App\Action\Admin;


use Auth\Sys\Routing;
use Auth\Sys\Views;
use PHPMailer\PHPMailer\Exception;

class Users extends _BaseAdmin
{
	// fixme "part_email" а "q" это имя используют для поискового запроса обычно, сейчас там мейл завтра закащик захочет чего то еще поэтому это просто поисковая строка ок
    const POST_NAME_Q = 'q';
    const GET_NAME_Q = 'q';
    const GET_NAME_LIMIT = 'limit';
    const GET_NAME_USER_ID_FIRST = 'user_id_first';
    const LIMIT = 3;

    public function __invoke($limit = 10, $q = '', $user_id_first = 10000)
    {
		// fixme у нас нету offset у нас только user_id_first ок
	    // fixme здесь нужно использовать не POST а GET

        $q = ! empty($_POST[self::POST_NAME_Q]) ? $_POST[self::POST_NAME_Q] : null;

        $has_not_activated_users = ! empty(\Auth\App\Model\Users::getNotActivated());

        if ( ! empty($q)) {
			// fixme переименовать findByEmail ok
            $users = \Auth\App\Model\Users::findByEmail($q, self::LIMIT + 1);

        } else {
            // fixme переименовать getNew сортировка по id так как id чем новее тем больше ok
            $users = \Auth\App\Model\Users::getNew(self::LIMIT + 1);

        }

        if ( ! empty($_GET[self::GET_NAME_USER_ID_FIRST])) {

            $user_id_first = ! empty($_GET[self::GET_NAME_USER_ID_FIRST]) ? $_GET[self::GET_NAME_USER_ID_FIRST] : null;

            $q = ! empty($_GET[self::GET_NAME_Q]) ? $_GET[self::GET_NAME_Q] : null;


            $limit = ! empty($_GET[self::GET_NAME_LIMIT]) ? $_GET[self::GET_NAME_LIMIT] : self::LIMIT;

            if ( ! empty($q)) {
                $users = \Auth\App\Model\Users::findByEmail($q, self::LIMIT + 1, $q);

            } else {
                $users = \Auth\App\Model\Users::getNew($limit + 1, $user_id_first);
            }
        }

        if (empty($users)) {

            throw new Exception('Пользователей не найдены', 404);
        }

        if ( ! empty($users)) {

            $last_user = end($users);

            $user_id_first = $last_user->getId();

            array_pop($users);
        }

        $content = Views::get(
            __DIR__ . '/../../View/Admin/Users.php',
            [
                'users' => $users,
                'limit' => self::LIMIT,
                'q' => $q,
                'has_not_activated_users' => $has_not_activated_users,
                'user_id_first' => $user_id_first
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