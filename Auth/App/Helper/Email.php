<?php
namespace APP\Helper;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{
	public static function send($subject, $message, $to)
	{
		require_once __DIR__ . '/../../../vendor/PHPMailer/src/Exception.php';
		require_once __DIR__ . '/../../../vendor/PHPMailer/src/PHPMailer.php';
		require_once __DIR__ . '/../../../vendor/PHPMailer/src/SMTP.php';

//        $subject = implode(', ', $subject);
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

		// здесь email кому отправлять это письмо например в нашем случае это может быть tehnomarket.nhk@yandex.ru
		$mail->addAddress("elena-gosu@mail.ru");

		// здесь указать email с того же аккаунта, что выше был указан пароль, может совпадать с email to
		$mail->setFrom("no-reply@drivemusic.me");

		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body    = $message;

		$mail->send();

	}
}