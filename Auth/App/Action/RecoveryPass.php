<?php

namespace Auth\App\Action;

use Auth\App\Entity\User;
use Auth\APP\Helper\Email;
use Auth\APP\Helper\Url;
use Auth\App\Model\Users;
use Auth\Sys\Views;

class RecoveryPass extends _Base
{
    const POST_NAME_EMAIL = 'email';

    public function __invoke($email = null, $code = null)
    {
        $content = Views::get(
            __DIR__ . '/../View/RecoveryPass.php'
        );

        self::showLayout(
            'Восстановление пароля',
            $content
        );

        if ($_POST[self::POST_NAME_EMAIL])
		{
            $email_post = $_POST[self::POST_NAME_EMAIL];
            if (! Users::hasUserByEmail($email_post)){
                throw new \Exception(
                    sprintf(
                        'Пользователь с email = "%s" не найден',
                        $email_post
                    )
                );
            }

            $user = Users::getByLoginOrEmail($email_post);

            $user->genChangePassCode();

			// fixme не правильное название столбца в БД это не время смены пароля а времся генерации кода смены пароля
            Users::setChangePassCode($user->getId(), $user->getChangePassCode());

            $activation_link = Url::getUrlAbsolute(
				RecoveryPass::getUrl([
                    'email' => $email_post,
                    'code' => $user->getChangePassCode()
                ])
            );

            $login = $user->getLogin();

            Email::send(
                "Восстановление пароля",
	            // fixme заказчик просил для каждого письма создавать отдельный шаблон так как он будет их модифицировать в html
                //  используй слово email в названии шаблона
                // fixme не используй url сайта (drivemusic.me) просто в коде, используй метод возвращающий его из конфигурационного файла Main
                // fixme тоже самое катается названия сайта (drivemusic)
                "Здравствуйте, $login!
                        Вы или кто-то другой запросили новый пароль на сайте
                        drivemusic.me.
                        Для смены пароля, пожалуйста, перейдите по ссылке <a href=$activation_link>сменить пароль</a>
                        С уважением,
                        Команда drivemusic",
                $email
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