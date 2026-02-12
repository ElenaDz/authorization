<?php
namespace Auth\App\Action;

use Auth\App\Service\Auth;
use Auth\Sys\Response;

class Logout extends _Base
{
	public function __invoke()
	{
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            throw new \Exception('Только для POST запросов');
        }

        if (Auth::isAuthorized()) {
            Auth::logout();
        }

        Response::redirect('/');
	}
}