<?php

namespace Auth\App\Action;

use Auth\APP\Helper\Url;
use Auth\App\Model\Users;
use Auth\App\Service\Auth;
use Auth\Sys\Response;
use Auth\Sys\Views;
use DateTime;

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

        $user = Users::getByLoginOrEmailOrFall($email);

        $user->validPassChangeCode($code);

	    // fixme это не здесь должно быть а в методе validPassChangeCode
	    // fixme имена переменных ниже (нотация)
        $codeTime = new DateTime($user->getPassChangeCodeAt());
        $now = new DateTime();

        $diffInSeconds = abs($now->getTimestamp() - $codeTime->getTimestamp());

        if ($diffInSeconds >= 300)
        {
            throw new \Exception(
                'Истёк срок действия ссылки для смены пароля. Запросите ссылку для востановления пароля ещё раз.'
            );
        }

        if ($_POST)
        {
            $pass_post = $_POST[self::POST_NAME_PASS];
            $pass_confirm_post = $_POST[self::POST_NAME_PASSWORD_CONFIRM];

            $change_pass_link = Url::getUrlAbsolute(
                ChangePass::getUrl([
                    ChangePass::POST_NAME_EMAIL => $email,
                    ChangePass::POST_NAME_CODE => $user->getPassChangeCode()
                ])
            );

            if ($pass_post != $pass_confirm_post) {
                $errors[self::POST_NAME_PASS] = 'Пароли не совпадают';
            }

			// fixme оптимизировать код
	        // fixme в этом контролере 3 раза получают шаблон ChangePass из за этого код совершенно не понятный,
	        //  это дублирование кода которое мы всячески стараемся избегать, избавься от всех вызовов кроме самого последнего
            if ( ! empty($errors) && count($errors) > 0)
            {
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

                return;
            }

            try {
                $user->setPass($pass_post);

            } catch (\DomainException $exception )
            {
                $errors[self::POST_NAME_PASS] = $exception->getMessage();

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

                return;
            }

            $user->resetPassChangeCode();

            $user->save();

            Response::redirect(
				// fixme замени магическую строку login на константу смотри пример ниже
	            /** @see \Auth\App\Action\ActivationUser::PARAM_NAME_LOGIN */
				Logon::getUrl(['login' => $user->getLogin()])
            );

            return;
        }

        $change_pass_link = ChangePass::getUrl([
            ChangePass::POST_NAME_EMAIL => $email,
            ChangePass::POST_NAME_CODE => $code
        ]);

        $content = Views::get(
            __DIR__ . '/../View/ChangePass.php',
            [
                'email'  => $email,
                'change_pass_link'  => $change_pass_link
            ]
        );

        self::showLayout(
            'Смена пароля',
            $content
        );
    }
}