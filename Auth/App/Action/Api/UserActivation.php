<?php

namespace Auth\App\Action\Api;

use Auth\App\Model\Users;

class UserActivation extends _BaseApi
{
    const POST_NAME_ID = 'id';
    const POST_NAME_ACTIVATION = 'activation';

    public function __invoke()
    {
		// fixme флажок снимается из за ошибку, но флажок остается заблокирован
		throw new \Exception("Что то пошло не так");

        $id = $_POST[self::POST_NAME_ID];

        $user = Users::getByIdOrFall($id);

        $is_activation = $_POST[self::POST_NAME_ACTIVATION];

		// fixme проверка и сообщения об ошибки совершенно не согласуются, разбить на две проверки и две ошибки
        if ( ! $is_activation && ! $user->isActivated()) {
            throw new \Exception('Пользователь уже активирован');
        }

        $user->activation();

        $user->save();
    }
}