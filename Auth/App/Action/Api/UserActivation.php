<?php

namespace Auth\App\Action\Api;

use Auth\App\Model\Users;

class UserActivation extends _BaseApi
{
    public function __invoke()
    {
        $id = $_POST['id'];

        $user = Users::getByIdOrFall($id);

		// fixme магическая строка
	    // fixme active и activation это не одно и тоже
        $is_active = $_POST['active'];

        if ($is_active) return;

		// fixme лучше спрятать логику работы с кодом активации внутри класса а наружу выставить то что нам может
	    //  понадобиться например активация пользователя
        $user->resetActivationCode();

        $user->save();
    }
}