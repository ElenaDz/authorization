<?php
namespace Auth\App\Action;

use Auth\App\Entity\User;
use Auth\App\Model\Users;
use Auth\App\Service\Auth;

class TestBox extends _Base
{
	public function __invoke()
	{
		foreach (range(1,10) as $i)
		{
			$login = 'user_'.rand(1, 1000);
			$pass = 'Qw123!';
			$email =  $login.'@mail.ru';

			$user = User::create($login, $pass, $email);

			var_dump(Users::add($user));
		}

	}
}