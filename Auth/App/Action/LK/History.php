<?php

namespace Auth\App\Action\LK;

use Auth\Sys\Views;

// fixme удалить
class History extends _BaseLK
{
    const LIMIT = 10;
    public function __invoke($user_id = null)
    {
        if ($user_id) {
            $song_ids = \Auth\App\Model\History::getSongIdsByUserId($user_id, self::LIMIT);
        }

        $content = Views::get(
            __DIR__ . '/../../View/Block/LK/History.php',
            [
                'user_id' => $user_id,
                'song_ids' => $song_ids
            ]
        );

        self::showLayout(
            'Моя история',
            $content
        );
    }
}