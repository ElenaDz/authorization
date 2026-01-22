<?php
require __DIR__.'/_init.php';

$class_name_action = $_GET['action'];

if ( ! class_exists($class_name_action, true))
{
	\Sys\Error::showError(
		sprintf(
			'Action not found "%s"',
			$class_name_action
		)
	);
};

$action = new $class_name_action;

$action();

