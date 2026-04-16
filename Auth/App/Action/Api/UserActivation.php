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

		// здесь нужно не просто проверить стоит флаг или нет нужно так же проверить что с активацией пользователя в БД, и если что-то не совпадает кидать ошибку
        if ( ! $is_activation && ! $user->isActivated()) {
			// fixme просто выход это скрытие ошибки, ошибки нужно не скрывать, а обязательно показывать, например кидать исключение ок
            throw new \Exception('Не получилось активировать пользователя');
        }

        $user->activation();

        $user->save();
    }
}