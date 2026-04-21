<?php
require_once __DIR__ . '/autoload.php';

\Auth\Sys\Error::init();


$job = $_GET['job'];

$params = [];

switch ($job) {
	// раз в день
	case \Auth\App\Action\Api\DeleteNotActivatedUsers::class:
		$action = \Auth\App\Action\Api\DeleteNotActivatedUsers::class;
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