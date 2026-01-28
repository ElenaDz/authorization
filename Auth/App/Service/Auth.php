<?php
namespace Auth\App\Service;

use Auth\App\Entity\User;
use Auth\App\Model\Users;

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

            setcookie(self::COOKIE_NAME_TOKEN, '', -1, "/");

            return false;
        }

        self::$user = $user;

        return true;
    }

    private static function unsetCookieToken()
    {
        unset($_COOKIE[self::COOKIE_NAME_TOKEN]);
    }

	public static function getUser()
	{
		if ( ! self::isAuthorized()) return null;

		return self::$user;
	}


    public static function logon($login_or_email, $pass)
    {
        $user = Users::getByLoginOrEmail($login_or_email);
        if (empty($user))
        {
            throw new \DomainException(
                sprintf(
                    'Пользователь "%s" не найден',
                    $login_or_email
                )
            );
        }

        if ( ! $user->verifyPass($pass)) {
            throw new \DomainException('Не правильный пароль');
        }

        $user->createToken();

        $user->save();

        $result = setcookie(self::COOKIE_NAME_TOKEN, $user->getToken(), time() + (3600 * 24 * 30), "/");
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

		// fixme сброс токена происходит в нескольких местах, лучше вынести в отдельную функцию ok
        self::unsetCookieToken();

        $result = setcookie(self::COOKIE_NAME_TOKEN, '', -1, "/");
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

	// fixme не понял логики, удалить (ещё не дописано было)
    public static function validRegData($login, $pass)
    {
        self::validLogin($login);
        self::validPassword($pass);
    }

    public static function validPassword($pass)
    {
        if (strlen($pass) < 6) {
            throw new \Exception(
                sprintf(
                    'Пароль должен быть не менее 6 символов'
                )
            );
        }
        if (strlen($pass) > 30) {
            throw new \Exception(
                sprintf(
                    'Пароль должен быть меньше 31 символа'
                )
            );
        }

        if (!preg_match('/[A-Z]/', $pass)) {
            throw new \Exception(
                'Пароль должен содержать хотя бы одну заглавную латинскую букву'
            );
        }

        if (!preg_match('/[a-z]/', $pass)) {
            throw new \Exception(
                'Пароль должен содержать хотя бы одну строчную латинскую букву'
            );
        }

        if (!preg_match('/[!"#$%&()*+,\-\.\/:;<=>\?]/', $pass)) {
            throw new \Exception(
                'Пароль должен содержать хотя бы один символ из перечисленных: ! " # $ % & ( ) * + , - . / : ; < = > ?'
            );
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!"#$%&()*+,\-\.\/:;<=>\?]).+$/', $pass)) {
            throw new \Exception(
                'Пароль не соответствует требованиям сложности'
            );
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