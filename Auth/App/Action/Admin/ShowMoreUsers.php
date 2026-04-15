<?php

namespace Auth\App\Action\Admin;

use Auth\Sys\Views;

// fixme удаляем
class ShowMoreUsers extends _BaseAdmin
{
    const POST_NAME_OFFSET = 'offset';
    const POST_NAME_LIMIT = 'limit';
    public function __invoke()
    {
        $offset = $_POST[self::POST_NAME_OFFSET];

        $limit = $_POST[self::POST_NAME_LIMIT];

        $users_more = \Auth\App\Model\Users::getWithOffset($limit, $offset);

        $content = Views::get(
            __DIR__ . '/../../View/Admin/UserTr.php',
            [
                'users' => $users_more
            ]
        );

        echo $content;
    }
}