<?php
namespace Auth\App\Entity;

use Auth\App\Model\Users;

class User extends _Base
{
    const NAME_ID = 'id';
    const NAME_LOGIN = 'login';
    const NAME_HASH = 'hash';
    const NAME_EMAIL = 'email';
    const NAME_TOKEN = 'token';
    const NAME_ACTIVATION_CODE = 'activation_code';
    const NAME_CREATED_AT = 'created_at';
    const NAME_PASS_CHANGE_CODE = 'pass_change_code';
    const NAME_PASS_CHANGE_CODE_AT = 'pass_change_code_at';

    private $id;
    private $login;
    private $hash;
    private $email;
    private $token;
    private $activation_code;
    private $created_at;
    private $pass_change_code;
    private $pass_change_code_at;


	public static function create($login, $pass, $email): User
    {
        $user = new self();

	    $user->setLogin($login);

	    $user->setEmail($email);

	    $user->setPass($pass);

	    $user->activation_code = md5(random_bytes(5));

        return $user;
	}

    public function getId(): int
    {
        return (int) $this->id;
    }

    public  function validActivationCode($code)
    {
        if ( $this->getActivationCode() !== $code) {
	        // fixme здесь можно указать конкретную причину так как она прямо здесь а не скрыта за вызовом функции
            throw new \Exception('Код активации не валиден');
        }
    }

	public function validPassChangeCode($code)
	{
        if ($this->getPassChangeCode() !== $code) {
			// fixme здесь можно указать конкретную причину так как она прямо здесь а не скрыта за вызовом функции
            throw new \Exception('Код смены пароля не валиден');
        }
	}

    /**
     * @return string|null
     */
    public function getActivationCode()
    {
        return $this->activation_code;
    }

	public function resetActivationCode()
	{
		$this->activation_code = null;
	}

    public function isActivated()
    {
        return empty($this->getActivationCode());
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

        $this->pass_change_code_at = date('Y-m-d H:i:s');
    }

    public function resetPassChangeCode()
    {
        $this->pass_change_code = null;

        $this->pass_change_code_at = null;
    }

    /**
     * @return string|null
     */
    public function getPassChangeCodeAt()
    {
        return $this->pass_change_code_at;
    }

	private function setLogin($login)
	{
        self::validLogin($login);

        if (Users::hasByLogin($login)) {
            throw new \Exception('Пользователь с таким Именем уже есть');
        }

		$this->login = $login;
	}

	// fixme логин и емейл эти те данные которые пришли к нам от пользователя и там могут быть XSS инфекции, чтобы
	//  защититься от них нужно во всех местах где эти данные вставляются в html использовать функцию htmlspecialchars
    public function getLogin() : string
    {
        return $this->login;
    }

    public function setPass($pass)
    {
        self::validPassword($pass);

        $this->hash = self::getHashForPass($pass);
    }

    private function getHash()
    {
        return $this->hash;
    }

	public function setEmail($email)
	{
        self::validEmail($email);

        if (Users::hasByEmail($email)) {
            throw new \Exception('Пользователь с таким email уже есть');
        }

		$this->email = $email;
	}

	public function getEmail()
	{
		return $this->email;
	}

    public function getCreatedAt()
    {
        return $this->created_at;
    }

	public function verifyPass($pass): bool
	{
		return password_verify($pass, $this->getHash());
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
        $this->token = null;
    }


	public function save()
	{
		Users::save($this);
	}


	public function delete()
	{
		Users::deleteById($this->getId());
	}


    public static function validLogin($login)
    {
        if (mb_strlen($login) > 100) {
            throw new \DomainException(
                'Логин доложен быть не больше 100 символов'
            );
        }
    }

    public static function validEmail($email)
    {
        if (mb_strlen($email) > 40) {
            throw new \DomainException(
                'Email должен быть не больше 40 символов'
            );
        }
    }

    public static function validPassword($pass)
    {
        if (mb_strlen($pass) < 6) {
            throw new \DomainException('Пароль должен быть не менее 6 символов');
        }

        if (mb_strlen($pass) > 30) {
            throw new \DomainException('Пароль должен быть не больше 30 символов');
        }

        if ( ! preg_match('/[A-ZА-ЯЁ]/', $pass)) {
            throw new \DomainException('Пароль должен содержать хотя бы одну заглавную букву');
        }

        if ( ! preg_match('/[a-zа-яё]/', $pass)) {
            throw new \DomainException('Пароль должен содержать хотя бы одну строчную букву');
        }

        if ( ! preg_match('/[!"#$%&()*+,. :;<=>?]/', $pass)) {
            throw new \DomainException(
                'Пароль должен содержать хотя бы один символ из перечисленных: ! " # $ % & ( ) * + , . : ; < = > ?'
            );
        }
    }

	private static function getHashForPass($pass)
	{
		return password_hash($pass, \PASSWORD_BCRYPT);
	}
}