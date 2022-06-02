<?php

namespace App\Barrage\Constant\KuaiShou;

class KuaiShouStateCode
{
    // 网络链接状态码
    const SUCCESS = 0;
    const HANDSHAKE_FAIL = 444;

    // 重新启动状态码
    const CLIENT_NEED_RESTART = 1;
    const CLIENT_END = 0;
}