<?php

namespace Auth\App\Action;

use Auth\APP\Helper\Email;
use Auth\APP\Helper\Url;
use Auth\App\Model\Users;
use Auth\Sys\Views;

class RecoveryPass extends _Base
{
    const POST_NAME_EMAIL = 'email';
    const POST_NAME_EMAIL_ERROR = 'email_error';

    public function __invoke($email_error = null)
    {
        if ( ! empty($_POST) && $_POST[self::POST_NAME_EMAIL])
		{
            $email_post = htmlspecialchars($_POST[self::POST_NAME_EMAIL]);

            if ( ! Users::hasByEmail($email_post))
			{
                $content = Views::get(
                    __DIR__ . '/../View/Block/RecoveryPass/RecoveryError.php',
                    [
                        'email_error' => $email_post
                    ]
                );

                self::showLayout(
                    'Электронная почта не найдена',
                    $content
                );

                return;
            }

            $user = Users::getByLoginOrEmailOrFall($email_post);

            $user->genPassChangeCode();

            $user->save();

            $activation_link = Url::getUrlAbsolute(
				ChangePass::getUrl([
                    ChangePass::POST_NAME_EMAIL => $email_post,
                    ChangePass::POST_NAME_CODE => $user->getPassChangeCode()
                ])
            );

            $login = $user->getLogin();

            $message = Views::get(
                __DIR__ . '/../View/Email/RecoveryPass.php',
                [
                    'login' => $login,
                    'activation_link' => $activation_link,
                ]
            );

            Email::send(
                "Восстановление пароля",
                $message,
                $email_post
            );

            $content = Views::get(
                __DIR__ . '/../View/Block/RecoveryPass/RecoverySuccess.php'
            );

            self::showLayout(
                'Восстановление пароля',
                $content
            );

            return;
        }

        $content = Views::get(
            __DIR__ . '/../View/RecoveryPass.php',
            [
                'email_error' => $email_error
            ]
        );

        self::showLayout(
            'Восстановление пароля',
            $content
        );
    }
}