<?php
require __DIR__.'/autoload.php';

if ( ! \Auth\Sys\Request::isDevelopment()) {
	\Auth\Sys\Error::init();
}

\Auth\Sys\Routing::run();