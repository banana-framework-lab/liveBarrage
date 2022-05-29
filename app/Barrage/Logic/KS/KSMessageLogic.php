<?php

namespace App\Barrage\Logic\KS;

use App\Barrage\Object\KS\KSSpiderObject;
use KuaiShouLive\CSWebEnterRoom;
use KuaiShouLive\CSWebHeartbeat;
use KuaiShouLive\SocketMessage;

/**
 * Class KSMessageLogic
 * @package App\Barrage\Logic\KS
 */
class KSMessageLogic
{
    /**
     * @return string
     */
    private function getPageId(): string
    {
        $charset = "_zyxwvutsrqponmlkjihgfedcba9876543210ZYXWVUTSRQPONMLKJIHGFEDCBA";
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
     * @return SocketMessage
     */
    public function getEnterRoomMessage(KSSpiderObject $spider): SocketMessage
    {
        $socketMessage = new SocketMessage();
        $csWebEnterRoom = new CSWebEnterRoom();
        $csWebEnterRoom->setLiveStreamId($spider->stream_id);
        $csWebEnterRoom->setToken($spider->token);
        $csWebEnterRoom->setPageId($this->getPageId());
        $socketMessage->setPayload($csWebEnterRoom->serializeToString());
        $socketMessage->setPayloadType(200);

        return $socketMessage;
    }

    /**
     * @return SocketMessage
     */
    public function getHeartBeatMessage(): SocketMessage
    {
        $socketMessage = new SocketMessage();
        $csWebHeartBeat = new CSWebHeartbeat();
        $csWebHeartBeat->setTimestamp(microTimes());
        $socketMessage->setPayload($csWebHeartBeat->serializeToString());
        $socketMessage->setPayloadType(1);
        $socketMessage->serializeToString();

        return $socketMessage;
    }
}