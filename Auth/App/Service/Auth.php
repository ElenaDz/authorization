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
		$token = $_COOKIE[self::COOKIE_NAME_TOKEN];
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
		// fixme используй функцию getByLoginOrEmailOrFall я писал подробнее в другом fixme
        $user = Users::getByLoginOrEmail($login_or_email);

        self::verifyLogin($user, $login_or_email);

        if ( ! $user->verifyPass($pass)) {
            throw new \DomainException('Не правильный пароль');
        }

        self::logonWithoutPassword($login_or_email);
    }

	// fixme переименовать в loginUser(User $user) чтобы избавиться от повторного запроса пользователя из БД
    public static function logonWithoutPassword($login_or_email)
    {
        $user = Users::getByLoginOrEmail($login_or_email);

        self::verifyLogin($user, $login_or_email);

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


	// fixme удалить, больше не нужно
    private static function verifyLogin($user, $login_or_email)
    {
        if ($user) return;

        throw new \DomainException(
            sprintf(
                'Пользователь "%s" не найден',
                $login_or_email
            )
        );
    }


	private static function setCookieToken($user)
	{
		$result = setcookie(
			self::COOKIE_NAME_TOKEN,
			$user->getToken(),
			[
				'expires' => time() + (3600 * 24 * 30 * 1),
				'path' => '/',
				'secure' => Request::isDevelopment() ? false : true,
				'httponly' => true,
				'samesite' => 'Lax'
			]
		);
		if ( ! $result) {
			throw new \Exception('Не удалось установить cookie');
		}
	}

	private static function unsetCookieToken($with_error = false)
	{
		unset($_COOKIE[self::COOKIE_NAME_TOKEN]);

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