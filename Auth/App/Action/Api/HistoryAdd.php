<?php
declare(strict_types=1);

namespace Auth\App\Action\Api;

use Auth\App\Model\History;
use Auth\App\Service\Auth;
use Auth\Sys\Response;

class HistoryAdd extends _BaseApi
{
    const POST_NAME_SONG_ID = 'song_id';

	public function __invoke()
	{
        header('Access-Control-Allow-Origin: http://mp3player');
        header('Access-Control-Allow-Credentials: true'); // Разрешает принимать куки
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');


        $song_id = $_POST[self::POST_NAME_SONG_ID];

        $user = Auth::getUser();

        History::add($song_id, $user->getId());

	}
}