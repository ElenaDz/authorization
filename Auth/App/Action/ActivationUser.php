<?php

namespace Auth\App\Action;

use Auth\Sys\Routing;

class ActivationUser extends _Base
{
    public function __invoke($login = null, $code = null)
    {
        $errors = [];
        var_dump($_GET);
//        авторизация и редирект на главную
    }

    public static function getUrl(array $params = []): string
    {
        return Routing::getUrl(static::class, $params);
    }
}