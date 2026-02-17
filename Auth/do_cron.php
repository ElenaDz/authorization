<?php
require_once __DIR__ . '/autoload.php';

\Auth\Sys\Error::init();

$job = $argv[1] ? $argv[1] : $_GET['job'];

$params = [];

switch ($job) {
	// раз в день
	case \Auth\App\Action\DeleteNotActivatedUsers::class:
		$action = \Auth\App\Action\DeleteNotActivatedUsers::class;
		break;

	default:
		throw new \Exception(
			sprintf(
				'Не известный job "%s"',
				$job
			)
		);
}

\Auth\Sys\Routing::runAction($action, $params);