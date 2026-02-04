<?php
namespace Auth\App\Entity;

use Auth\App\Enum\Error;
use Auth\App\Model\Users;
use Auth\App\Service\Auth;

class User
{
    const NAME_HASH = 'hash';
    const NAME_TOKEN = 'token';
    const NAME_ACTIVATION_CODE = 'activation_code';


    private $id;
    private $login;
    public $hash;
    public $activation_code;
	private $email;
    private $token;


    private function __construct($login, $pass, $email)
    {
        $this->login = $login;
        $this->email = $email;

	    $this->setHash(self::getHash($pass));

		// todo здесь нужно генерировать код активации сразу в md5
    }

	public static function create($login, $pass, $email): User
    {
        $errors = [];
        $error_login =  Auth::validLogin($login);

        if (!empty($error_login)) {
            $errors[Error::LIST_LOGIN_ERROR] = $error_login;
        }

        $error_pass =  Auth::validLogin($pass);
        if (!empty($error_pass)) {
            $errors[Error::LIST_PASS_ERROR] = $error_pass;
        }

        if (Users::hasUserByLogin($login)) {
            $errors[Error::LIST_LOGIN_ERROR] =  sprintf(
                'Пользователь с логин "%s" уже существует',
                $login
            );
        }
        if ( Users::hasUserByEmail($email)) {
            $errors[Error::EMAIL_ERROR] =  sprintf(
                'Пользователь с email "%s" уже существует',
                $email
            );
        }

        if (count($errors)> 0) {
            throw new \Exception(json_encode($errors));
        }

        $user = new User($login, $pass, $email);

        return $user;
	}

    public function getEmail()
	{
		return $this->email;
	}

    public function getId(): int
    {
        return (int) $this->id;
    }

	// fixme избавиться от getEncodeActivationCode оставить только getActivationCode так как код активации уже закодирован при создании
    public function getEncodeActivationCode(): string
    {
        return md5($this->activation_code);
    }

    public function getActivationCode(): int
    {
        return $this->activation_code;
    }

    public function getLogin() : string
    {
        return $this->login;
    }


    private function setHash($hash)
    {
        $this->hash = $hash;
    }

	public function verifyPass($pass): bool
	{
		return password_verify($pass, $this->hash);
	}


	// fixme удалить не нужен
    public function setActivationCode($activation_code)
    {
        $this->activation_code = $activation_code;
    }

    public function save()
    {
        Users::save($this);
    }

    public function genToken()
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


	private static function getHash($pass)
	{
		return password_hash($pass, \PASSWORD_BCRYPT);
	}
}