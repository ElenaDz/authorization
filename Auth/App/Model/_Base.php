<?php
namespace Auth\App\Model;

use PDO;

abstract class _Base
{
	/**
	 * @var PDO $pdo
	 */
	private static $pdo;

	protected static function getPDO(): PDO
	{
		if (empty(self::$pdo)) {
			self::$pdo = new PDO(
				'mysql:host=localhost;dbname=authorization',
				'lena',
				'`12',
				[
                    PDO::ATTR_PERSISTENT => false
                ]
			);
		}
		return self::$pdo;
	}
}