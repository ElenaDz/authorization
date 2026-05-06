<?php
declare(strict_types=1);

namespace Auth\App\Action;

use Auth\Sys\Views;

class LK extends _Base
{
	public function __invoke()
	{

		echo Views::get(
			__DIR__.'/../View/LK.php'
		);
	}

	public static function getUrl(array $params = []): string
	{
		return parent::getUrl($params);
	}
}