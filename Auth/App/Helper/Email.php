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

		$mail->addAddress(
			Request::isDevelopment() && strtolower(substr($to, 0, 4)) === 'lena'
			? 'Lenagosu@yandex.ru'
			: $to
		);

		// здесь указать email с того же аккаунта, что выше был указан пароль, может совпадать с email to
		$mail->setFrom("no-reply@drivemusic.me");

		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body    = $message;

		return $mail->send();
	}
}