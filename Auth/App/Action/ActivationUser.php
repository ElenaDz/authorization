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

        try {
            $user = Users::getByLoginOrEmailOrFall($login);

        } catch (\Exception $exception ) {
            throw new \Exception($exception->getMessage());
        }

		// todo добавь проверку что аккаунт еще не активирован ок
        if (empty($user->getActivationCode()))
        {
            throw new \Exception('Аккаунт уже был активирован ранее');
        }

        if ( ! $user->validActivationCode($code))
        {
            throw new \Exception('Код активации не валиден');
        }

        $user->resetActivationCode();

        Auth::loginUser($user);

        Response::redirect('/');
    }

    public static function getUrl(array $params = []): string
    {
        return Routing::getUrl(static::class, $params);
    }
}