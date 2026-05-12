<?php

namespace Auth\App\Model;

class History extends _Base
{
    public static function getSongIdsByUserId($user_id, $limit = 50)
    {
        $pdo = self::getPDO();

        $results = $pdo->query(
            'SELECT song_id 
			FROM History
			WHERE '.
	            \Sql::where([
	                'user_id' => $user_id
	            ]).'  
	        ORDER BY id DESC 
			LIMIT '. (int)$limit
        );

        return $results->fetchAll(\PDO::FETCH_COLUMN);
    }

    public static function clean($user_id, $limit = 50)
    {
        $pdo = self::getPDO();

        $sql = "DELETE FROM History 
        WHERE " . \Sql::where(['user_id' => $user_id]) . " 
        AND song_id NOT IN (
            SELECT song_id FROM (
                SELECT song_id FROM History 
                WHERE user_id = " . (int)$user_id . " 
                ORDER BY id DESC 
                LIMIT ". (int)$limit ."
            ) tmp
        )";

        $results = $pdo->query($sql);

        $results->execute();
    }


    public static function add($song_id, $user_id)
    {
        if (rand(1, 10) == 1) {
            self::clean($user_id);
        }

        $pdo = self::getPDO();

        $check = $pdo->query(
            'SELECT 1 
			FROM History
			WHERE '.
            \Sql::where([
                'user_id' => $user_id,
                'song_id' => $song_id
            ])
        );

        if ($check->fetch()) {
            return;
        }

		// fixme где проверка что такой записи уже нету? ok

        $prepare = self::getPDO()->prepare(
            'INSERT INTO 
                     history
                    (song_id, user_id) 
                VALUES 
                    (:song_id, :user_id)'
        );

        $prepare->execute([
            'song_id' => $song_id,
            'user_id' => $user_id
        ]);
    }
}