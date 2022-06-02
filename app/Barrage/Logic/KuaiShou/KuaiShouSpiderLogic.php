<?php

namespace App\Barrage\Logic\KuaiShou;

use App\Barrage\Model\HttpModel\KuaiShou\KuaiShouModel;
use App\Barrage\Object\KuaiShou\KuaiShouSpiderObject;
use Exception;
use Library\Container;

/**
 * Class KuaiShouSpiderLogic
 * @package App\Barrage\Logic\KS
 */
class KuaiShouSpiderLogic
{
    /**
     * @param $live_id
     * @return KuaiShouSpiderObject
     * @throws Exception
     */
    public function getLiveSpider($live_id): KuaiShouSpiderObject
    {
        $liveInfo = (new KuaiShouModel())->getStreamId(
            $live_id,
            Container::getConfig()->get('ks_barrage.cookie')
        );
        preg_match_all("/liveStream\":(.*),\"feedInfo/", $liveInfo, $result);
        if ($result[1][0] ?? false) {
            $streamInfo = json_decode($result[1][0], true);
            if ($streamInfo['json'] ?? false) {
                $data['stream_id'] = $streamInfo['json']['liveStreamId'] ?? '';

                $socketInfo = (new KuaiShouModel())->getWebSocketInfo(
                    $live_id,
                    $data['stream_id'],
                    Container::getConfig()->get('ks_barrage.cookie')
                );

                $socketInfo = json_decode($socketInfo, true);
                $spider = new KuaiShouSpiderObject($data);
                $spider->token = $socketInfo['data']['webSocketInfo']['token'];
                $spider->live_ws_url = $socketInfo['data']['webSocketInfo']['webSocketUrls'][0] ?? '';
                $spider->live_id = $live_id;

                return $spider;
            } else {
                throw new Exception("直播间({$live_id})还未开播");
            }
        } else {
            throw new Exception("直播间({$live_id})信息获取失败");
        }
    }
}