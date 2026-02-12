<?php
namespace Auth\App\Entity;

use Auth\App\Enum\Error;
use Auth\App\Model\Users;
use Auth\App\Service\Auth;

class User extends _Base
{
    const NAME_HASH = 'hash';
    const NAME_TOKEN = 'token';

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

    public  function validActivationCode($code): bool
    {
        return $this->getActivationCode() == $code;
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
        self::validLogin($login);

		// todo здесь нет проверки что этот логин уже занят другим пользователем, должна быть здесь, а не в методе выше
		//  так как нам понадобиться id пользователя для этой проверки

		$this->login = $login;
	}

    public function getLogin() : string
    {
        return $this->login;
    }

    private function setPass($pass)
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

		// todo здесь нет проверки для емейл уже занят

		$this->email = $email;
	}

	public function getEmail()
	{
		return $this->email;
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
        unset($this->token);
    }

	public function save()
	{
		Users::save($this);
	}

    public static function validLogin($login)
    {
		// fixme вместо strlen во всех валидациях нужно использовать mb_strlen потому что мы работает c кодировкой utb8 а она многобайтовая
        if (strlen($login) > 100) {
            throw new \DomainException(
                'Логин доложен быть меньше 100 символов'
            );
        }
    }

    public static function validEmail($email)
    {
        if (strlen($email) > 40) {
            throw new \DomainException(
                'Email доложен быть меньше 40 символов'
            );
        }
    }

    public static function validPassword($pass)
    {
        if (strlen($pass) < 6) {
            throw new \DomainException('Пароль должен быть не менее 6 символов');
        }

        if (strlen($pass) > 30) {
            throw new \DomainException('Пароль должен быть меньше 31 символа');
        }

        if ( ! preg_match('/[A-Z]/', $pass)) {
            throw new \DomainException('Пароль должен содержать хотя бы одну заглавную латинскую букву');
        }

        if ( ! preg_match('/[a-z]/', $pass)) {
            throw new \DomainException('Пароль должен содержать хотя бы одну строчную латинскую букву');
        }

        if ( ! preg_match('/[!"#$%&()*+,. :;<=>?]/', $pass)) {
            throw new \DomainException(
                'Пароль должен содержать хотя бы один символ из перечисленных: ! " # $ % & ( ) * + , . : ; < = > ?'
            );
        }

		// fixme кажется дублирует выше приведенные проверки, если так удалить
        if ( ! preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!"#$%&()*+,. :;<=>?]).+$/', $pass)) {
			// fixme не понятное сообщение об ошибки, оно не помогает исправить ошибку
            throw new \DomainException('Пароль не соответствует требованиям сложности');
        }
    }

	private static function getHashForPass($pass)
	{
		return password_hash($pass, \PASSWORD_BCRYPT);
	}
}