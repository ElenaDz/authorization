<?php

namespace Auth\App\Action\Api;

use Auth\App\Model\Users;

class UserSetActivation extends _BaseApi
{
    public function __invoke()
    {
        $id = $_POST['id'];

        $user = Users::getById($id);

        $is_active = $_POST['active'];

        if (empty($user))
        {
            throw new \Exception(
                sprintf(
                'Пользователь с id = "%s" не найден в БД',
                    $id
            ));
        }
		// todo проверить найден для пользователь ок


		// fixme нужно присылать с сервера состояние флажка, просто проверять состояние из БД не правильно ок
        if ($is_active) return;

        $user->resetActivationCode();

        $user->save();
    }
}