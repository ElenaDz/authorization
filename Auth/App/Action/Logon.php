<?php
namespace Auth\App\Action;

use Auth\App\Entity\User;
use Auth\App\Service\Auth;
use Auth\Sys\Response;
use \Auth\Sys\Views;

class Logon extends _Base
{
    const POST_NAME_LOGIN = 'login';
    const POST_NAME_PASS = 'password';


    public function __invoke($param_optional = null)
	{
        if (Auth::isAuthorized()) {
            Response::redirect('/');
        }

        $errors = [];

        if ($_POST[self::POST_NAME_LOGIN])
        {
            $login = $_POST[self::POST_NAME_LOGIN];
            $pass = $_POST[self::POST_NAME_PASS];

            try {
                Auth::logonByPassword($login, $pass);

                Response::redirect('/');

            } catch (\DomainException $exception) {
				// fixme например "не правильный пароль" это не ошибка логина, это ошибка пароля
                $errors[self::POST_NAME_LOGIN] = $exception->getMessage();
            }
        }

		$content = Views::get(
			__DIR__ . '/../View/Logon.php',
			[
                'errors' => $errors
			]
		);

		self::showLayout(
			'Вход',
			$content
		);
	}
}