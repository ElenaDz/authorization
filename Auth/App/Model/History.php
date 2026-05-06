<?php

namespace Auth\App\Model;

class History extends _Base
{
    public static function getSongIdsByUserId($user_id, $limit)
    {
        $pdo = self::getPDO();

        $results = $pdo->query(
            'SELECT song_id 
			FROM History 
			WHERE '.
            \Sql::where([
                'user_id' => $user_id
            ]).
            '  
			LIMIT '. $limit
        );

        return $results->fetchColumn();
    }

    public static function add($song_id, $user_id)
    {
        $prepare = self::getPDO()->prepare(
            'INSERT INTO 
                     History
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