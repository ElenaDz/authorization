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
		$mail->Host       = 'mx.drivemusic.me';

		// здесь логин от яндекса
		$mail->Username   = 'no-reply';

		$mail->Password   = 'AyCi9dR7zD5jPQC';
		$mail->Port       = 587;

		$mail->addAddress($to);

		// здесь указать email с того же аккаунта, что выше был указан пароль, может совпадать с email to
		$mail->setFrom("no-reply@drivemusic.me");

		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body    = $message;

		return $mail->send();

		// todo использовать данные присланные заказником, почта должна приходить к тебе на емейл, если вдруг не удастся
		//  об этом лучше узнать как можно раньше ok
	}
}