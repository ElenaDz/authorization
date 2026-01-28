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
	 * @return User|false
	 */
	public static function getByLogin($login)
	{
		$pdo = self::getPDO();

		$results = $pdo->prepare(
			'SELECT * FROM users WHERE login=:login'
		);

		$results->execute([
			'login' => $login
		]);

		return $results->fetchObject(
			User::class
		);
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
     * @return User|false
     */
    public static function getByEmail($email)
    {
        $pdo = self::getPDO();

        $results = $pdo->prepare(
            'SELECT * FROM users WHERE email=:email'
        );

        $results->execute([
            'email' => $email
        ]);

        return $results->fetchObject(
            User::class
        );
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

	public static function add($login, $hash, $email): int
	{
		// fixme логику создания пользователя лучше перенести в метод а здесь только записать его в БД ok
		/** @link \Auth\App\Entity\User::create */

		$prepare = self::getPDO()->prepare(
			'INSERT INTO 
                     users
                    (login, hash, email, token) 
                VALUES 
                    (:login, :hash, :email, null)'
		);

		$prepare->execute([
			'login' => $login,
			'hash' => $hash,
			'email' => $email
		]);

		return self::getPDO()->lastInsertId();
	}

    public static function save(User $user)
    {
		// fixme если id пустой нужно добавить а тут ошибка кривая выскочит(ok)
        if (
                empty($user->getId())
            ||  empty(self::getById($user->getId()))
        ) {
            self::add($user->getLogin(), $user->getHash(), $user->getEmail());
        }

        $user_from_db = self::getById($user->getId());

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
                        `hash` = :hash, 
                        `token` = :token
                    WHERE 
                        id = :id'
        );

        $ref_user = new \ReflectionClass($user);

        $prop_hash = $ref_user->getProperty(User::NAME_HASH);
        $prop_token = $ref_user->getProperty(User::NAME_TOKEN);

        $prop_hash->setAccessible(true);
        $prop_token->setAccessible(true);

        $prepare->execute([
            'id'            => $user->getId(),
            'hash'          => $prop_hash->getValue($user),
            'token'         => $prop_token->getValue($user),
        ]);
    }
}