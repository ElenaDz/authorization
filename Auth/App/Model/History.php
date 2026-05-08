<?php

namespace Auth\App\Model;

class History extends _Base
{
    public static function getSongIdsByUserId($user_id, $limit)
    {
        $pdo = self::getPDO();

        $results = $pdo->query(
            'SELECT song_id 
			FROM history 
			WHERE '.
	            \Sql::where([
	                'user_id' => $user_id
	            ]).'  
			LIMIT '. $limit
        );

        return $results->fetchColumn();
    }

    public static function add($song_id, $user_id)
    {
		// fixme где проверка что такой записи уже нету?

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