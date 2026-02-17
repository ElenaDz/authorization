<?php

namespace Auth\App\Action;

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

        if ($user->getPassChangeCode() !== $code) {
            throw new \Exception('Код не совпадает с кодом пользователя');
        }

        if ($_POST)
        {
            $pass_post = $_POST[self::POST_NAME_PASS];
            $pass_confirm_post = $_POST[self::POST_NAME_PASSWORD_CONFIRM];

            if ($pass_post != $pass_confirm_post) {
                throw new \Exception('Пароли не совпадают');
            }

            $user->resetPassChangeCode();

			// надо подумать над бизнес логикой
            $user->setPass($pass_post);

            Auth::loginUser($user);

            Response::redirect('/');

            return;
        }

		// fixme для получения url у нас есть метод getUrl, смотри например как получается url для активации, тут так же
	    /** @see \Auth\App\Action\ActivationUser::getUrl */
        $activation_link = self::getUrl().'&'. self::POST_NAME_EMAIL. '='. $email.'&' . self::POST_NAME_CODE. '='. $code;

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