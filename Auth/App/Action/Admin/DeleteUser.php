<?php

namespace Auth\App\Action\Admin;

use Auth\App\Model\Users;

class DeleteUser extends _BaseAdmin
{
    public function __invoke()
    {

        $id = $_POST['id'];

        $user = Users::getById($id);

        $user->delete();

        // fixme как лучше удалить, напрямую или с проверками что такой юзер есть?

        Users::deleteById($id);
    }
}