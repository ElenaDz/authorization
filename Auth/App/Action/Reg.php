<?php
namespace Auth\App\Action;

use Auth\Sys\Views;

class Reg extends _Base
{
    const POST_NAME_USER = 'username';
    const POST_EMAIL = 'email';
    const POST_NAME_PASS = 'password';
    const POST_NAME_PASSWORD_CONFIRM = 'password_confirm';


	public function __invoke()
	{
		// todo 3 обработка присланной формы регистрации

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