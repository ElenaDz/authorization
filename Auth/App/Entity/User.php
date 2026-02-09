<?php
namespace Auth\App\Entity;

use Auth\App\Enum\Error;
use Auth\App\Model\Users;
use Auth\App\Service\Auth;

class User extends _Base
{
    const NAME_HASH = 'hash';
    const NAME_TOKEN = 'token';


	// fixme свойства здесь должны быть в том же порядке что в БД иначе слишком сложно сравнивать их с БД
	// fixme все свойства всегда private доступ только через сетеры/гетеры
    private $id;
    private $login;
    public $hash;
    public $activation_code;
    public $change_pass_code;
	private $email;
    private $token;


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
	        // fixme не будем кидать json в качестве исключения, ни когда такого не видел не слышал
            throw new \Exception(json_encode($errors));
        }

        $user = new self();

	    $user->setLogin($login);

	    $user->setEmail($email);

	    $user->setPass($pass);

	    $user->setActivationCode();

        return $user;
	}


    public function getId(): int
    {
        return (int) $this->id;
    }


    public  function validActivationCode($code): bool
    {
		// fixme почему self ?
        return self::getActivationCode() == $code;
    }

    /**
     * @return string|null
     */
    public function getActivationCode()
    {
        return $this->activation_code;
    }

	// fixme код активации создается один раз при создании пользователя, лучше перенеси этот код в конструктор, сетер удали
	//  вторая ошибка в имени функции здесь gen а не set
    public function setActivationCode()
    {
		$this->activation_code = md5(random_bytes(5));
    }

	public function resetActivationCode()
	{
		$this->activation_code = null;
	}


    /**
     * @return string|null
     */
    public function getChangePassCode()
    {
        return $this->change_pass_code;
    }

    public function genChangePassCode()
    {
        $this->change_pass_code = md5(random_bytes(3));

		// fixme если это поле свеяно с полем времени генерации этого кода то обновленное время нужно записывать прямо здесь
    }



	private function setLogin($login)
	{
		// todo валидация

		$this->login = $login;
	}

    public function getLogin() : string
    {
        return $this->login;
    }


    private function setPass($pass)
    {
		// todo валидация

	    $this->hash = self::getHashForPass($pass);
    }

    private function getHash()
    {
        return $this->hash;
    }


	public function setEmail($email)
	{
		// todo валидация

		$this->email = $email;
	}

	public function getEmail()
	{
		return $this->email;
	}


	public function verifyPass($pass): bool
	{
		// fixme для hash есть гетер
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


	private static function getHashForPass($pass)
	{
		return password_hash($pass, \PASSWORD_BCRYPT);
	}
}