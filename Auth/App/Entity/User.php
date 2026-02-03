<?php
namespace Auth\App\Entity;

use Auth\App\Enum\Error;
use Auth\App\Model\Users;
use Auth\App\Service\Auth;

class User
{
    const NAME_HASH = 'hash';
    const NAME_TOKEN = 'token';
    private $id;
    private $login;
    public $hash;
    public $activation_code;
	private $email;
    private $token;

	// fixme тебе нужно придумать минимальный необходимый набор данных для создания пользователя и указать его здесь
	//  в конструкторе в качестве аргументов ok
    private function __construct($login, $email)
    {
        $this->login = $login;
        $this->email = $email;
    }

	// fixme не нужно передать сюда два пароля, то что там пароля два это относится к контролеру ok
	public static function create($login, $pass, $email): User
    {
		// fixme если бросать исключения мы будем получать только одну ошибку, а ошибок может быть несколько,
		//  а нам нужно показывать сразу все ошибки, поэтому нужно возвращать массив всех ошибок ok
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

        $user =  new User($login, $email);

        Auth::setHashForUser($user, $pass);

        return $user;
		// fixme мы находимся в сущности мы не можешь здесь добавлять данные в БД, это делает модель или контроллер,
		//  здесь мы можешь только создать пользователя и вернуть его ok
	}

    public function getEmail()
	{
		return $this->email;
	}

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getEncodeActivationCode(): string
    {
        $code = $this->activation_code."c1k";

        return base64_encode($code);
    }

    public function getLogin() : string
    {
        return $this->login;
    }

    public function getHash() : string
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function verifyPass($pass): bool
    {
        return Auth::passwordVerify($pass, $this->hash);
    }

	// fixme скорее это genToken тоесть генерация токена так как это его можно вызывать несколько раз ok
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

//    удалила не использую
}