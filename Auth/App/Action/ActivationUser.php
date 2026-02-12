<?php
namespace Auth\App\Action;

use Auth\App\Model\Users;
use Auth\App\Service\Auth;
use Auth\Sys\Routing;
use Auth\Sys\Response;

class ActivationUser extends _Base
{
	const PARAM_NAME_LOGIN = 'login';
	const PARAM_NAME_CODE = 'code';


    public function __invoke($login = null, $code = null)
    {
        if (empty($login)) {
            throw new \Exception('Нет логина');

        } elseif (empty($code)) {
            throw new \Exception('Нет кода активации');
        }

		// todo заведи метод getByLoginOrEmailOrFall который будет вызывать getByLoginOrEmail и если он ни чего не вернул
	    //  бросай исключение и используй его везде где это нужно, например здесь
        $user = Users::getByLoginOrEmail($login);

        if ( ! $user->validActivationCode($code))
        {
            throw new \Exception('Код активации не совпадает');
        }

		// fixme основной код не должен быть во вложенности
        if ($user->validActivationCode($code))
        {
            $user->resetActivationCode();

            Auth::logonWithoutPassword($login);

            Response::redirect('/');
        }
    }

    public static function getUrl(array $params = []): string
    {
        return Routing::getUrl(static::class, $params);
    }
}