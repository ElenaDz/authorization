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

		// fixme использовать sql::where для where ок
        $sql = "DELETE FROM History 
	        WHERE " . \Sql::where(['user_id' => $user_id]) . " 
	        AND song_id NOT IN (
	            SELECT song_id FROM (
	                SELECT song_id FROM History 
	                WHERE " . \Sql::where(['user_id' => $user_id]) . " 
	                ORDER BY id DESC 
	                LIMIT ". (int)$limit ."
	            ) tmp
	        )
		";

        $results = $pdo->query($sql);

        $results->execute();
    }

    public static function repeatCheck($song_id, $user_id)
    {
        $pdo = self::getPDO();

        // todo вынести в отдельный метод ok
        $check = $pdo->query(
            'SELECT 1 
			FROM History
			WHERE '.
            \Sql::where([
                'user_id' => $user_id,
                'song_id' => $song_id
            ])
        );

        return $check->fetch();
    }

    public static function delete($song_id, $user_id)
    {
        $pdo = self::getPDO();

        $results = $pdo->query(
            'DELETE
			FROM History
			WHERE '.
            \Sql::where([
                'user_id' => $user_id,
                'song_id' => $song_id
            ])
        );

        $results->execute();
    }

    public static function add($song_id, $user_id)
    {
		// fixme это нужно делать после а не до добавления ок
		// todo вынести в отдельный метод ok

        if (self::repeatCheck($song_id, $user_id)) self::delete($song_id, $user_id);

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

        if (rand(1, 10) == 1) {
            self::clean($user_id);
        }
    }
}