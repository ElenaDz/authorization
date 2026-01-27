<?php
namespace Auth\App\Service;

use Auth\App\Entity\User;
use Auth\App\Model\Users;

class Auth
{
    const NAME_COOKIE_TOKEN = 'auth_token';

    /**
     * @var User $user
     */
    private static $user;

    public static function isAuthorized(): bool
    {
        $token = $_COOKIE[self::NAME_COOKIE_TOKEN];
        if (empty($token)) return false;

        if (self::$user) return true;

        $user = Users::getByToken($token);
        if (empty($user))
        {
            unset($_COOKIE[self::NAME_COOKIE_TOKEN]);
            setcookie(self::NAME_COOKIE_TOKEN, '', -1, "/");

            return false;
        }

        self::$user = $user;

        return true;
    }

	public static function getUser()
	{
		if ( ! self::isAuthorized()) return null;

		return new User();
	}

    public static function logon($login, $pass)
    {
        $user = Users::getByLogin($login);
        if (empty($user))
        {
            throw new \DomainException(
                sprintf(
                    'Пользователь "%s" не найден',
                    $login
                )
            );
        }

        if ( ! $user->verifyPass($pass)) {
            throw new \DomainException('Не правильный пароль');
        }

        $user->save();

        $result = setcookie(self::NAME_COOKIE_TOKEN, $user->getToken(), time() + (3600 * 24 * 30), "/");
        if ( ! $result) {
            throw new \Exception('Не удалось установить cookie');
        }
    }

    public static function logout()
    {
        if ( ! Auth::isAuthorized()) return;

        $user = Auth::getUser();

        $user->resetToken();

        $user->save();

        unset($_COOKIE[self::NAME_COOKIE_TOKEN]);

        $result = setcookie(self::NAME_COOKIE_TOKEN, '', -1, "/");
        if ( ! $result) {
            throw new \Exception('Не удалось удалить cookie');
        }
    }

    public static function getHash($pass)
    {
        return password_hash($pass, \PASSWORD_BCRYPT);
    }

    public static function passwordVerify($pass, $hash): bool
    {
        return password_verify($pass, $hash);
    }

    public static function validRegData($login, $pass)
    {
        self::validLogin($login);
        self::validPassword($pass);
    }

    public static function validPassword($pass)
    {
        $rules = [
            [
                'check' => function ($p) {
                    return strlen($p) > 5;
                },
                'error' => 'Пароль должен быть не менее 6 символов',
            ],
            [
                'check' => function ($p) {
                    return strlen($p) <= 30;
                },
                'error' => 'Пароль должен быть меньше 31 символа',
            ],
            [
                'check' => function ($p) {
                    return preg_match('/[A-Z]/', $p);
                },
                'error' => 'Пароль должен содержать хотя бы одну заглавную латинскую букву',
            ],
            [
                'check' => function ($p) {
                    return !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!"#$%&()*+,\-\.\/:;<=>\?]).+$/', $p);
                },
                'error' => 'Пароль не соответствует требованиям сложности',
            ],
            [
                'check' => function ($p) {
                    return preg_match('/[a-z]/', $p);
                },
                'error' => 'Пароль должен содержать хотя бы одну строчную латинскую букву',
            ],
            [
                'check' => function ($p) {
                    return preg_match('/[!"#$%&()*+,\-\.\/:;<=>\?]/', $p);
                },
                'error' => 'Пароль должен содержать хотя бы один символ из перечисленных: ! " # $ % & ( ) * + , - . / : ; < = > ?',
            ],
        ];

        foreach ($rules as $rule) {
            if (!call_user_func($rule['check'], $pass)) {
                throw new \Exception($rule['error']);
            }
        }
    }

    public static function validLogin($login)
    {
        if ( strlen($login) > 100) {
            throw new \Exception(
                sprintf(
                    'Имя пользователя должно быть меньше 100 символов'
                )
            );
        }
    }
}