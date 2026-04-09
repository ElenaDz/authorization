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

		// fixme что-то тут не так слева активирован а справа НЕ активирован
	    //   проблема в том что имена не совпадают а всем было бы проще если бы они полностью совпадали
        $is_activated = ! $_POST[self::POST_NAME_ACTIVATION];

        if ($is_activated) return;

        $user->activation();

        $user->save();
    }
}