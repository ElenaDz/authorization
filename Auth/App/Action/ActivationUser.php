<?php
namespace Auth\App\Action;

use Auth\App\Model\Users;
use Auth\App\Service\Auth;
use Auth\Sys\Routing;
use Auth\Sys\Response;

class ActivationUser extends _Base
{
    public function __invoke($login = null, $code = null)
    {
		// fixme плохая идея объединять эти две проверки для каждой проверки должен быть отдельный if и исключения ok
	    //  важно сейчас тебя исправить чтобы ты в дальнейшем не делала этой ошибки
        if (empty($login))
        {
            throw new \Exception(
                'Нет Логина'
            );
        } elseif (empty($code)) {
            throw new \Exception(
                'Нет кода активации'
            );
        }

        $user = Users::getByLoginOrEmail($login);

        if ($user->validActivationCode($code))
        {
            Auth::activation($login);

            $user->resetActivationCode();

            Response::redirect('http://authorization/');
        }
    }

    public static function getUrl(array $params = []): string
    {
        return Routing::getUrl(static::class, $params);
    }
}