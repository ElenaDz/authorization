<?php
$start = microtime(true);

require __DIR__.'/autoload.php';

\Auth\Sys\Error::init();

\Auth\Sys\Routing::run();

echo round((microtime(true) - $start)*1000).' msec';