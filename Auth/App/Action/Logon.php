<?php
namespace Auth\App\Action;

use \Auth\Sys\Views;

class Logon extends _Base
{
	public function __invoke($param_optional = null)
	{

		$content = Views::get(
			__DIR__ . '/../View/Logon.php',
			[
				'test' => $param_optional ?? 'ok'
			]
		);

		self::showLayout(
			'Вход',
			$content
		);
	}
}