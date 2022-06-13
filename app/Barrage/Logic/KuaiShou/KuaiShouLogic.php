<?php


namespace App\Barrage\Logic\KuaiShou;


use App\Barrage\Constant\KuaiShou\KuaiShouStateCode;
use App\Barrage\Service\KuaiShou\KuaiShouClientService;
use Library\Container;
use Swoole\Timer;
use Throwable;

class KuaiShouLogic
{
    public function execute()
    {
        date_default_timezone_set('PRC');

        try {
            // 初始化配置
            Container::setConfig();
            Container::getConfig()->initConfig();
            // 初始化redis
            Container::setRedisPool('barrage');

            $ksClientLogic = new KuaiShouClientLogic();
            $client = new KuaiShouClientService();
            $spider = (new KuaiShouSpiderLogic())->getLiveSpider(
                Container::getConfig()->get('ks_barrage.live_id')
            );
            $client->setOnConnectFunction($ksClientLogic->getOnConnectHandler());
            $client->setLiveStreamFunction($ksClientLogic->getLiveStreamHandler());
        } catch (Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
            return;
        }

        while (true) {
            $state = $client->run($spider);

            if ($client->handleErrCode($state->code, $state->message) != KuaiShouStateCode::CLIENT_NEED_RESTART) {
                Timer::clearAll();
                break;
            }
        }
    }
}