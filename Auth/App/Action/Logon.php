<?php
namespace Auth\App\Action;

use Auth\App\Exception\UserNoActivationDomainException;
use Auth\App\Service\Auth;
use Auth\Sys\Request;
use Auth\Sys\Response;
use \Auth\Sys\Views;

class Logon extends _Base
{
    const POST_NAME_EMAIL = 'email';
    const POST_NAME_PASS = 'password';
    const POST_NAME_SUBMIT = 'submit';

    public function __invoke($email = null)
	{
		if (Auth::isAuthorized()) {
            Response::redirect('/');
        }

        $errors = [];

        if ( ! empty($_POST) && $_POST[self::POST_NAME_EMAIL])
        {
            $email = $_POST[self::POST_NAME_EMAIL];
            $pass = $_POST[self::POST_NAME_PASS];

            try {
                Auth::logonByPassword($email, $pass);

                if (Request::isAjax()) {
                    http_response_code(201);

                } else {
                    Response::redirect('/');
                }

            } catch (UserNoActivationDomainException $exception) {
                $content = Views::get(
                    __DIR__ . '/../View/Block/Logon/LogonNoActivation.php'
                );

                self::showLayout(
                    'Ошибка входа',
                    $content
                );

                exit();

            } catch (\DomainException $exception) {
                $errors[self::POST_NAME_SUBMIT] = $exception->getMessage();
            }
        }

		$content = Views::get(
			__DIR__ . '/../View/Logon.php',
			[
                'errors' => $errors,
                'email' => $email ?? null
			]
		);

		self::showLayout(
			'Вход',
			$content
		);
	}

	public static function getUrl(array $params = []): string
	{
		return parent::getUrl($params);
	}
}