<?php
namespace Auth\App\Action\Api;

use Auth\App\Model\Users;

class UserDelete extends _BaseApi
{
    public function __invoke()
    {
        $id = $_POST['id'];

        $user = Users::getById($id);

        if (empty($user))
        {
            throw new \Exception(
                sprintf(
                    'Пользователь с id = "%s" не найден в БД',
                    $id
                ));
        }
		// fixme проверить что пользователь есть ок

        $user->delete();
    }
}