<?php

namespace App\Barrage\Service;

use App\Barrage\Object\KS\KSSpiderObject;
use Closure;
use Exception;
use Swoole\Coroutine\Http\Client;

class ClientService
{
    /**
     * @var Client $client
     */
    private $client;

    private $spider;

    private $onConnectFunction;

    private $liveStreamFunction;

    private function startOnConnect()
    {
        $function = $this->onConnectFunction;
        $function($this->client, $this->spider);
    }

    private function liveStreamHandle($stream, Client $client, KSSpiderObject $spider)
    {
        $function = $this->liveStreamFunction;
        return $function($stream, $client, $spider) ?: true;
    }

    /**
     * @param Closure $onConnectFunction
     */
    public function setOnConnectFunction(Closure $onConnectFunction)
    {
        $this->onConnectFunction = $onConnectFunction;
    }

    /**
     * @param Closure $liveStreamFunction
     */
    public function setLiveStreamFunction(Closure $liveStreamFunction)
    {
        $this->liveStreamFunction = $liveStreamFunction;
    }

    private function validate()
    {
        if (!$this->onConnectFunction) {
            throw new Exception('onConnectFunction不能为空');
        }

        if (!$this->liveStreamFunction) {
            throw new Exception('liveStreamHandleFunction不能为空');
        }

        if (!$this->spider) {
            throw new Exception('spider不能为空');
        }
    }

    /**
     * @param KSSpiderObject $spider
     * @throws Exception
     */
    public function run(KSSpiderObject $spider)
    {
        $this->spider = $spider;

        $this->validate();

        $host = (parse_url($this->spider->live_ws_url))['host'] ?? '';
        $this->client = new Client($host, 443, true);
//        $this->client->set(['websocket_mask' => true]);
        $this->client->upgrade('/websocket');

        if ($this->client->getStatusCode() !== 101) {
            echo "websocket握手失败,返回码为" . $this->client->getStatusCode() . PHP_EOL;
            $this->client->close();
            return;
        }

        $this->startOnConnect();

        while (true) {
            $swooleMsg = $this->client->recv();
            if ($swooleMsg) {
                if (!$this->liveStreamHandle($swooleMsg->data, $this->client, $spider)) {
                    break;
                }
            }
//            co::sleep(20);
        }

        $this->client->close();
    }
}