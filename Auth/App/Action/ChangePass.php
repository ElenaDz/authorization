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
    const POST_NAME_PASSWORD = 'password';
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

        if ($_POST)
        {
            $pass = $_POST[self::POST_NAME_PASSWORD];
            $pass_confirm = $_POST[self::POST_NAME_PASSWORD_CONFIRM];

            if ($pass != $pass_confirm) {
                $errors[self::POST_NAME_PASSWORD] = 'Пароли не совпадают';
            }

            try {
                if (empty($errors)) {
                    $user->setPass($pass);
                }

            } catch (\DomainException $exception )
            {
                $errors[self::POST_NAME_PASSWORD] = $exception->getMessage();
            }

            if (empty($errors))
			{
                $user->resetPassChangeCode();

                $user->save();

                Response::redirect(
	                // fixme не внимательно заменила, разве это POST?
	                /** @see \Auth\App\Action\ActivationUser::PARAM_NAME_LOGIN */
                    Logon::getUrl([Logon::POST_NAME_LOGIN => $user->getLogin()])
                );

                return;
            }
        }

	    $change_pass_link = Url::getUrlAbsolute(
		    ChangePass::getUrl([
			    ChangePass::POST_NAME_EMAIL => $email,
			    ChangePass::POST_NAME_CODE => $code
		    ])
	    );

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