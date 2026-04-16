<?php

namespace Auth\App\Action\Admin;


use Auth\Sys\Views;

class Users extends _BaseAdmin
{
	// fixme "part_email" а "q" это имя используют для поискового запроса обычно, сейчас там мейл завтра закащик захочет чего то еще поэтому это просто поисковая строка
    const POST_NAME_PART_EMAIL = 'part_email';
    const POST_NAME_OFFSET = 'offset';
	// fixme в этом акшине не используется
    const POST_NAME_ID = 'id';
	// fixme в этом акшине не используется
    const POST_NAME_IS_ACTIVATED = 'is_activated';
    const LIMIT = 10;

    public function __invoke()
    {
		// fixme у нас нету offset у нас только user_id_first
	    // fixme здесь нужно использовать не POST а GET
        $offset = ! empty($_POST[self::POST_NAME_OFFSET]) ? $_POST[self::POST_NAME_OFFSET] : 0;

        $limit = ! empty($offset) ? self::LIMIT + $offset : self::LIMIT;

        $part_email = ! empty($_POST[self::POST_NAME_PART_EMAIL]) ? $_POST[self::POST_NAME_PART_EMAIL] : null;

        if ( ! empty($part_email)) {
			// fixme переименовать findByEmail
            $users = \Auth\App\Model\Users::getAllByPartEmail($part_email, $limit);
        } else {
			// fixme переименовать getNew сортировка по id так как id чем новее тем больше
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