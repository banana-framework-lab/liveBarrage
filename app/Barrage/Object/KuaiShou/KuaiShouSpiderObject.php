<?php


namespace App\Barrage\Object\KuaiShou;

use Exception;
use Library\Abstracts\Object\AbstractObject;

class KuaiShouSpiderObject extends AbstractObject
{
    public $live_id;

    public $live_ws_url;

    public $stream_id;

    public $token;

    /**
     * @throws Exception
     */
    public function getWSHost()
    {
        $host = (parse_url($this->live_ws_url))['host'] ?? '';
        if ($host) {
            return $host;
        } else {
            throw new Exception('host为空', 0);
        }
    }

}