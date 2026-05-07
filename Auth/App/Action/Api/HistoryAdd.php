<?php
declare(strict_types=1);

namespace Auth\App\Action\Api;

use Auth\App\Model\History;
use Auth\App\Service\Auth;
use Auth\Sys\Response;

class HistoryAdd extends _BaseApi
{
    const POST_NAME_SONG_ID = 'song_id';
	// todo
	public function __invoke()
	{
        if ($_POST) {
            $song_id = $_POST[self::POST_NAME_SONG_ID];

            if ($song_id) {

                $user = Auth::getUser();

                History::add($song_id, $user->getId());

                Response::redirect('/');
            }
        }
	}
}