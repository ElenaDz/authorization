<?php
namespace Auth\Sys;

class Error
{
	public static function init()
	{
		// Включаем кэширование вывода, чтобы иметь возможность удалить уже отправленное содержание перед выводом ошибки
		ob_start();

		$error_types =  E_ALL - E_DEPRECATED - E_NOTICE - E_STRICT;

		error_reporting($error_types);

		set_error_handler(
			function (int $severity , string $message , string $file , int $line ) use ($error_types)
			{
				throw new \ErrorException($message, 0, $severity, $file, $line);
			},
			$error_types
		);

		register_shutdown_function(
			function () {
				$error = error_get_last();
				if (empty($error)) return;

				error_clear_last();

				$severity = $error['type'];
				if ( ! self::inErrorReporting($severity)) return;

				$msg = $error['message'];

				$result = preg_match('#(.+) in #', $msg, $matches);
				if ($result === 1) {
					$msg = $matches[1];
				}

				$e = new \ErrorException(
					$msg,
					0,
					$severity,
					$error['file'],
					$error['line']
				);

				self::exceptionHandler($e);
			}
		);

		set_exception_handler(
			function (\Throwable $e)
			{
				self::exceptionHandler($e);
			}
		);
	}

	private static function exceptionHandler(\Throwable $e)
	{
		$msg = 'Ошибка! '.$e->getMessage();
		$code = $e->getCode();

		if ($code && ($code < 300 || $code >= 600))
		{
			$msg = $msg." [code $code]";
		}

		if ($e->getFile() && Request::isDevelopment())
		{
			$msg = $msg."\r\n".
				$e->getFile().' on line '.$e->getLine();
		}

		error_log(
			(
				strpos(\PHP_OS, 'WIN') !== false
				? mb_convert_encoding($msg, 'cp1251', 'utf8')
				: $msg
			)
		);

		self::showError($msg, $code);
	}

	private static function inErrorReporting($severity)
	{
		return ! empty($severity & error_reporting());
	}

    private static function showError($msg, $code)
    {
	    // Очистка вывода, чтобы показывать ошибку
	    while (ob_get_level() > 0)
	    {
		    ob_end_clean();
	    }

	    if ( ! headers_sent($filename, $line)) {
	        header("Content-Type: text/plain");
	    }

	    http_response_code($code ?: 500);

	    echo($msg);
		exit;
    }
}