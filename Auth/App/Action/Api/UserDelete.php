<?php
namespace Auth\App\Action\Api;

use Auth\App\Model\Users;
use Exception;


class UserDelete extends _BaseApi
{
    const POST_NAME_ID = 'id';

    public function __invoke()
    {
        $id = $_POST[self::POST_NAME_ID];

        $user = Users::getByIdOrFall($id);

        $user->delete();
    }
}