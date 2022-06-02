<?php

namespace App\Barrage\Server;

use Library\Abstracts\Server\AbstractSwooleServer;
use Swoole\WebSocket\Server as SwooleSocketServer;
use Throwable;

class BarrageServer extends AbstractSwooleServer
{
    /**
     * @inheritDoc
     */
    public function onStart(SwooleSocketServer $server, int $workerId): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function exit(SwooleSocketServer $server, int $workerId)
    {
    }

    /**
     * @param Throwable $exception
     * @return array
     */
    public function getExceptionResponse(Throwable $exception)
    {
        return [
            'status' => 0,
            'msg' => $exception->getMessage()
        ];
    }
}