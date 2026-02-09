<?php
namespace Auth\App\Action;

use Auth\App\Model\Users;
use Auth\App\Service\Auth;
use Auth\Sys\Routing;
use Auth\Sys\Response;

class ActivationUser extends _Base
{
	const PARAM_NAME_LOGIN = 'login';


    public function __invoke($login = null, $code = null)
    {
        if (empty($login)) {
            throw new \Exception('Нет логина');

        } elseif (empty($code)) {
            throw new \Exception('Нет кода активации');
        }

        $user = Users::getByLoginOrEmail($login);

		// todo нет сообщения от ошибке когда код активации не правильный
        if ($user->validActivationCode($code))
        {
            Auth::logonWithoutPassword($login);

            $user->resetActivationCode();

            Response::redirect('http://authorization/');
        }
    }

    public static function getUrl(array $params = []): string
    {
        return Routing::getUrl(static::class, $params);
    }
}