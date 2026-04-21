<?php
declare(strict_types=1);

namespace Auth\App\Action\Admin;

use Auth\App\Entity\User;
use Auth\App\Model\Users;
use Auth\Sys\Routing;

class GeneratorFakeUsers extends _BaseAdmin
{
	public function __invoke()
	{
		foreach (range(1,10) as $num)
		{
			$login = "user_${num}_".rand(1,999);
			$pass = 'Qw123!';
			$email =  $login.'@mail.ru';

			$user = User::create($login, $pass, $email);

			var_dump(Users::add($user));
		}
	}

	public static function getUrl(array $params = []): string
	{
		return Routing::getUrl(static::class, $params);
	}
}