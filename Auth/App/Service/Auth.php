<?php
namespace Auth\App\Service;

use Auth\App\Action\Logon;
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

        $user->updateUserIp();

        if ( ! headers_sent()) {
            setcookie(Logon::COOKIE_NAME_UPDATE_USER_IP_DONE, true, 0, '/');
        }

        $user->save();

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
    public static function logonByPassword($email, $pass)
    {
		$user = Users::getByEmailOrFall($email);

        if ( ! $user->verifyPass($pass)) {
            throw new \DomainException('Не правильный пароль');
        }

        self::loginUser($user);
    }

    public static function loginUser(User $user)
    {
        if ( ! $user->isActivated()) {
            throw new \Exception('Чтобы войти, вам нужно активировать аккаунт, проверьте почту');
        }

        if (empty($user->getToken())) {
            $user->genToken();
        }

        self::setCookieToken($user);

	    $user->updateLastLoginAt();

	    $user->save();
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

        if (headers_sent())
        {
			if ($with_error) {
				throw new \Exception('Заголовки уже были отправлены');
			}

			return;
        }

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