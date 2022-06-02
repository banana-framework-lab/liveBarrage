<?php

namespace App\Barrage\Model\RedisModel\KuaiShou;

use Library\Abstracts\Model\AbstractRedisModel;

class KuaiShouUserInfoModel extends AbstractRedisModel
{
    public $kuaiShouBarrageKey = 'kuai_shou_user_info';

    /***
     * @param string $id
     * @param array $data
     */
    public function addUserInfo(string $id, array $data)
    {
        $this->redis->hSet($this->kuaiShouBarrageKey, $id, json_encode($data));
    }

    /**
     * @param string $id
     * @return string|null
     */
    public function getUserInfo(string $id)
    {
        return $this->redis->hGet($this->kuaiShouBarrageKey, $id) ?: null;
    }

}