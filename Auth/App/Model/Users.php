<?php
namespace Auth\App\Model;

use Auth\App\Entity\User;
use Auth\App\Service\Auth;

class Users extends _Base
{
	/**
	 * @param $id
	 * @return User|false
	 */
	public static function getById($id)
	{
		$results = self::getPDO()->query (
			'SELECT * FROM users WHERE id ='. (int) $id
		);

		return $results->fetchObject(
			User::class
		);
	}

	/**
	 * @param $login
	 * @return bool
	 */
	public static function hasUserByLogin($login): bool
    {
		$pdo = self::getPDO();

		$results = $pdo->prepare(
			'SELECT * FROM users WHERE login=:login'
		);

		$results->execute([
			'login' => $login
		]);

        return !empty($results->fetchColumn());
	}

    /**
     * @param $login_or_email
     * @return User|false
     */
    public static function getByLoginOrEmail($login_or_email)
    {
        $pdo = self::getPDO();

        $results = $pdo->prepare(
            'SELECT * FROM users WHERE login = :value OR email = :value'
        );

        $results->execute([
            'value' => $login_or_email
        ]);

        return $results->fetchObject(
            User::class
        );
    }

    /**
     * @param $email
     * @return bool
     */
    public static function hasUserByEmail($email): bool
    {
        $pdo = self::getPDO();

        $results = $pdo->prepare(
            'SELECT * FROM users WHERE email=:email'
        );

        $results->execute([
            'email' => $email
        ]);

        return !empty($results->fetchColumn());
    }

	/**
	 * @return User[]
	 */
	public static function getAll(): array
    {
		$pdo = self::getPDO();

		$results = $pdo->query(
			'SELECT * FROM users'
		);

		return $results->fetchAll(
			\PDO::FETCH_CLASS,
			User::class
		);
	}

	/**
	 * @param $token
	 * @return User|false|null
	 */
    public static function getByToken($token)
    {
        $pdo = self::getPDO();

        $results = $pdo->prepare(
            'SELECT * FROM users WHERE token=:token LIMIT 1'
        );

        $results->execute([
            'token' => $token
        ]);

        return $results->fetchObject(
            User::class
        );
    }

	public static function add(User $user): int
	{
        $code = random_int(1, 1000);

		$prepare = self::getPDO()->prepare(
			'INSERT INTO 
                     users
                    (login, hash, email, activation_code, token) 
                VALUES 
                    (:login, :hash, :email,:activation_code, null)'
		);

		$prepare->execute([
			'login' => $user->getLogin(),
			'hash' => self::getPrivatePropValueByUser($user, User::NAME_HASH),
			'email' => $user->getEmail(),
            'activation_code' => $code
		]);

		return self::getPDO()->lastInsertId();
	}


    public static function save(User $user)
    {
        $user_from_db = self::getById($user->getId()) ?? null;

	    // fixme тут нужно добавить проверку что если у пользователя есть id но он не найден в бд то кидаем ошибку

        if (
                empty($user->getId())
            ||  empty($user_from_db)
        ) {
            self::add($user);
        }

        if ($user_from_db->getLogin() !== $user->getLogin())
        {
            throw new \Exception(
                sprintf(
                    'Логин пользователя не может измениться. Был "%s", стал "%s"',
                    $user_from_db->getLogin(),
                    $user->getLogin()
                )
            );
        }

        $prepare = self::getPDO()->prepare(
            'UPDATE 
                        users 
                    SET 
                        hash = :hash, 
                        token = :token,
                        activation_code = :activation_code
                    WHERE 
                        id = :id'
        );

        $prepare->execute([
            'id'                => $user->getId(),
            'hash'              => self::getPrivatePropValueByUser($user, User::NAME_HASH),
            'token'             => self::getPrivatePropValueByUser($user, User::NAME_TOKEN),
            'activation_code'   => self::getPrivatePropValueByUser($user, User::NAME_ACTIVATION_CODE)
        ]);
    }


	private static function getPrivatePropValueByUser(User $user, string $prop_name)
	{
		$ref_user = new \ReflectionClass($user);

		$prop = $ref_user->getProperty($prop_name);

		$prop->setAccessible(true);

		return $prop->getValue($user);
	}
}