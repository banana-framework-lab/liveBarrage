<?php

namespace App\Barrage\Model\RedisModel\KuaiShou;

use App\Barrage\Object\KuaiShou\KuaiShouMessageObject;
use Library\Abstracts\Model\AbstractRedisModel;

class KuaiShouBarrageModel extends AbstractRedisModel
{
    public $kuaiShouBarrageKey = 'kuai_shou_barrage';

    /***
     * @param KuaiShouMessageObject $data
     */
    public function addBarrage(KuaiShouMessageObject $data)
    {
        $this->redis->zAdd($this->kuaiShouBarrageKey, [], microTimes(), json_encode($data));
    }

    /**
     * @param int $time
     * @return array
     */
    public function getBarrage(int $time)
    {
        return $this->redis->zRangeByScore($this->kuaiShouBarrageKey, $time, 9999999999999) ?: [];
    }

    public function deleteAllBarrage()
    {
        $this->redis->del($this->kuaiShouBarrageKey);
    }

}