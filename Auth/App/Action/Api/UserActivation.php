<?php

namespace Auth\App\Action\Api;

use Auth\App\Model\Users;

class UserActivation extends _BaseApi
{
    const POST_NAME_ID = 'id';
    const POST_NAME_ACTIVATION = 'activation';

    public function __invoke()
    {
        $id = $_POST[self::POST_NAME_ID];

        $user = Users::getByIdOrFall($id);

        $is_activation = $_POST[self::POST_NAME_ACTIVATION];

        if ( ! $is_activation ) {
            throw new \Exception('Нельзя отменить активацию');
        }

        if ( $user->isActivated()) {
            throw new \Exception('Пользователь уже активирован');
        }

        $user->activation();

        $user->save();
    }
}