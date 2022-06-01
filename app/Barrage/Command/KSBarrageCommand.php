<?php

namespace App\Barrage\Command;

use App\Barrage\Constant\KS\KSStateCode;
use App\Barrage\Logic\KS\KSClientLogic;
use App\Barrage\Logic\KS\KSSpiderLogic;
use App\Barrage\Service\KS\KSClientService;
use Exception;
use Library\Abstracts\Command\AbstractCommand;
use Library\Container;
use Throwable;

class KSBarrageCommand extends AbstractCommand
{
    /**
     * @throws Exception
     */
    public function execute()
    {
        date_default_timezone_set('PRC');

        try {
            Container::setConfig();
            Container::getConfig()->initConfig();

            $ksClientLogic = new KSClientLogic();
            $client = new KSClientService();
            $spider = (new KSSpiderLogic())->getLiveSpider(
                Container::getConfig()->get('ks_barrage.live_id')
            );
            $client->setOnConnectFunction($ksClientLogic->getOnConnectHandler());
            $client->setLiveStreamFunction($ksClientLogic->getLiveStreamHandler());
        } catch (Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
            return;
        }

        while (true) {
            $state = $client->run((new KSSpiderLogic())->getLiveSpider($spider));

            if ($client->handleErrCode($state->code, $state->message) != KSStateCode::CLIENT_NEED_RESTART) {
                break;
            }
        }
    }
}