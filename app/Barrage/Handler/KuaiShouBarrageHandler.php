<?php


namespace App\Barrage\Handler;


use Library\Abstracts\Handler\AbstractHandler;
use Swoole\Http\Request as SwooleHttpRequest;
use Swoole\WebSocket\Frame as SwooleSocketFrame;
use Swoole\WebSocket\Server as SwooleSocketServer;

class KuaiShouBarrageHandler extends AbstractHandler
{

    public function open(SwooleSocketServer $server, SwooleHttpRequest $request)
    {
        // TODO: Implement open() method.
    }

    public function message(SwooleSocketServer $server, SwooleSocketFrame $frame)
    {
        echo $frame->data;
    }

    public function close(SwooleSocketServer $server, int $fd)
    {
        // TODO: Implement close() method.
    }
}