<?php
namespace Auth\App\Entity;

use Auth\App\Model\Users;
use Auth\App\Service\Auth;

class User
{
    const NAME_HASH = 'hash';
    const NAME_TOKEN = 'token';
    private $id;
    private $login;
    private $hash;
	private $email;
    private $token;

    private function __construct()
    {

    }

	public static function create($login, $pass, $pass_confirm, $email)
	{
        Auth::validRegData($login, $pass);

        $user_by_login = Users::getByLogin($login);
        $user_by_email = Users::getByEmail($email);

        if ( ! empty($user_by_login)) {
            throw new \Exception(
                sprintf(
                    'Пользователь с логин "%s" уже существует',
                    $login
                )
            );
        }

        if ( ! empty($user_by_email)) {
            throw new \Exception(
                sprintf(
                    'Пользователь с email "%s" уже существует',
                    $email
                )
            );
        }

        if ( $pass != $pass_confirm) {
            throw new \Exception('Пароли не совпадают');
        }

        $hash = Auth::getHash($pass);

        Users::add($login, $hash, $email);
	}


    public function getEmail()
	{
		return $this->email;
	}

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getLogin() : string
    {
        return $this->login;
    }

    public function getHash() : string
    {
        return $this->hash;
    }

    public function verifyPass($pass): bool
    {
        return Auth::passwordVerify($pass, $this->hash);
    }

	// fixme по-умолчанию нужно создавать функции private и менять только по необходимости ok
    public function createToken()
    {
        $this->token = md5(uniqid('', true));
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function resetToken()
    {
        unset($this->token);
    }

    public function save()
    {
        \Auth\APP\Model\Users::save($this);
    }
}