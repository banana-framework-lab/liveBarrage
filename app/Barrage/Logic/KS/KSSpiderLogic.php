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
        $liveInfo = (new KSModel())->getStreamId(
            $live_id,
            'did=web_d6e9b22e6c8d7b4d0574e37ba556e573'
        );
        preg_match_all("/liveStream\":(.*),\"feedInfo/", $liveInfo, $result);
        if ($result[1][0] ?? false) {
            $streamInfo = json_decode($result[1][0], true);
            if ($streamInfo['json'] ?? false) {
                $data['stream_id'] = $streamInfo['json']['liveStreamId'] ?? '';

                $socketInfo = (new KSModel())->getWebSocketInfo(
                    $live_id,
                    $data['stream_id'],
                    'userId=622817178; clientid=3; kuaishou.live.bfb1s=7206d814e5c089a58c910ed8bf52ace5; did=web_d6e9b22e6c8d7b4d0574e37ba556e573; client_key=65890b29; kpn=GAME_ZONE; ksliveShowClipTip=true; userId=622817178; kuaishou.live.web_st=ChRrdWFpc2hvdS5saXZlLndlYi5zdBKgAV06y9Q4f5bzqqBtnOGVjIxJ4hj73hwjMQWh_7y5-Hi6MFocE6BccAJAUQCGB-0fsr7ssX3r3YwbqK4LucVlXt9PrCX74GUU8yAMBqMKw6rMKIEVA0UWQFazPHo8iUIDJsPZhf-Dyx8KoWY_xYJe6gdsZTeTVtWG3GGy9VqP0mqhMk8xkatd2ENBXxwHDSAZd-lN8_4vm1yRnpbK5sLm-RIaEk2hY_LIikBot7IUVtJ3ydB6KCIgPiLcSEaVDLJoEHr-tLE3PXCJHfoI4pj8zFv90NJ7QG8oBTAB; kuaishou.live.web_ph=da7298fbe0d373853d80cb9ec3bfb2c9bc05'
                );

                $socketInfo = json_decode($socketInfo, true);
                $spider = new KSSpiderObject($data);
                $spider->token = $socketInfo['data']['webSocketInfo']['token'];
                $spider->live_ws_url = $socketInfo['data']['webSocketInfo']['webSocketUrls'][0] ?? '';
                $spider->live_id = $live_id;
                $spider->page_id = $this->getPageId();
                $spider->reg_data = $this->getLiveRegData($spider);
                return $spider;
            } else {
                throw new Exception('直播间还未开播');
            }
        } else {
            throw new Exception('直播间信息获取失败');
            echo var_dump($liveInfo);
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