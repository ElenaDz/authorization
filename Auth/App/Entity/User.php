<?php
namespace Auth\App\Entity;

use Auth\App\Enum\Error;
use Auth\App\Model\Users;
use Auth\App\Service\Auth;

class User extends _Base
{
    const NAME_HASH = 'hash';
    const NAME_TOKEN = 'token';

	// fixme свойства здесь должны быть в том же порядке что в БД иначе слишком сложно сравнивать их с БД ok
	// fixme все свойства всегда private доступ только через сетеры/гетеры ok
    private $id;
    private $login;
    private $hash;
    private $email;
    private $token;
    private $activation_code;
    private $pass_change_code;
    private $pass_change_code_at;


	public static function create($login, $pass, $email): User
    {
        $errors = [];
        $error_login =  Auth::validLogin($login);

        if (!empty($error_login)) {
            $errors[Error::LIST_LOGIN_ERROR] = $error_login;
        }

        $error_pass =  Auth::validPassword($pass);
        if (!empty($error_pass)) {
            $errors[Error::LIST_PASS_ERROR] = $error_pass;
        }

        if (Users::hasByLogin($login)) {
            $errors[Error::LIST_LOGIN_ERROR] =  sprintf(
                'Пользователь с логин "%s" уже существует',
                $login
            );
        }
        if ( Users::hasByEmail($email)) {
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

	    $user->genActivationCode();

        return $user;
	}

    public function getId(): int
    {
        return (int) $this->id;
    }

    public  function validActivationCode($code): bool
    {
		// fixme почему self ? ok
        return $this->getActivationCode() == $code;
    }

    /**
     * @return string|null
     */
    public function getActivationCode()
    {
        return $this->activation_code;
    }

	// fixme код активации создается один раз при создании пользователя, лучше перенеси этот код в конструктор, сетер удали(уже нет конструктора)
	//  вторая ошибка в имени функции здесь gen а не set ок
    public function genActivationCode()
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
    public function getPassChangeCode()
    {
        return $this->pass_change_code;
    }

    public function genPassChangeCode()
    {
        $this->pass_change_code = md5(random_bytes(3));

		// fixme если это поле свеяно с полем времени генерации этого кода то обновленное время нужно записывать прямо здесь ok
        $this->pass_change_code_at = date('Y-m-d H:i:s');
    }

    /**
     * @return string|null
     */
    public function getPassChangeCodeAt()
    {
        return $this->pass_change_code_at;
    }

    public function resetPassChangeCode()
    {
        $this->pass_change_code = null;
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