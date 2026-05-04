<?php
declare(strict_types=1);

class Sql
{
	public static function setPDO(PDO $pdo)
	{
		SqlWhereCondition::setPDO($pdo);
	}

	/**
	 * @see https://medoo.in/api/where
	 */
	public static function where(array $array): string
	{
		return self::whereMany($array);
	}

	public static function raw($value):SqlRaw
	{
		return new SqlRaw($value);
	}


	private static function whereMany($array, $glue = 'AND', $level = 0)
	{
		$array = array_map(
			function ($column, $value) use ($level)
			{
				$column = strpos($column, '#') !== false
					? strstr($column, "#", true)
					: $column;

				if (
						(in_array($column, ['OR', 'AND']) || is_int($column))
					&&  is_array($value)
				) {
					$level ++;

					return
						( $level > 1 ? '(' : '').
						self::whereMany($value, $column, $level).
						( $level > 1 ? ')' : '');
				}

				return (new SqlWhereCondition($column, $value))->toString();
			},
			array_keys($array),
			$array
		);

		return implode( " {$glue} ", $array);
	}
}


class SqlRaw
{
	private $value;

	public function __construct(string $value)
	{
		$this->value = $value;
	}

	public function __toString()
	{
		return $this->value;
	}
}


class SqlWhereCondition
{
	private $column;
	private $sign;
	private $value;

	private static $pdo;


	public function __construct($column, $value)
	{
		$this->setColumn($column);
		$this->setValue($value);
	}


	public static function setPDO(PDO $pdo)
	{
		self::$pdo = $pdo;
	}


	public function toString()
	{
		$column = $this->getColumn();
		$values = $this->getValue();
		$sign = $this->getSign();

		if ( ! is_array($values))
		{
			$value = $values;

			$is_null = strpos($value, 'NULL') !== false;

			if ($is_null) {
				if ( ! in_array($sign, ['=', '!=']))
				{
					throw new \Exception(
						sprintf(
							'Не допустимое значение [%s] для NULL (столбец "%s")',
							$sign,
							$this->getColumn()
						)
					);
				}

				$sign = 'IS'.($sign === '!=' ? ' NOT' : '');
			}

		} else
		{
			$is_between = strpos($sign, 'BETWEEN') !== false;

			if ($is_between)
			{
				if (count($values) != 2) {
					throw new Exception(
						sprintf(
							'Для BETWEEN нужно передать 2 значения, переданы %s (столбец "%s")',
							count($values),
							$column
						)
					);
				}
				$value = implode(' AND ', $values);

			} else {
				if ( ! in_array($sign, ['=', '!=']))
				{
					throw new \Exception(
						sprintf(
							'Не допустимое значение [%s] для массива значений (столбец "%s")',
							$sign,
							$column
						)
					);
				}

				$sign = ($sign === '!=' ? 'NOT ' : '').'IN';

				$value = '('.implode(', ', $values).')';
			}
		}

		return
			$column.' '.
			$sign.' '.
			$value;
	}

	private function setColumn($column)
	{
		$pattern = '#^(\w+)(?:\[((?:[><]=?)|(?:<>)|(?:><)|(?:!=?)|(?:!?~)|(?:REGEXP))\])?$#';
		$result = preg_match($pattern, $column, $matches);
		if ($result === false) {
			throw new Exception(
				sprintf(
					'Не удалось распарсить столбец "%s"',
					$column
				)
			);
		}
		if ($matches[0] !== $column) {
			throw new Exception(
				sprintf(
					'Поиск по регулярному выражения в столбце вернул строку "%s" не совпадающую с исходной "%s"',
					$matches[0],
					$column
				)
			);
		}

		$column = $matches[1];
		$_sign = $matches[2];

		switch ($_sign) {
			case '!':
				$sign = '!=';
				break;

			case '<>':
				$sign = 'BETWEEN';
				break;

			case '><':
				$sign = 'NOT BETWEEN';
				break;

			case '~':
				$sign = 'LIKE';
				break;

			case '!~':
				$sign = 'NOT LIKE';
				break;

			default:
				$sign = $_sign;
		}

		$this->column = $column;
		$this->sign = $sign;
	}

	private function getColumn()
	{
		return $this->column;
	}


	private function getSign()
	{
		return $this->sign ?: '=';
	}

	private function isLike()
	{
		return strpos($this->getSign(), 'LIKE') !== false;
	}


	private function setValue($value)
	{
		if (is_array($value))
		{
			$value = array_map(
				function ($value) {
					return $this->encodeValue($value);
				},
				$value
			);

		} else {
			$value = $this->encodeValue($value);
		}

		$this->value = $value;
	}

	private function getValue()
	{
		return $this->value;
	}

	private function encodeValue($value)
	{
		if ($this->isLike() && ! is_string($value)) {
			throw new Exception(
				sprintf(
					'LIKE не применим к типу данных "%s" (столбец "%s")',
					gettype($value),
					$this->getColumn()
				)
			);
		}

		if (is_numeric($value)) {
			$value = (string) $value;

		} elseif (is_bool($value)) {
			$value = $value ? '1' : '0';

		} elseif (is_null($value)) {
			$value = 'NULL';

		} elseif ($value instanceof \SqlRaw) {
			$value = (string) $value;

		} elseif (is_string($value)) {
			if ($this->isLike()) {
				$value = (substr($value, -1) === '%' || substr($value, 0, 1) === '%')
					? $value
					: "%$value%";
			}

			$value = self::$pdo->quote($value, PDO::PARAM_STR);

		} else {
			throw new Exception(
				sprintf(
					'Неожиданный тип данных значения "%s" (столбец "%s")',
					gettype($value),
					$this->getColumn()
				)
			);
		}

		return $value;
	}
}