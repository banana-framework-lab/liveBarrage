<?php

use App\Check\Server\CheckServer;
use Library\Server\BananaSwooleServer;

date_default_timezone_set('PRC');
require dirname(__FILE__) . '/../vendor/autoload.php';

$server = new BananaSwooleServer();
$server->setServer(new CheckServer());
$server->run();
