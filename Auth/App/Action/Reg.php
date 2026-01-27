<?php
namespace Auth\App\Action;

use Auth\Sys\Views;

class Reg extends _Base
{
	// fixme login = username? нет должно быть одинаковое имя
    const POST_NAME_LOGIN = 'username';
	// fixme забыла добавить name
    const POST_EMAIL = 'email';
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
            $email = $_POST[self::POST_EMAIL];

            try {
                \Auth\APP\Model\Users::add($login, $pass, $pass_confirm, $email);

            } catch (\Exception $exception){
                var_dump($exception->getMessage());
            }
        }

        $content = Views::get(
            __DIR__ . '/../View/Reg.php',
            [
                'test' => 'ok'
            ]
        );

        self::showLayout(
            'Регистрация',
            $content
        );
	}
}