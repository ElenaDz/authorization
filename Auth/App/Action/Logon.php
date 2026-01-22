<?php
namespace Auth\App\Action;

use \Auth\Sys\Views;

class Logon extends _Base
{
	public function __invoke()
	{
		$content = Views::get(
			__DIR__.'/../View/Logon.php',
			[
				'test' => 'ok'
			]
		);

		self::showLayout(
			'Вход',
			$content
		);
	}
}