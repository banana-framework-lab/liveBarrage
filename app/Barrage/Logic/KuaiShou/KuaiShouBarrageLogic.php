<?php

namespace App\Barrage\Logic\KuaiShou;

use App\Barrage\Model\RedisModel\KuaiShou\KuaiShouBarrageModel;

class KuaiShouBarrageLogic
{
    public function getBarrage(int $time)
    {
        $data = (new KuaiShouBarrageModel())->getBarrage(0);
        foreach ($data as &$info) {
            $info = json_decode($info, true);
        }
        return $data;
    }
}