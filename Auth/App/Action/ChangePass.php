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
        if ($_POST)
        {
            $pass_post = $_POST[self::POST_NAME_PASS];
            $pass_confirm_post = $_POST[self::POST_NAME_PASSWORD_CONFIRM];

            try {
                if ($pass_post != $pass_confirm_post) {
                    $errors[Error::LIST_PASS_ERROR][Error::PASS_ERROR] = 'Пароли не совпадают';
                    throw new \Exception(json_encode($errors));
                }

                $user = Users::getByLoginOrEmail($email);

                $user->resetPassChangeCode();

                Auth::logonByPassword($email, $pass_post);

                Response::redirect('/');

            } catch (\Exception $exception){
                $errors = json_decode($exception->getMessage(),true);
            }
        }

        if ($email && $code)
        {
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

            if ($user->getPassChangeCode() == $code)
            {
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
    }
}