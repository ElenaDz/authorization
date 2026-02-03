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

	// fixme вызовом этого метода ты создашь нового пользователя, а я просил чтобы пользователя можно было создать только
	//  в одном месте, функцию должна принимать объект сущности User ok
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
			'hash' => $user->getHash(),
			'email' => $user->getEmail(),
            'activation_code' => $code
		]);

		return self::getPDO()->lastInsertId();
	}

//     удалила, т.к. пока не использую
    public static function save(User $user)
    {
        $user_from_db = self::getById($user->getId()) ?? null;

        if (
            empty($user->getId())
            // fixme когда у пользователя есть id но он не найден в БД нужно показывать ошибку, потому что это явная ошибка
            //  кстати ты запрашиваешь пользователя второй раз ниже, это не правильно, нужно делать это один раз
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
                        token = :token
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