<?php

use App\Barrage\Server\BarrageServer;
use Library\Server\BananaSwooleServer;

date_default_timezone_set('PRC');
require dirname(__FILE__) . '/../vendor/autoload.php';

$server = new BananaSwooleServer();
$server->setServer(new BarrageServer());
$server->run();
