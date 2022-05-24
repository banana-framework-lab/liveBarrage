<?php

namespace App\Barrage\Service\KS;

use App\Barrage\Object\KS\KSSpiderObject;
use Closure;
use co;
use Exception;
use Swoole\Coroutine\Http\Client;
use Swoole\Timer;

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
        $this->client->set(['websocket_mask' => true]);
        $this->client->upgrade('/websocket');

        if ($this->client->getStatusCode() !== 101) {
            echo "websocket握手失败,返回码为" . $this->client->getStatusCode() . PHP_EOL;
            $this->client->close();
            return;
        }

        $this->startOnConnect();

        while (true) {
            $swooleMsg = $this->client->recv();
            $errCode = $this->client->errCode;
            if ($swooleMsg && !$errCode) {
                if (!$this->liveStreamHandle($swooleMsg->data, $this->client, $spider)) {
                    break;
                }
            } elseif ($errCode) {
                echo "获取信息失败:错误信息:{$this->client->errMsg},错误码:{$errCode}" . PHP_EOL;
                if ($errCode) {
                    $this->client->close();
                    Timer::clearAll();
                    break;
                }
            }
            co::sleep(1);
        }

        $this->client->close();
    }
}