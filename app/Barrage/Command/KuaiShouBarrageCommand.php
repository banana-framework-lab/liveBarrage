<?php

namespace App\Barrage\Command;

use App\Barrage\Logic\KuaiShou\KuaiShouLogic;
use Exception;
use Library\Abstracts\Command\AbstractCommand;

class KuaiShouBarrageCommand extends AbstractCommand
{
    /**
     * @throws Exception
     */
    public function execute()
    {
        (new KuaiShouLogic())->execute();
    }
}