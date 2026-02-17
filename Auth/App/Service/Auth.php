<?php
namespace Auth\App\Service;

use Auth\App\Entity\User;
use Auth\App\Model\Users;
use Auth\Sys\Request;

class Auth
{
    const COOKIE_NAME_TOKEN = 'auth_token';

    /**
     * @var User $user
     */
    private static $user;


    public static function isAuthorized(): bool
    {
		$token = $_COOKIE[self::COOKIE_NAME_TOKEN] ?? null ;
        if (empty($token)) return false;

        if (self::$user) return true;

        $user = Users::getByToken($token);
        if (empty($user))
        {
            self::unsetCookieToken();

            return false;
        }

        self::$user = $user;

        return true;
    }

	public static function getUser()
	{
		if ( ! self::isAuthorized()) return null;

		return self::$user;
	}


    /**
     * @throws \Exception
     */
    public static function logonByPassword($login_or_email, $pass)
    {
        try {
            $user = Users::getByLoginOrEmailOrFall($login_or_email);

        } catch (\Exception $exception ) {
            throw new \DomainException($exception->getMessage());
        }

        if ( ! $user->verifyPass($pass)) {
            throw new \DomainException('Не правильный пароль');
        }

        self::loginUser($user);
    }

    public static function loginUser(User $user)
    {
		// todo добавить проверку что аккаунт пользователя активирован

        $user->genToken();

        $user->save();

        self::setCookieToken($user);
    }


	public static function logout()
	{
		if ( ! Auth::isAuthorized()) return;

		$user = Auth::getUser();

		$user->resetToken();

		$user->save();

		self::unsetCookieToken(true);
	}

	private static function setCookieToken($user)
	{
		$result = setcookie(
			self::COOKIE_NAME_TOKEN,
			$user->getToken(),
			time() + (3600 * 24 * 30 * 1),
			'/',
			'',
			Request::isDevelopment() ? false : true,
			true
		);

		if ( ! $result) {
			throw new \Exception('Не удалось установить cookie');
		}
	}

	private static function unsetCookieToken($with_error = false)
	{
		unset($_COOKIE[self::COOKIE_NAME_TOKEN]);

		// todo добавь проверку что заголовки уже были отправлены с помощью headers_sent() так как если они отправлены куки уже не установить
		//  кидай исключение если $with_error true
		$result = setcookie(
			self::COOKIE_NAME_TOKEN,
			'',
			-1,
			"/"
		);
		if ( ! $result && $with_error) {
			throw new \Exception('Не удалось удалить cookie');
		}
	}
}