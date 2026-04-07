<?php

namespace Auth\App\Action\Admin;

use Auth\App\Model\Users;

class ActivationUser extends _BaseAdmin
{
    public function __invoke()
    {
        $id = $_POST['id'];

        $user = Users::getById($id);

        if ($user->getActivationCode()) {
            $user->resetActivationCode();
        } else {
            $user->genActivationCode();
            // fixme Уточнить у заказчика, нужно ли деактивировать , и нужно ли посылать письмо с новым кодом активации
        }

        $user->save();
    }
}