<?php


namespace App\Barrage\Logic\KS;

use App\Barrage\Model\HttpModel\KS\KSModel;
use App\Barrage\Object\KS\KSSpiderObject;
use Exception;
use KuaiShouPack\CSWebEnterRoom;
use KuaiShouPack\CSWebHeartbeat;
use KuaiShouPack\SocketMessage;

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
        $liveInfo = (new KSModel())->getLiveInfo(
            $live_id,
            'did=web_e2c386c5989849e2b5d35f8eeb5ba6d7; didv=1652943906000; sid=ed16c913549f9f0722c49756; Hm_lvt_86a27b7db2c5c0ae37fee4a8a35033ee=1652943904; Hm_lpvt_86a27b7db2c5c0ae37fee4a8a35033ee=1652948380'
        );
        preg_match_all("/wsFeedInfo\":(.*),\"liveExist/", $liveInfo, $result);
        if ($result) {
            $wsFeedInfo = json_decode($result[1][0], true);
            $data['stream_id'] = $wsFeedInfo['liveStreamId'] ?? '';
            $data['live_ws_url'] = $wsFeedInfo['webSocketUrls'][0] ?? '';
            $data['token'] = $wsFeedInfo['token'] ?? '';

            $spider = new KSSpiderObject($data);
            $spider->live_id = $live_id;
            $spider->page_id = $this->getPageId();
            $spider->reg_data = $this->getLiveRegData($spider);
            return $spider;
        } else {
            throw new Exception('直播间信息获取失败');
        }
    }

    /**
     * @return string
     */
    private function getPageId()
    {
        $charset = "bjectSymhasOwnProp-0123456789ABCDEFGHIJKLMNQRTUVWXYZ_dfgiklquvxz";
        $page_id = '';
        for ($i = 1; $i <= 16; $i++) {
            $page_id .= substr($charset, (rand(0, strlen($charset) - 1)), 1);
        }
        $page_id .= "_";
        $page_id .= time() * 1000;
        return $page_id;
    }

    /**
     * @param KSSpiderObject $spider
     * @return string
     */
    private function getLiveRegData(KSSpiderObject $spider)
    {
        $socketMessage = new SocketMessage();
        $csWebEnterRoom = new CSWebEnterRoom();
        $csWebEnterRoom->setLiveStreamId($spider->stream_id);
        $csWebEnterRoom->setPageId($spider->page_id);
        $csWebEnterRoom->setToken($spider->token);
        $socketMessage->setPayload($csWebEnterRoom->serializeToString());
        $socketMessage->setPayloadType(200);

        return $socketMessage->serializeToString();
    }

    /**
     * @param KSSpiderObject $spider
     * @return string
     */
    public function getLiveHeartBeatData(KSSpiderObject $spider)
    {
        $socketMessage = new SocketMessage();
        $csWebHeartBeat = new CSWebHeartbeat();
        $csWebHeartBeat->setTimestamp((int)(time() * 1000));
        $socketMessage->setPayload($csWebHeartBeat->serializeToString());
        $socketMessage->setPayloadType(1);
        return $socketMessage->serializeToString();
    }
}