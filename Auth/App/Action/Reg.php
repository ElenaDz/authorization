<?php
namespace Auth\App\Action;

use Auth\App\Helper\Email;
use Auth\App\Entity\User;
use Auth\App\Helper\Url;
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

			// fixme попытался зарегистрировать не получилось, в консоле разработчика вижу эту ошибку
	        //  проблема в том что не может определится страна по понятным причинам ведь у меня  ip адрес локалхоста
	        //  а БД ругается что страна не может быть NULL
            if (empty($id)) {
	            // todo сейчас у нас ошибки сервера показываются только в console, обычно они показываются как всплывающие
	            //  сообщения типа alert, только не браузерный, а кастомный сделанный на js с помощью какой то библиотеки,
	            //  Узнай у закащика как нужно показывать серверные ошибки, то то пользователь будет кликать и ни чего
	            //  не происходит а пользователь в консоль не пойдет
                throw new \Exception('Пользователь не добавлен');
            }

            $activation_link = Url::getUrlAbsolute(
                ActivationUser::getUrl([
                    ActivationUser::GET_NAME_EMAIL => $email,
                    ActivationUser::GET_NAME_CODE => $user->getActivationCode()
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

	public static function getUrl(array $params = []): string
	{
		return parent::getUrl($params);
	}
}