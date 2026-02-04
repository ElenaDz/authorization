<?php

namespace Auth\App\Action;

use Auth\App\Model\Users;
use Auth\Sys\Routing;

class ActivationUser extends _Base
{
    public function __invoke($login = null, $code = null)
    {
        $errors = [];

//        авторизация и редирект на главную
        if ($login)
        {
            $user = Users::getByLoginOrEmail($login);

            var_dump($user->getEncodeActivationCode());
            var_dump($code);
            if ($user->getEncodeActivationCode() == $code)
            {
                setcookie();

                $user->setActivationCode(null);

                $user->save();

            }
        }
    }

    public static function getUrl(array $params = []): string
    {
        return Routing::getUrl(static::class, $params);
    }
}