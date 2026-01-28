<?php
namespace Auth\App\Action;

use Auth\Sys\Views;

class Reg extends _Base
{
	// fixme login = username? нет должно быть одинаковое имя ok
    const POST_NAME_LOGIN = 'login';
	// fixme забыла добавить name ok
    const POST_NAME_EMAIL = 'email';
    const POST_NAME_PASS = 'password';
    const POST_NAME_PASSWORD_CONFIRM = 'password_confirm';


    /**
     * @throws \Exception
     */
    public function __invoke()
	{
        $errors = [];

        if ($_POST[self::POST_NAME_LOGIN])
        {
            $login = $_POST[self::POST_NAME_LOGIN];
            $pass = $_POST[self::POST_NAME_PASS];
            $pass_confirm = $_POST[self::POST_NAME_PASSWORD_CONFIRM];
            $email = $_POST[self::POST_NAME_EMAIL];

            try {
                \Auth\APP\Entity\User::create($login, $pass, $pass_confirm, $email);

            } catch (\Exception $exception){
                var_dump($exception->getMessage());
            }
        }

        $content = Views::get(
            __DIR__ . '/../View/Reg.php',
            [
                'login' => self::POST_NAME_LOGIN,
                'pass' => self::POST_NAME_PASS,
                'pass_confirm' => self::POST_NAME_PASSWORD_CONFIRM,
                'email' => self::POST_NAME_EMAIL
            ]
        );

        self::showLayout(
            'Регистрация',
            $content
        );
	}
}