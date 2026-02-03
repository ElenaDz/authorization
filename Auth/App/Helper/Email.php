<?php
namespace Auth\APP\Helper;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{
	public static function send($subject, $message, $to)
	{
//        Работает только без антивируса

		require_once __DIR__ . '/../../../vendor/PHPMailer/src/Exception.php';
		require_once __DIR__ . '/../../../vendor/PHPMailer/src/PHPMailer.php';
		require_once __DIR__ . '/../../../vendor/PHPMailer/src/SMTP.php';

//        $subject = implode(', ', $subject);
		$mail = new PHPMailer(true);

		$mail->CharSet    = PHPMailer::CHARSET_UTF8;

//        $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
		$mail->isSMTP();
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Host       = 'smtp.yandex.ru';

		// здесь логин от яндекса
		$mail->Username   = 'Lenagosu';

		$mail->Password   = 'tkioaxwinulqjvgq';
		$mail->Port       = 587;

		// здесь email кому отправлять это письмо ($to) например в нашем случае это может быть tehnomarket.nhk@yandex.ru
		$mail->addAddress("Lenagosu@yandex.ru");

		// здесь указать email с того же аккаунта, что выше был указан пароль, может совпадать с email to
		$mail->setFrom("Lenagosu@yandex.ru");

		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body    = $message;

		$mail->send();

//        Лена, привет.
//
//        Мы подняли почтовый сервер для отправки писем пользователям при регистрации и восстановлении пароля.
//
//        Сбрасываю данные:
//
//    no-reply@drivemusic.me
//    Пароль: AyCi9dR7zD5jPQC
//
//    SMTP сервер: mx.drivemusic.me
//    Порт: 587
//    Соединение: Безопасное на станд. порт STARTTLS
//    Протокол: POP v3
	}
}