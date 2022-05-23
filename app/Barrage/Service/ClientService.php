<?php

namespace App\Barrage\Service;

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

    private $heartBeatPid;

    private $heartBeatFunction;

    private $onConnectFunction;

    private $liveStreamFunction;

    private function startOnConnect()
    {
        $function = $this->onConnectFunction;
        $function($this->client, $this->spider);
    }

    private function startHeartBeat()
    {
        $this->heartBeatPid = Timer::tick(20000, function () {
            $heartBeat = $this->heartBeatFunction;
            $heartBeat($this->client, $this->spider);
        });
    }

    private function liveStreamHandle($stream)
    {
        $function = $this->liveStreamFunction;
        return $function($stream) ?: true;
    }

    /**
     * @param Closure $onConnectFunction
     */
    public function setOnConnectFunction(Closure $onConnectFunction)
    {
        $this->onConnectFunction = $onConnectFunction;
    }

    /**
     * @param Closure $heartBeatFunction
     */
    public function setHeartBeatFunction(Closure $heartBeatFunction)
    {
        $this->heartBeatFunction = $heartBeatFunction;
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
        if (!$this->heartBeatFunction) {
            throw new Exception('heartBeatFunction不能为空');
        }

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
//        $this->client->setHeaders([
//            'Accept-Encoding: gzip, deflate, br',
//            'Accept-Language: zh-CN,zh;q=0.9',
//            'Cache-Control: no-cache',
//            'Pragma: no-cache',
//            'Origin: https://live.kuaishou.com',
//            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.67 Safari/537.36'
//        ]);
        $this->client->set(['websocket_mask' => true]);
        $this->client->upgrade('/websocket');

        if ($this->client->getStatusCode() !== 101) {
            echo "websocket握手失败,返回码为" . $this->client->getStatusCode() . PHP_EOL;
            return;
        }

        $this->client->close();

        $this->startOnConnect();
        $this->startHeartBeat();

        while (true) {
            $swooleMsg = $this->client->recv();
            if ($swooleMsg) {
                if (!$this->liveStreamHandle($swooleMsg->data)) {
                    break;
                }
            }
            co::sleep(10);
        }

        Timer::clear($this->heartBeatPid);
        $this->client->close();
    }
}