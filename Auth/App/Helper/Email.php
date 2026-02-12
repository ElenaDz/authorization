<?php
namespace Auth\APP\Helper;

use Auth\Sys\Request;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{
	/**
	 * Внимание! Работает только без антивируса
	 */
	public static function send($subject, $message, $to)
	{
		$to = Request::isDevelopment() && $to === '1@1' ? 'Lenagosu@yandex.ru' : $to;

		require_once __DIR__ . '/../../../vendor/PHPMailer/src/Exception.php';
		require_once __DIR__ . '/../../../vendor/PHPMailer/src/PHPMailer.php';
		require_once __DIR__ . '/../../../vendor/PHPMailer/src/SMTP.php';

		// $subject = implode(', ', $subject);
		$mail = new PHPMailer(true);

		$mail->CharSet    = PHPMailer::CHARSET_UTF8;

		// $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
		$mail->isSMTP();
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Host       = 'smtp.yandex.ru';

		// здесь логин от яндекса
		$mail->Username   = 'Lenagosu';

		$mail->Password   = 'tkioaxwinulqjvgq';
		$mail->Port       = 587;

		$mail->addAddress($to);

		// здесь указать email с того же аккаунта, что выше был указан пароль, может совпадать с email to
		$mail->setFrom("Lenagosu@yandex.ru");

		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body    = $message;

		$mail->send();

		// todo использовать данные присланные заказником, почта должна приходить к тебе на емейл, если вдруг не удастся
		//  об этом лучше узнать как можно раньше

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