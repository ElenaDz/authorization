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

	
	// todo добавить нужные сеттеры и добавить в них валидацию
    private $id;
    private $login;
    public $hash;
    public $activation_code;
	private $email;
    private $token;


	// fixme конструктор должен быть private чтобы не было возможности создать пользователя с помощью него, для этого метод create
    private function __construct($login = null, $pass = null, $email = null)
    {
        if (!empty($login)) {
            $this->login = $login;
        }

        if (!empty($email)) {
            $this->email = $email;
        }

        if (!$pass === false) {
			// fixme в момент создания пользователя все поля его пустые зачем ты здесь проверяешь старое значение пароля?
            if ($pass !== $this->getHashFromBD())
            {
                $this->setPass($pass);
            }
        }

        if (empty($this->getActivationCode())) {
            $this->setActivationCode();
        }

		// todo используй здесь random_bytes вместо random_int ok
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


    public  function validActivationCode($code): bool
    {
		// fixme если у тебя есть геттер для свойства необходимо использовать его вместо прямого обращения к свойству ok
        return self::getActivationCode() == $code;
    }

    /**
     * @return string|null
     */
    public function getActivationCode()
    {
        return $this->activation_code;
    }

    public function setActivationCode()
    {
        if (empty($this->getToken()) ) {
            $this->activation_code = md5(random_bytes(5));
        }
    }

	public function resetActivationCode()
	{
		$this->activation_code = null;
	}

    public function getLogin() : string
    {
        return $this->login;
    }

    private function setPass($pass)
    {
        $this->setHash(self::getHash($pass));
    }
	// fixme лучше сделать метод setPass и пускай логика что происходит после этого уже будет скрыта в этом методе ok
    private function setHash($hash)
    {
        $this->hash = $hash;
    }

	// fixme ерунда какая то pass или hash ok
    private function getHashFromBD()
    {
        return $this->hash;
    }

	public function verifyPass($pass): bool
	{
		return password_verify($pass, $this->hash);
	}


    public function genToken()
    {
        $this->token = md5(uniqid('', true));
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }

    public function resetToken()
    {
        unset($this->token);
    }


	public function save()
	{
		Users::save($this);
	}


	private static function getHash($pass)
	{
		return password_hash($pass, \PASSWORD_BCRYPT);
	}
}