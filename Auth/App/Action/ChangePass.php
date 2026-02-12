<?php

namespace Auth\App\Action;

use Auth\App\Enum\Error;
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
        // fixme эта проверка должна быть в самом начале ok
        if (empty($email)) {
            throw new \Exception('Нет email');

        } elseif (empty($code)) {
            throw new \Exception('Нет кода смены пароля');
        }

        if ($_POST)
        {
            $pass_post = $_POST[self::POST_NAME_PASS];
            $pass_confirm_post = $_POST[self::POST_NAME_PASSWORD_CONFIRM];

            if ($pass_post != $pass_confirm_post) {
                throw new \Exception('Пароли не совпадают');
            }

            try {
                $user = Users::getByLoginOrEmail($email);

                $user->resetPassChangeCode();

                Auth::logonByPassword($email, $pass_post);

                Response::redirect('/');

            } catch (\Exception $exception){
                $errors = json_decode($exception->getMessage(),true);
            }
            return;
        }

        $user = Users::getByLoginOrEmail($email);

        if (empty($user))
        {
            throw new \Exception(
                sprintf(
                    'Пользователь с email = "%s" не найден',
                    $email
                )
            );
        }

		// fixme нарушила принцип что основной код контролера не должен быть во вложении ok
        if ($user->getPassChangeCode() !== $code) {
            throw new \Exception('Код не совпадает с кодом пользователя');
        }

        $content = Views::get(
            __DIR__ . '/../View/ChangePass.php',
            [
                'email'  => $email
            ]
        );

        self::showLayout(
            'Смена пароля',
            $content
        );

    }
}