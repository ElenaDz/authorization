<?php

namespace Auth\App\Action\LK;

use Auth\App\Action\_Base;
use Auth\App\Model\DleSongs;
use Auth\Sys\Views;

class History extends _Base
{
    public function __invoke($userId)
    {
        $dleSongIds = \Auth\App\Model\History::getSongIdsByUserId($userId);

        $dleSongs = DleSongs::getSongsByDleSongIds($dleSongIds);

        echo Views::get(
            __DIR__ . '/../../View/Block/ListSongsItem.php',
            [
                'dleSongs' => $dleSongs
            ]
        );
    }

    public static function getUrl(array $params = []): string
    {
        return parent::getUrl($params);
    }
}