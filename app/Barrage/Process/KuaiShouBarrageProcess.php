<?php


namespace App\Barrage\Process;


use App\Barrage\Logic\KuaiShou\KuaiShouLogic;
use Library\Abstracts\Process\AbstractProcess;

class KuaiShouBarrageProcess extends AbstractProcess
{
    public function main()
    {
        (new KuaiShouLogic())->execute();
    }
}