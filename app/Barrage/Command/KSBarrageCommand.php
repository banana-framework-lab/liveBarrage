<?php

namespace App\Barrage\Command;

use App\Barrage\Logic\KS\KSClientLogic;
use App\Barrage\Logic\KS\KSSpiderLogic;
use App\Barrage\Service\ClientService;
use Exception;
use Library\Abstracts\Command\AbstractCommand;
use Library\Container;

class KSBarrageCommand extends AbstractCommand
{
    /**
     * @throws Exception
     */
    public function execute()
    {
        Container::setConfig();
        Container::getConfig()->initConfig();

        $ksClientLogic = new KSClientLogic();
        $client = new ClientService();
        $client->setOnConnectFunction($ksClientLogic->getOnConnectHandler());
        $client->setLiveStreamFunction($ksClientLogic->getLiveStreamHandler());
        $client->run(
            (new KSSpiderLogic())->getLiveSpider(
                Container::getConfig()->get('ks_barrage.live_id')
            )
        );
    }
}