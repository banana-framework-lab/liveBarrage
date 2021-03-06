<?php

namespace App\Barrage\Service\KuaiShou;

use App\Barrage\Constant\KuaiShou\KuaiShouStateCode;
use App\Barrage\Object\KuaiShou\KuaiShouErrorObject;
use App\Barrage\Object\KuaiShou\KuaiShouSpiderObject;
use Closure;
use co;
use Exception;
use Swoole\Coroutine\Http\Client;
use Swoole\Timer;
use Throwable;

class KuaiShouClientService
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

    private function liveStreamHandle($stream, Client $client, KuaiShouSpiderObject $spider): bool
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

    /**
     * @throws Exception
     */
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
     * @param $errCode
     * @param $errMsg
     * @return int
     */
    public function handleErrCode($errCode, $errMsg): int
    {
        switch ($errCode) {
            case KuaiShouStateCode::LIVE_DOWN:
            case 1017:
            case 1016:
                echo date('Y-m-d H:i:s') . '主播已经下播' . $errCode . PHP_EOL;
                return KuaiShouStateCode::CLIENT_END;
            case 11:
            case KuaiShouStateCode::HANDSHAKE_FAIL:
                echo date('Y-m-d H:i:s') . '服务端断开，重新连接' . PHP_EOL;
                return KuaiShouStateCode::CLIENT_NEED_RESTART;
            default:
                echo date('Y-m-d H:i:s') . "错误信息:$errMsg,错误码:$errCode" . PHP_EOL;
                return KuaiShouStateCode::CLIENT_END;
        }
    }

    /**
     * @param KuaiShouSpiderObject $spider
     * @return KuaiShouErrorObject
     */
    public function run(KuaiShouSpiderObject $spider): KuaiShouErrorObject
    {
        try {
            $this->spider = $spider;
            $this->validate();

            $this->client = new Client($this->spider->getWSHost(), 443, true);
            $this->client->set(['websocket_mask' => true]);
            $this->client->upgrade('/websocket');

            if ($this->client->getStatusCode() !== 101) {
                $this->client->close();
                throw new Exception("websocket握手失败,返回码为" . $this->client->getStatusCode(), KuaiShouStateCode::HANDSHAKE_FAIL);
            }

            $this->startOnConnect();

            while (true) {
                $swooleMsg = $this->client->recv(-1);
                if (!$this->client->errCode) {
                    if ($swooleMsg) {
                        if ($swooleMsg->data) {
                            if (!$this->liveStreamHandle($swooleMsg->data, $this->client, $spider)) {
                                return new KuaiShouErrorObject([
                                    'code' => KuaiShouStateCode::SUCCESS,
                                ]);
                            }
                        }
                    } else {
                        throw new Exception('返回空消息体', KuaiShouStateCode::LIVE_DOWN);
                    }
                } else {
                    $this->client->close();
                    Timer::clearAll();
                    throw new Exception($this->client->errMsg, $this->client->errCode);
                }
                co::sleep(1);
            }
        } catch (Throwable $e) {
            $error = new KuaiShouErrorObject();
            $error->code = $e->getCode();
            $error->message = $e->getMessage();
        }
        return $error;
    }
}