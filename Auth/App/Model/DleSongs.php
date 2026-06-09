<?php

namespace Auth\App\Model;

use Auth\App\Entity\DleSong;

class DleSongs extends _Base
{
    public static function getSongsByDleSongIds(DleSongIds)
    {
        $pdo = self::getPDO();

        $results = $pdo->query();

        return $results->fetchAll(
            \PDO::FETCH_CLASS,
            DleSong::class
        );
    }
}