<?php

namespace Auth\App\Action;

use Auth\APP\Helper\Email;
use Auth\APP\Helper\Url;
use Auth\App\Model\Users;
use Auth\Sys\Views;

class RecoveryPass extends _Base
{
    const POST_NAME_EMAIL = 'email';
    const POST_NAME_CODE = 'code';

    public function __invoke()
    {
        if (empty($_POST))
        {
            $content = Views::get(
                __DIR__ . '/../View/RecoveryPass.php'
            );

            self::showLayout(
                'Восстановление пароля',
                $content
            );
        }

        if ($_POST[self::POST_NAME_EMAIL])
		{
            $email_post = $_POST[self::POST_NAME_EMAIL];

            if (! Users::hasByEmail($email_post)){
                $content = Views::get(
                    __DIR__ . '/../View/Block/RecoveryPass/RecoveryError.php'
                );

                self::showLayout(
                    'Электронная почта не найдена',
                    $content
                );
            }

            $user = Users::getByLoginOrEmail($email_post);

            $user->genPassChangeCode();

			// fixme не правильное название столбца в БД это не время смены пароля а времся генерации кода смены пароля ok
            $user->save();

            $activation_link = Url::getUrlAbsolute(
				ChangePass::getUrl([
                    ChangePass::POST_NAME_EMAIL => $email_post,
                    ChangePass::POST_NAME_CODE => $user->getPassChangeCode()
                ])
            );

            $login = $user->getLogin();

            $message = Views::get(
                __DIR__ . '/../View/Block/EmailMessage/RecoveryPass.php',
                [
                    'login' => $login,
                    'activation_link' => $activation_link,
                ]
            );

            Email::send(
                "Восстановление пароля",
	            // fixme заказчик просил для каждого письма создавать отдельный шаблон так как он будет их модифицировать в html ok
                //  используй слово email в названии шаблона( использовала в названии директроии)
                // fixme не используй url сайта (drivemusic.me) просто в коде, используй метод возвращающий его из конфигурационного файла Main ок
                // fixme тоже самое катается названия сайта (drivemusic) ok
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
        }
    }
}