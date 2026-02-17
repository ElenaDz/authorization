<?php
namespace Auth\App\Action;

use Auth\APP\Helper\Email;

class TestBox extends _Base
{
	public function __invoke()
	{
		var_dump('ok');
	}
}