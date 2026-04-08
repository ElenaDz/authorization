<?php
namespace Auth\App\Action\Api;

use Auth\App\Model\Users;

class UserDelete extends _BaseApi
{
    public function __invoke()
    {
		// fixme магическая строка
        $id = $_POST['id'];

        $user = Users::getByIdOrFall($id);

        $user->delete();
    }
}