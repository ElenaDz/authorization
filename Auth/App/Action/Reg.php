<?php
namespace Auth\App\Action;

class Reg extends _Base
{
    const POST_NAME_LOGIN = 'login';
    const POST_NAME_PASS = 'pass';


	public function __invoke()
	{
		// todo 3 обработка присланной формы регистрации

        $errors = [];

        var_dump($_POST);

        if ($_POST[self::POST_NAME_LOGIN])
        {
			$login = $_POST[self::POST_NAME_LOGIN];
			$pass = $_POST[self::POST_NAME_PASS];

			var_dump();
        }
	}
}