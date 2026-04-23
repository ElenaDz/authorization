<?php
namespace Auth\App\Model;

use Auth\App\Entity\User;

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

    public static function getCount()
    {
        $results = self::getPDO()->query (
            'SELECT COUNT(*) FROM users'
        );

        return $results->fetchColumn();
    }

	public static function getByIdOrFall($id)
	{
		$user = self::getById($id);
		if (empty($user))
		{
			throw new \Exception(
				sprintf(
					'Пользователь с id = "%s" не найден в БД',
					$id
				)
			);
		}
		return $user;
	}

    // todo используем этот
    public static function getNew1(int $limit = 100, $id_first = null, $part_email = '')
    {
        $conditions = ["1=1"];
        $params = [];

        if (!empty($part_email)) {
            $conditions[] = "email LIKE :part_email";
            $params['part_email'] = '%' . $part_email . '%';
        }

        if (!empty($id_first)) {
            $conditions[] = "id <= :id_first";
            $params['id_first'] = $id_first;
        }

        $sql = sprintf(
            "SELECT *
			FROM users 
			WHERE %s 
			ORDER BY id DESC 
			LIMIT %d",
            implode(' AND ', $conditions),
            $limit
        );

        $results = self::getPDO()->prepare($sql);
        $results->execute($params);

        return $results->fetchAll(
            \PDO::FETCH_CLASS,
            User::class
        );
    }


	// fixme удаляем, так не пойдет
    public static function getNew2($part_email, int $limit = 100, $id_first = null)
    {
        if (!empty($id_first) && !empty($part_email))
		{
            $sql = sprintf(
                "SELECT * 
				FROM users
				WHERE 
						email LIKE '%s'
				    AND	%s
				ORDER BY id DESC 
				LIMIT %s",
                '%' . $part_email . '%',
                ! empty($id_first) ? 'id <='.$id_first.'' : '1=1',
                (int) $limit
            );

        } elseif (!empty($id_first) && empty($part_email))
        {
            $sql = sprintf(
                "SELECT * 
				FROM users
				WHERE %s
				ORDER BY id DESC
				LIMIT %s",
                ! empty($id_first) ? 'id <='.$id_first.'' : '1=1',
                (int) $limit
            );

        } elseif (empty($id_first) && !empty($part_email))
        {
            $sql = sprintf(
                "SELECT * 
				FROM users
				WHERE 
					email LIKE '%s'
				ORDER BY id DESC
				LIMIT %d",
                 '%' .$part_email. '%',
                (int) $limit
            );

        } else {
            $sql = sprintf(
                "SELECT * 
				FROM users
				ORDER BY id DESC
				LIMIT %s",
                (int) $limit
            );
        }

        $results = self::getPDO()->prepare($sql);

        $results->execute();

        return $results->fetchAll(
            \PDO::FETCH_CLASS,
            User::class
        );
    }

	/**
	 * @return User[]
	 */
    public static function getNotActivated()
    {
        $results = self::getPDO()->query (
            'SELECT * 
			FROM users 
			WHERE activation_code IS NOT NULL'
        );

        return $results->fetchAll(
            \PDO::FETCH_CLASS,
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
            'SELECT * FROM users WHERE email = :value  LIMIT 1'
        );

		$results->execute([
            'value' => $email
        ]);

        return $results->fetchObject(User::class);
    }

    public static function getByEmailOrFall($email)
    {
        $user = self::getByEmail($email);
        if ( ! $user) {
            throw new \DomainException('Пользователь с такими данными не найден');
        }

        return $user;
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

        return ! empty($results->fetchColumn());
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
                    (login, hash, email, activation_code, token, country, ip, city) 
                VALUES 
                    (:login, :hash, :email,:activation_code, null, :country, :ip, :city)'
		);

		$prepare->execute([
			'login' => $user->getLogin(),
			'hash' => self::getPrivatePropValueByUser($user, User::NAME_HASH),
			'email' => $user->getEmail(),
            'activation_code' => $user->getActivationCode(),
            'country' => $user->getCountry(),
            'ip' => $user->getIP(),
            'city' => $user->getCity()
		]);

		return self::getPDO()->lastInsertId();
	}

    public static function save(User $user)
    {
        $user_from_db = self::getById($user->getId()) ?? null;

        if ($user->getId() && empty($user_from_db)) {
            throw new \Exception(
                sprintf(
                    'Пользователь с id = "%s" не найден в БД',
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
                        login = :login,
                        hash = :hash, 
                        email = :email, 
                        token = :token,
                        activation_code = :activation_code,
                        created_at = :created_at,
                        last_login_at = :last_login_at,
                        pass_change_code = :pass_change_code,
                        pass_change_code_at = :pass_change_code_at,
                        country = :country,
                        ip = :ip,
                        city = :city
                    WHERE 
                        id = :id'
        );

        $prepare->execute([
            'id'                        => self::getPrivatePropValueByUser($user, User::NAME_ID),
            'login'                     => self::getPrivatePropValueByUser($user, User::NAME_LOGIN),
            'hash'                      => self::getPrivatePropValueByUser($user, User::NAME_HASH),
            'email'                     => self::getPrivatePropValueByUser($user, User::NAME_EMAIL),
            'token'                     => self::getPrivatePropValueByUser($user, User::NAME_TOKEN),
            'activation_code'           => self::getPrivatePropValueByUser($user, User::NAME_ACTIVATION_CODE),
            'created_at'                => self::getPrivatePropValueByUser($user, User::NAME_CREATED_AT),
            'last_login_at'             => self::getPrivatePropValueByUser($user, User::NAME_LAST_LOGIN_AT),
            'pass_change_code'          => self::getPrivatePropValueByUser($user, User::NAME_PASS_CHANGE_CODE),
            'pass_change_code_at'       => self::getPrivatePropValueByUser($user, User::NAME_PASS_CHANGE_CODE_AT),
            'country'                   => self::getPrivatePropValueByUser($user, User::NAME_COUNTRY),
            'ip'                        => self::getPrivatePropValueByUser($user, User::NAME_IP),
            'city'                      => self::getPrivatePropValueByUser($user, User::NAME_CITY)
        ]);
    }

	private static function getPrivatePropValueByUser(User $user, string $prop_name)
	{
		$ref_user = new \ReflectionClass($user);

		$prop = $ref_user->getProperty($prop_name);

		$prop->setAccessible(true);

		return $prop->getValue($user);
	}


    public static function deleteById($id)
    {
        self::getPDO()->query (
            'DELETE 
			FROM users 
			WHERE id ='. (int) $id
        );
    }
}