<?php
namespace Auth\App\Action\Api;

use Auth\App\Model\Users;

class UserDelete extends _BaseApi
{
    public function __invoke()
    {
        $id = $_POST['id'];

        $user = Users::getById($id);

		// fixme проверить что пользователь есть

        $user->delete();
    }
}