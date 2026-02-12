<?php
namespace Auth\App\Model;

use Auth\App\Entity\User;
use Auth\App\Service\Auth;
use PDO;

// todo добавить ключи в БД для полей по которым происходит поиск для всей этой модели
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

	// todo создать контролер который будет удалять не активированных пользователей
    public static function deleteUserNotActivated()
    {
		// fixme нельзя удалять пользователей напрямую из БД как здесь, так как при удалении пользователя может потребоваться
	    //  удалить что-то еще, например файлы которые относиться к этому пользователю и лежат на диске, а не в БД или
	    //  просто данные в других таблицах которые относятся к этому пользователю, поэтому удаляем пользователей
	    //  по одному по id метод delete(User $user), а кого удалять должен решать контролер соответствующий
        self::getPDO()->query (
            'DELETE FROM users
                    WHERE activation_code IS NOT NULL
                      AND created_at < NOW() - INTERVAL 7 DAY'
        );
    }


	/**
	 * @param $login
	 * @return bool
	 */
	public static function hasByLogin($login): bool
    {
		$pdo = self::getPDO();

	    $results = $pdo->prepare(
			'SELECT * FROM users WHERE login=:login  LIMIT 1'
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
            'SELECT * FROM users WHERE login = :value OR email = :value  LIMIT 1'
        );

		$results->execute([
            'value' => $login_or_email
        ]);

        return $results->fetchObject(User::class);
    }


    /**
     * @param $email
     * @return bool
     */
    public static function hasByEmail($email): bool
    {
        $pdo = self::getPDO();

        $results = $pdo->prepare(
            'SELECT * FROM users WHERE email=:email LIMIT 1'
        );

        $results->execute([
            'email' => $email
        ]);

        return !empty($results->fetchColumn());
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
            'activation_code' => $user->getActivationCode()
		]);

		return self::getPDO()->lastInsertId();
	}


    public static function save(User $user)
    {
        $user_from_db = self::getById($user->getId()) ?? null;

        if ($user->getId() && empty($user_from_db)) {
            throw new \Exception(
                sprintf(
                    'Пользователь с id = "%s" не найден в базе данных',
                    $user->getId()
                )
            );
        }

        if (empty($user->getId()) || empty($user_from_db))
        {
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
                        activation_code = NULL,
                        pass_change_code = :pass_change_code,
                        pass_change_code_at = :pass_change_code_at
                    WHERE 
                        id = :id'
        );

        $prepare->execute([
            'id'                        => $user->getId(),
            'hash'                      => self::getPrivatePropValueByUser($user, User::NAME_HASH),
            'token'                     => self::getPrivatePropValueByUser($user, User::NAME_TOKEN),
            'pass_change_code'          => $user->getPassChangeCode() ?? null,
            'pass_change_code_at'       => $user->getPassChangeCode() ? $user->getPassChangeCodeAt() : null
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