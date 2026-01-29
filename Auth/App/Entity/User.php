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

	// fixme тебе нужно придумать минимальный необходимый набор данных для создания пользователя и указать его здесь
	//  в конструкторе в качестве аргументов
    private function __construct()
    {

    }

	// fixme не нужно передать сюда два пароля, то что там пароля два это относится к контролеру
	public static function create($login, $pass, $pass_confirm, $email)
	{
		// fixme если бросать исключения мы будем получать только одну ошибку, а ошибок может быть несколько,
		//  а нам нужно показывать сразу все ошибки, поэтому нужно возвращать массив всех ошибок
        Auth::validLogin($login);
		Auth::validPassword($pass);

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

		$user = new User();

		// fixme мы находимся в сущности мы не можешь здесь добавлять данные в БД, это делает модель или контроллер,
		//  здесь мы можешь только создать пользователя и вернуть его
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

	// fixme скорее это genToken тоесть генерация токена так как это его можно вызывать несколько раз
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