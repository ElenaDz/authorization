<?php
namespace Auth\App\Action;

use Auth\App\Model\Users;
use Auth\App\Service\Auth;
use Auth\Sys\Routing;
use Auth\Sys\Response;

class ActivationUser extends _Base
{
    const GET_NAME_EMAIL = 'email';
	const GET_NAME_CODE = 'code';


    public function __invoke($email = null, $code = null)
    {
        if (empty($email)) {
            throw new \Exception('Нет email');

        } elseif (empty($code)) {
            throw new \Exception('Нет кода активации');
        }

        $user = Users::getByEmailOrFall($email);

        if ($user->isActivated())
        {
            throw new \Exception('Аккаунт уже был активирован ранее');
        }

        $user->validActivationCode($code);

        $user->activation();

        Auth::loginUser($user);

        Response::redirect('/');
    }

    public static function getUrl(array $params = []): string
    {
        return Routing::getUrl(static::class, $params);
    }
}