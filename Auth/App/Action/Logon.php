<?php
namespace Auth\App\Action;

use \Auth\Sys\Views;

class Logon extends _Base
{
    const POST_NAME_LOGIN = 'login';
    const POST_NAME_PASS = 'pass';

    public function __invoke($param_optional = null)
	{

		$content = Views::get(
			__DIR__ . '/../View/Logon.php',
			[
				'test' => '',

			]
		);

		self::showLayout(
			'Вход',
			$content
		);
	}
}