<?php
namespace Auth\App\Service;

use Auth\App\Entity\User;
use Auth\App\Model\Users;

class Auth
{
    const COOKIE_NAME_TOKEN = 'auth.token';

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

    private static function unsetCookieToken($with_error = false)
    {
        unset($_COOKIE[self::COOKIE_NAME_TOKEN]);

	    $result = setcookie(self::COOKIE_NAME_TOKEN, '', -1, "/");
	    if ( ! $result && $with_error) {
		    throw new \Exception('Не удалось удалить cookie');
	    }
    }

    private static function setCookieToken($user)
    {
        $result = setcookie(self::COOKIE_NAME_TOKEN, $user->getToken(), time() + (3600 * 24 * 30), "/");
        if ( ! $result) {
            throw new \Exception('Не удалось установить cookie');
        }
    }

	public static function getUser()
	{
		if ( ! self::isAuthorized()) return null;

		return self::$user;
	}

    public static function validLogin($login)
    {
        if ( strlen($login) > 100) {
            return ['login' =>'Имя пользователя должно быть меньше 100 символов' ];
        }

		return [];
    }

    /**
     * @throws \Exception
     */
    public static function logonByPassword($login_or_email, $pass)
    {
        $user = Users::getByLoginOrEmail($login_or_email);

        self::verifyLogin($user, $login_or_email);

        if ( ! $user->verifyPass($pass)) {
            throw new \DomainException('Не правильный пароль');
        }

        self::logonWithoutPassword($login_or_email);
    }

    public static function logonWithoutPassword($login_or_email)
    {
        $user = Users::getByLoginOrEmail($login_or_email);

        self::verifyLogin($user, $login_or_email);

        $user->genToken();

        $user->save();

        self::setCookieToken($user);
    }

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

    public static function logout()
    {
        if ( ! Auth::isAuthorized()) return;

        $user = Auth::getUser();

        $user->resetToken();

        $user->save();

        self::unsetCookieToken(true);
    }

    public static function validPassword($pass): array
    {
        $errors = [];

        if (strlen($pass) < 6) {
            $errors['password'] = 'Пароль должен быть не менее 6 символов';
            return  $errors;
        }
        if (strlen($pass) > 30) {
            $errors['password'] = 'Пароль должен быть меньше 31 символа';
            return  $errors;
        }

        if (!preg_match('/[A-Z]/', $pass)) {
            $errors['password'] = 'Пароль должен содержать хотя бы одну заглавную латинскую букву';
            return  $errors;
        }

        if (!preg_match('/[a-z]/', $pass)) {
            $errors['password'] = 'Пароль должен содержать хотя бы одну строчную латинскую букву';
            return  $errors;
        }

        if (!preg_match('/[!"#$%&()*+,. :;<=>?]/', $pass)) {
            $errors['password'] = 'Пароль должен содержать хотя бы один символ из перечисленных: ! " # $ % & ( ) * + , . : ; < = > ?';
            return  $errors;
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!"#$%&()*+,. :;<=>?]).+$/', $pass)) {
            $errors['password'] = 'Пароль не соответствует требованиям сложности';
            return  $errors;
        }

        return [];
    }
}