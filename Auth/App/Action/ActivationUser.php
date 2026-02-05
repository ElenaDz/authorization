<?php
namespace Auth\App\Action;

use Auth\App\Model\Users;
use Auth\Sys\Routing;

class ActivationUser extends _Base
{
    public function __invoke($login = null, $code = null)
    {
		// fixme плохая идея объединять эти две проверки для каждой проверки должен быть отдельный if и исключения
	    //  важно сейчас тебя исправить чтобы ты в дальнейшем не делала этой ошибки
        if (empty($login) || empty($code))
        {
            throw new \Exception(
                sprintf(
                    'Нет Логина ("%s") или кода активации ("%s")',
                    $login,
                    $code
                )
            );
        }

        $user = Users::getByLoginOrEmail($login);

        if ($user->validActivationCode($code))
        {
            $user->resetActivationCode();

            $user->save();
        }
    }

    public static function getUrl(array $params = []): string
    {
        return Routing::getUrl(static::class, $params);
    }
}