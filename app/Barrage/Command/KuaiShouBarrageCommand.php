<?php

namespace App\Barrage\Command;

use App\Barrage\Constant\KuaiShou\KuaiShouStateCode;
use App\Barrage\Logic\KuaiShou\KuaiShouClientLogic;
use App\Barrage\Logic\KuaiShou\KuaiShouSpiderLogic;
use App\Barrage\Service\KS\KSClientService;
use Exception;
use Library\Abstracts\Command\AbstractCommand;
use Library\Container;
use Throwable;

class KuaiShouBarrageCommand extends AbstractCommand
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
            // Pool默认启动
            $defaultInitList = ['redis'];
            foreach ($defaultInitList as $initPool) {
                $default_name = Container::getConfig()->get("pool.{$initPool}.index", 'default');
                if (Container::getConfig()->get("$initPool.$default_name")) {
                    $poolName = ucfirst(strtolower($initPool));
                    if (method_exists(Container::class, "set{$poolName}Pool")) {
                        $methodName = "set{$poolName}Pool";
                        Container::$methodName($default_name);
                    }
                }
            }


            $ksClientLogic = new KuaiShouClientLogic();
            $client = new KSClientService();
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
                break;
            }
        }
    }
}