<?php

namespace Auth\App\Action;

use Auth\APP\Helper\Url;
use Auth\App\Model\Users;
use Auth\App\Service\Auth;
use Auth\Sys\Response;
use Auth\Sys\Views;

class ChangePass  extends _Base
{
    const POST_NAME_EMAIL = 'email';
    const POST_NAME_CODE = 'code';
    const POST_NAME_PASS = 'password';
    const POST_NAME_PASSWORD_CONFIRM = 'password_confirm';


    public function __invoke($email = null, $code = null)
    {
        if (empty($email)) {
            throw new \Exception('Нет email');

        } elseif (empty($code)) {
            throw new \Exception('Нет кода смены пароля');
        }

        try {
            $user = Users::getByLoginOrEmailOrFall($email);

        } catch (\Exception $exception ) {
            throw new \Exception($exception->getMessage());
        }

		// todo завести метод validPassChangeCode
        if ($user->getPassChangeCode() !== $code) {
            throw new \Exception('Не валидный код смены пароля');
        }

	    // todo добавить проверку что с момента создания кода смены пароля прошло не больше 5 минут

        if ($_POST)
        {
            $pass_post = $_POST[self::POST_NAME_PASS];
            $pass_confirm_post = $_POST[self::POST_NAME_PASSWORD_CONFIRM];

			// todo эту ошибка должна показываться рядом с паролем
            if ($pass_post != $pass_confirm_post) {
                throw new \DomainException('Пароли не совпадают');
            }

	        // fixme имя переменной не правильное
            $activation_link = Url::getUrlAbsolute(
                ChangePass::getUrl([
                    ChangePass::POST_NAME_EMAIL => $email,
                    ChangePass::POST_NAME_CODE => $user->getPassChangeCode()
                ])
            );

            try {
                $user->setPass($pass_post);

            } catch (\DomainException $exception )
            {
                $errors[self::POST_NAME_PASS] = $exception->getMessage();

                $content = Views::get(
                    __DIR__ . '/../View/ChangePass.php',
                    [
                        'email'  => $email,
                        'activation_link'  => $activation_link,
                        'errors' => $errors
                    ]
                );

                self::showLayout(
                    'Смена пароля',
                    $content
                );

                return;
            }

            $user->resetPassChangeCode();

			// todo после смены пароля нельзя делать автоматическую авторизацию, ни на одном сайте такого нет,
	        //  после смены пароля происходит редирект на форму ввода пароля (логина) и нужно вводить пароль
            Auth::loginUser($user);

            Response::redirect('/');

            return;
        }

		// fixme имя переменной не правильное
        $activation_link = ChangePass::getUrl([
            ChangePass::POST_NAME_EMAIL => $email,
            ChangePass::POST_NAME_CODE => $code
        ]);

        $content = Views::get(
            __DIR__ . '/../View/ChangePass.php',
            [
                'email'  => $email,
                'activation_link'  => $activation_link
            ]
        );

        self::showLayout(
            'Смена пароля',
            $content
        );

    }
}