<?php
namespace Auth\App\Entity;

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

    public function verifyPass($pass): bool
    {
        return Auth::passwordVerify($pass, $this->hash);
    }

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