<?php
namespace Auth\App\Action;

use Auth\APP\Helper\Email;
use Auth\App\Entity\User;
use Auth\APP\Helper\Url;
use Auth\App\Model\Users;
use Auth\Sys\Views;

class Reg extends _Base
{
    const POST_NAME_LOGIN = 'login';
    const POST_NAME_EMAIL = 'email';
    const POST_NAME_PASS = 'password';
    const POST_NAME_PASSWORD_CONFIRM = 'password_confirm';

    /**
     * @throws \Exception
     */
    public function __invoke()
	{
        $errors = [];

		$login = null;
		$email = null;

        if ( ! empty($_POST) && $_POST[self::POST_NAME_LOGIN])
        {
            $login = $_POST[self::POST_NAME_LOGIN];
            $pass = $_POST[self::POST_NAME_PASS];
            $pass_confirm = $_POST[self::POST_NAME_PASSWORD_CONFIRM];
            $email = $_POST[self::POST_NAME_EMAIL];

            try {
                User::validEmail($email);

            } catch (\DomainException $e){
                $errors[self::POST_NAME_EMAIL] =  $e->getMessage();
            }

            try {
                User::validLogin($login);

            } catch (\DomainException $e) {
                $errors[self::POST_NAME_LOGIN] =  $e->getMessage();
            }

            try {
                User::validPassword($pass);

            } catch (\DomainException $e){
                $errors[self::POST_NAME_PASS] =  $e->getMessage();
            }

            if (Users::hasByLogin($login)) {
                $errors[self::POST_NAME_LOGIN] = 'Пользователь с таким именем уже есть';
            }

			if (Users::hasByEmail($email)) {
                $errors[self::POST_NAME_EMAIL] = 'Пользователь с таким email уже есть';
            }

            if ($pass != $pass_confirm) {
                $errors[self::POST_NAME_PASS] = 'Пароли не совпадают';
            }

            if (count($errors)> 0)
			{
                $content = Views::get(
                    __DIR__ . '/../View/Reg.php',
                    [
                        'errors' => $errors,
                        'login' => $login,
                        'email' => $email,
                    ]
                );

                self::showLayout(
                    'Регистрация',
                    $content
                );
                return;
            }

            $user = User::create($login, $pass, $email);

            $id = Users::add($user);

			// fixme в какой ситуации метод добавления пользователя должен вернуть пустой id ? если есть такая ситуация то нужно бросать исключение
            if ( ! empty($id))
			{
                $activation_link = Url::getUrlAbsolute(
					ActivationUser::getUrl([
						ActivationUser::PARAM_NAME_LOGIN => $login,
                        ActivationUser::PARAM_NAME_CODE => $user->getActivationCode()
                    ])
                );

                $message = Views::get(
                    __DIR__ . '/../View/Email/Reg.php',
                    [
                        'login' => $login,
                        'activation_link' => $activation_link,
                    ]
                );

                Email::send(
                    "Подтверждения электронной почты $email",
                    $message,
                    $email
                );

                $content = Views::get(
                    __DIR__ . '/../View/Block/Reg/RegSuccess.php'
                );

                self::showLayout(
                    'Регистрация',
                    $content
                );

                return;
            }
        }

        $content = Views::get(
            __DIR__ . '/../View/Reg.php',
            [
                'login' => $login,
                'email' => $email,
            ]
        );

        self::showLayout(
            'Регистрация',
            $content
        );
	}
}