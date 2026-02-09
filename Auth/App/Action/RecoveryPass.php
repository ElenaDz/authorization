<?php

namespace Auth\App\Action;

use Auth\App\Entity\User;
use Auth\APP\Helper\Email;
use Auth\App\Model\Users;
use Auth\Sys\Views;

class RecoveryPass extends _Base
{
    const POST_NAME_EMAIL = 'email';

    public function __invoke($email = null, $code = null)
    {
        $content = Views::get(
            __DIR__ . '/../View/RecoveryPass.php',
            [

            ]
        );

        self::showLayout(
            'Востановление пароля',
            $content
        );

        if ($_POST[self::POST_NAME_EMAIL]) {

            $email_post = $_POST[self::POST_NAME_EMAIL];
            if (! Users::hasUserByEmail($email_post)){
                throw new \Exception(
                    sprintf(
                        'Пользователь с email = "%s" не найден в базе данных',
                        $email_post
                    )
                );
            }

            $user = Users::getByLoginOrEmail($email_post);

            $user->genChangePassCode();

            Users::setChangePassCode($user->getId(), $user->getChangePassCode());

            $activation_link = $_SERVER['HTTP_ORIGIN'] . RecoveryPass::getUrl([
                    'email' => $email_post,
                    'code' => $user->getChangePassCode()
                ]);

            $login = $user->getLogin();

            Email::send(
                "Востановление пароля",
                "Здравствуйте, $login!
                        Вы или кто-то другой запросили новый пароль на сайте
                        drivemusic.me.
                        Для смены пароля, пожалуйста, перейдите по ссылке <a href=$activation_link>сменить пароль</a>
                        С уважением,
                        Команда drivemusic",
                $email
            );

            $content = Views::get(
                DIR . '/../View/Block/RecoveryPass/RecoverySuccess.php',
                [
                    'test' => ''
                ]
            );

            self::showLayout(
                'Востановление пароля',
                $content
            );
        }

    }
}