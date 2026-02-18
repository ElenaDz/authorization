<?php
namespace Auth\App\Action;

use Auth\App\Service\Auth;
use Auth\Sys\Response;
use \Auth\Sys\Views;

class Logon extends _Base
{
    const POST_NAME_LOGIN = 'login';
    const POST_NAME_PASS = 'password';
    const POST_NAME_SUBMIT = 'submit';


    public function __invoke($login = null)
	{
        if (Auth::isAuthorized()) {
            Response::redirect('/');
        }

        $errors = [];

        if ( ! empty($_POST) && $_POST[self::POST_NAME_LOGIN])
        {
            $login = $_POST[self::POST_NAME_LOGIN];
            $pass = $_POST[self::POST_NAME_PASS];

            try {
                Auth::logonByPassword($login, $pass);

                Response::redirect('/');

            } catch (\DomainException $exception) {
                $errors[self::POST_NAME_SUBMIT] = $exception->getMessage();
            }
        }

		$content = Views::get(
			__DIR__ . '/../View/Logon.php',
			[
                'errors' => $errors,
                'login' => $_POST[self::POST_NAME_LOGIN] ?? $login ?? null
			]
		);

		self::showLayout(
			'Вход',
			$content
		);
	}
}