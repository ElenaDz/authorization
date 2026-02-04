<?php
namespace Auth\App\Action;

use Auth\APP\Helper\Email;
use Auth\App\Entity\User;
use Auth\App\Enum\Error;
use Auth\App\Model\Users;
use Auth\Sys\Views;

class Reg extends _Base
{
    const POST_NAME_LOGIN = 'login';
    const POST_NAME_EMAIL = 'email';
    const POST_NAME_PASS = 'password';
    const POST_NAME_PASSWORD_CONFIRM = 'password_confirm';


    /**
     * @throws \Exception
     */
    public function __invoke()
	{
        $errors = [];

        if ($_POST[self::POST_NAME_LOGIN])
        {
            $login = $_POST[self::POST_NAME_LOGIN];
            $pass = $_POST[self::POST_NAME_PASS];
            $pass_confirm = $_POST[self::POST_NAME_PASSWORD_CONFIRM];
            $email = $_POST[self::POST_NAME_EMAIL];

            try {
                if ( $pass != $pass_confirm) {
                    $errors[Error::LIST_PASS_ERROR][Error::PASS_ERROR] = 'Пароли не совпадают';
                    throw new \Exception(json_encode($errors));
                }

                $user = User::create($login, $pass, $email);
                $id = Users::add($user);

                if (!empty($id)) {
                    $activation_link = $_SERVER['HTTP_ORIGIN'] . ActivationUser::getUrl([
                        'login' => $_POST[self::POST_NAME_LOGIN],
                        'code' => $user->getEncodeActivationCode()
                    ]);

                    Email::send(
                        "Подтверждения электронной почты $email",
                        "Здравствуйте, $login!
                        Для подтверждения электронной почты и активации вашего аккаунта на сайте 
                        drivemusic.me, пожалуйста, перейдите по <a href=$activation_link>этой ссылке</a>
                        С уважением,
                        Команда drivemusic",
                        $email
                    );

                    $content = Views::get(
                        __DIR__ . '/../View/AfterReg.php',
                        [
                            'test' => ''
                        ]
                    );

                    self::showLayout(
                        'Регистрация',
                        $content
                    );
                    return;
                }

            } catch (\Exception $exception){
                $errors = json_decode($exception->getMessage(),true);
            }
        }

        $content = Views::get(
            __DIR__ . '/../View/Reg.php',
            [
                'errors' => $errors,
                'login' => $login,
                'email' => $email,
            ]
        );

        self::showLayout(
            'Регистрация',
            $content
        );
	}
}