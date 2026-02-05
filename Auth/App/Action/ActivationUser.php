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
        if (empty($login) || empty($code))
        {
            throw new \Exception(
                sprintf(
                    'Нет Логина("%s") или кода активации("%s")',
                    $login,
                    $code
                )
            );
        }

        $user = Users::getByLoginOrEmail($login);

		// fixme лучше завести метод validActivationCode по аналогии c validPass чтобы логика проверки кода осталась внутри класса пользователя ok
        if ($user->validActivationCode($code))
        {
			// fixme в данном случае лучше завести метод resetActivationCode без параметров ok
            $user->resetActivationCode();

            $user->save();
        }
    }

    public static function getUrl(array $params = []): string
    {
        return Routing::getUrl(static::class, $params);
    }
}