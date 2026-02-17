<?php
require __DIR__.'/autoload.php';

\Auth\Sys\Error::init();

\Auth\Sys\Routing::run();