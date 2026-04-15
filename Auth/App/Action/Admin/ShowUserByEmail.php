<?php

namespace Auth\App\Action\Admin;

use Auth\Sys\Views;

class ShowUserByEmail extends _BaseAdmin
{
    const POST_NAME_PART_EMAIL = 'part_email';
    public function __invoke()
    {
        $part_email = $_POST[self::POST_NAME_PART_EMAIL];

        $users = \Auth\App\Model\Users::getAllByPartEmail($part_email);

        $content = Views::get(
            __DIR__ . '/../../View/Admin/UserTr.php',
            [
                'users' => $users
            ]
        );

        echo $content;
    }
}