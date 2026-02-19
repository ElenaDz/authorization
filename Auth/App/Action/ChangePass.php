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
        if (Auth::isAuthorized()) {
            Response::redirect('/');
        }

        if (empty($email)) {
            throw new \Exception('Нет email');

        } elseif (empty($code)) {
            throw new \Exception('Нет кода смены пароля');
        }

        $errors = [];

        $user = Users::getByLoginOrEmailOrFall($email);

        $user->validPassChangeCode($code);

        $change_pass_link = Url::getUrlAbsolute(
            ChangePass::getUrl([
                ChangePass::POST_NAME_EMAIL => $email,
                ChangePass::POST_NAME_CODE => $code
            ])
        );

	    // fixme это не здесь должно быть а в методе validPassChangeCode ok
	    // fixme имена переменных ниже (нотация) ok

        if ($_POST)
        {
            $pass_post = $_POST[self::POST_NAME_PASS];
            $pass_confirm_post = $_POST[self::POST_NAME_PASSWORD_CONFIRM];

            if ($pass_post != $pass_confirm_post) {
                $errors[self::POST_NAME_PASS] = 'Пароли не совпадают';
            }

	        // fixme в этом контролере 3 раза получают шаблон ChangePass из за этого код совершенно не понятный,
	        //  это дублирование кода которое мы всячески стараемся избегать, избавься от всех вызовов кроме самого последнего ok

            try {
                if (empty($errors)){
                    $user->setPass($pass_post);
                }

            } catch (\DomainException $exception )
            {
                $errors[self::POST_NAME_PASS] = $exception->getMessage();
            }

            if (empty($errors)) {
                $user->resetPassChangeCode();

                $user->save();

                Response::redirect(
                // fixme замени магическую строку login на константу смотри пример ниже ok
                /** @see \Auth\App\Action\ActivationUser::PARAM_NAME_LOGIN */
                    Logon::getUrl([Logon::POST_NAME_LOGIN => $user->getLogin()])
                );
                return;
            }
        }

        $content = Views::get(
            __DIR__ . '/../View/ChangePass.php',
            [
                'email'  => $email,
                'change_pass_link'  => $change_pass_link,
                'errors' => $errors
            ]
        );

        self::showLayout(
            'Смена пароля',
            $content
        );
    }
}