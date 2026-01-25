<?php
if (defined('__DIR_AUTH_ROOT__')) return;

define('__DIR_AUTH_ROOT__', realpath(dirname(__DIR__)));
define('__URL_AUTH_ROOT__', '/'.basename(__DIR__).'/');

const ACTION_NAME = 'action';

require_once __DIR__.'/Sys/Autoload.php';
\Auth\Sys\Autoload::register();
