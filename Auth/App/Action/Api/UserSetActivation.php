<?php

namespace Auth\App\Action\Api;

use Auth\App\Model\Users;

class UserSetActivation extends _BaseApi
{
    public function __invoke()
    {
        $id = $_POST['id'];

        $user = Users::getById($id);

		// todo проверить найден для пользователь

		// fixme нужно присылать с сервера состояние флажка, просто проверять состояние из БД не правильно
        if ($user->getActivationCode()) {
            $user->resetActivationCode();

        } else {
            $user->genActivationCode();
            // todo Уточнить у заказчика, нужно ли деактивировать, и нужно ли посылать письмо с новым кодом активации
        }

        $user->save();
    }
}