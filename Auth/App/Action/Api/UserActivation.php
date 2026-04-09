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

		// fixme магическая строка ok
	    // fixme active и activation это не одно и тоже ok
        $is_activated = ! $_POST[self::POST_NAME_ACTIVATION];

        if ($is_activated) return;

		// fixme лучше спрятать логику работы с кодом активации внутри класса а наружу выставить то что нам может ok
	    //  понадобиться например активация пользователя
        $user->activation();

        $user->save();
    }
}