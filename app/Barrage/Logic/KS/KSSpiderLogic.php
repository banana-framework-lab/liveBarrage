<?php


namespace App\Barrage\Logic\KS;

use App\Barrage\Model\HttpModel\KS\KSModel;
use App\Barrage\Object\KS\KSSpiderObject;
use Exception;
use Library\Container;

/**
 * Class KSSpiderLogic
 * @package App\Barrage\Logic\KS
 */
class KSSpiderLogic
{
    /**
     * @param $live_id
     * @return KSSpiderObject
     * @throws Exception
     */
    public function getLiveSpider($live_id)
    {
        $liveInfo = (new KSModel())->getStreamId(
            $live_id,
            Container::getConfig()->get('ks_barrage.cookie')
        );
        preg_match_all("/liveStream\":(.*),\"feedInfo/", $liveInfo, $result);
        if ($result[1][0] ?? false) {
            $streamInfo = json_decode($result[1][0], true);
            if ($streamInfo['json'] ?? false) {
                $data['stream_id'] = $streamInfo['json']['liveStreamId'] ?? '';

                $socketInfo = (new KSModel())->getWebSocketInfo(
                    $live_id,
                    $data['stream_id'],
                    Container::getConfig()->get('ks_barrage.cookie')
                );

                $socketInfo = json_decode($socketInfo, true);
                $spider = new KSSpiderObject($data);
                $spider->token = $socketInfo['data']['webSocketInfo']['token'];
                $spider->live_ws_url = $socketInfo['data']['webSocketInfo']['webSocketUrls'][0] ?? '';
                $spider->live_id = $live_id;

                return $spider;
            } else {
                throw new Exception('直播间还未开播');
            }
        } else {
            throw new Exception('直播间信息获取失败');
        }
    }
}