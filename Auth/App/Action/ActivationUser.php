<?php
namespace Auth\App\Action;

use Auth\App\Model\Users;
use Auth\Sys\Routing;

class ActivationUser extends _Base
{
    public function __invoke($login = null, $code = null)
    {
        $errors = [];

		// todo проверка что переданы все необходимые данные, если нет кидаем исключение

        $user = Users::getByLoginOrEmail($login);

        var_dump($user->getEncodeActivationCode());
        var_dump($code);

		// fixme лучше завести метод validActivationCode по аналогии c validPass чтобы логика проверки кода осталась внутри класса пользователя
        if ($user->getEncodeActivationCode() == $code)
        {
			// fixme в данном случае лучше завести метод resetActivationCode без параметров
            $user->setActivationCode(null);

            $user->save();

        }

    }

    public static function getUrl(array $params = []): string
    {
        return Routing::getUrl(static::class, $params);
    }
}