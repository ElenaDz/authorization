<?php
namespace Auth\App\Entity;

abstract class _Base
{
	/**
	 * Метод объявлен final так как используется метод fetchObject который вызывает конструктор, что нам не нужно,
	 * final гарантирует что конструктор останется пустым
	 */
	final protected function __construct(){}
}