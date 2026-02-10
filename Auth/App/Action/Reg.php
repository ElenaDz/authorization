<?php
namespace Auth\App\Action;

use Auth\APP\Helper\Email;
use Auth\App\Entity\User;
use Auth\App\Enum\Error;
use Auth\APP\Helper\Url;
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

		$login = null;
		$email = null;

        if ($_POST[self::POST_NAME_LOGIN])
        {
            $login = $_POST[self::POST_NAME_LOGIN];
            $pass = $_POST[self::POST_NAME_PASS];
            $pass_confirm = $_POST[self::POST_NAME_PASSWORD_CONFIRM];
            $email = $_POST[self::POST_NAME_EMAIL];

            try {
                if ($pass != $pass_confirm) {
                    $errors[Error::LIST_PASS_ERROR][Error::PASS_ERROR] = 'Пароли не совпадают';
					// fixme не будем кидать json в качестве исключения, ни когда такого не видел не слышал
                    throw new \Exception(json_encode($errors));
                }

                $user = User::create($login, $pass, $email);

                $id = Users::add($user);

                if (!empty($id))
				{
					// todo для генерации абсолютных url`ов как здесь используй этот helper ок
                    $activation_link = Url::getUrlAbsolute(
						ActivationUser::getUrl([
							// todo заведи константы для этих магических строк, ниже я дал ссылку на одну из них ok
							ActivationUser::PARAM_NAME_LOGIN => $login,
	                        ActivationUser::PARAM_NAME_CODE => $user->getActivationCode()
	                    ])
                    );

                    $message = Views::get(
                        __DIR__ . '/../View/Block/EmailMessage/Reg.php',
                        [
                            'login' => $login,
                            'activation_link' => $activation_link,
                        ]
                    );

                    Email::send(
                        "Подтверждения электронной почты $email",
						// fixme заказчик просил для каждого письма создавать отдельный шаблон так как он будет их модифицировать в html ok
                        $message,
                        $email
                    );

                    $content = Views::get(
                        __DIR__ . '/../View/Block/Reg/RegSuccess.php'
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