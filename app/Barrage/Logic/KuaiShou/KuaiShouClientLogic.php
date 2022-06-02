<?php

namespace App\Barrage\Logic\KuaiShou;

use App\Barrage\Constant\KuaiShou\KuaiShouMap;
use App\Barrage\Model\HttpModel\KuaiShou\KuaiShouModel;
use App\Barrage\Object\KuaiShou\KuaiShouSpiderObject;
use Closure;
use KuaiShouLive\SCWebEnterRoomAck;
use KuaiShouLive\SCWebFeedPush;
use KuaiShouLive\SCWebHeartbeatAck;
use KuaiShouLive\SCWebLiveWatchingUsers;
use KuaiShouLive\SocketMessage;
use KuaiShouLive\WebComboCommentFeed;
use KuaiShouLive\WebCommentFeed;
use KuaiShouLive\WebGiftFeed;
use KuaiShouLive\WebLikeFeed;
use KuaiShouLive\WebShareFeed;
use KuaiShouLive\WebSystemNoticeFeed;
use Library\Container;
use Swoole\Coroutine\Http\Client;
use Swoole\Timer;
use Throwable;

/**
 * Class KuaiShouClientLogic
 * @package App\Barrage\Logic\KS
 */
class KuaiShouClientLogic
{
    public function echoSystemMsg($msg)
    {
        echo '----------------------------------------' . date('Y-m-d H:i:s') . $msg . PHP_EOL;
    }

    /**
     * @return Closure
     */
    public function getOnConnectHandler(): Closure
    {
        return function (Client $client, KuaiShouSpiderObject $spider) {

            $this->echoSystemMsg('开始抓取');

            $client->push(
                (new KuaiShouMessageLogic())->getEnterRoomMessage($spider)->serializeToString(),
                WEBSOCKET_OPCODE_BINARY
            );
        };
    }

    /**
     * @return Closure
     */
    public function getLiveStreamHandler(): Closure
    {
        return function ($stream, Client $client, KuaiShouSpiderObject $spider) {
            try {
                $socketMessage = new SocketMessage();
                $socketMessage->mergeFromString($stream);

//                $this->echoSystemMsg(
//                    '推流分析:' .
//                    KSMap::getPayLoadTypeName($socketMessage->getPayloadType()) .
//                    ' ' . $socketMessage->getCompressionType() .
//                    ' ' . $client->errCode
//                );

                if ($socketMessage->getPayloadType() == 300) {

                    $scWebEnterRoomAck = new SCWebEnterRoomAck();
                    $scWebEnterRoomAck->mergeFromString($socketMessage->getPayload());

//                    $this->echoSystemMsg('收到服务器进入房间确认:' . $scWebEnterRoomAck->serializeToJsonString());

//                    Timer::after($scWebEnterRoomAck->getMinReconnectMs(), function () use ($client, $spider) {
//                        $this->echoSystemMsg('发送reconnect');
//                        $client->push(
//                            (new KSMessageLogic())->getEnterRoomMessage($spider)->serializeToString(),
//                            WEBSOCKET_OPCODE_BINARY
//                        );
//                    });

                    Timer::after(120000, function () use ($spider) {
                        (new KuaiShouModel())->getWatchingFeed(
                            Container::getConfig()->get('ks_barrage.cookie'),
                            $spider->stream_id,
                            $spider->live_id
                        );
                    });

                    Timer::tick($scWebEnterRoomAck->getHeartbeatIntervalMs(), function () use ($client, $spider) {
                        $client->push(
                            (new KuaiShouMessageLogic())->getHeartBeatMessage()->serializeToString(),
                            WEBSOCKET_OPCODE_BINARY
                        );

//                        $this->echoSystemMsg('发送心跳包');
                    });
                } elseif ($socketMessage->getPayloadType() == 101) {
                    $scWebHeartbeatAck = new SCWebHeartbeatAck();
                    $scWebHeartbeatAck->mergeFromString($socketMessage->getPayload());

//                    $this->echoSystemMsg('收到服务器心跳确认:' . $scWebHeartbeatAck->serializeToJsonString());

                } elseif ($socketMessage->getPayloadType() == 340) {
                    $scWebLiveWatchingUsers = new SCWebLiveWatchingUsers();
                    $scWebLiveWatchingUsers->mergeFromString($socketMessage->getPayload());

//                    $this->echoSystemMsg($scWebLiveWatchingUsers->serializeToJsonString());

                } elseif ($socketMessage->getPayloadType() == 310) {

                    $scWebFeedPush = new SCWebFeedPush();
                    $scWebFeedPush->mergeFromString($socketMessage->getPayload());

//                    foreach ($scWebFeedPush->getComboCommentFeeds() as $comoCommentFeed) {
//                        /** @var WebComboCommentFeed $comoCommentFeed */
//                        echo date('Y-m-d H:i:s') . '连击评论----' . $comoCommentFeed->getContent() . PHP_EOL;
//                    }

                    foreach ($scWebFeedPush->getCommentFeeds() as $commentFeed) {
                        /** @var WebCommentFeed $commentFeed */
                        echo date('Y-m-d H:i:s') . $commentFeed->getUser()->getUserName() . ':' . $commentFeed->getContent() . PHP_EOL;
                    }

//                    foreach ($scWebFeedPush->getGiftFeeds() as $giftFeed) {
//                        /** @var WebGiftFeed $giftFeed */
//                        echo date('Y-m-d H:i:s') . $giftFeed->getUser()->getUserName() . ':送出了' . KuaiShouMap::getGiftName($giftFeed->getGiftId()) . PHP_EOL;
//                    }
//
//                    foreach ($scWebFeedPush->getLikeFeeds() as $likeFeed) {
//                        /** @var WebLikeFeed $likeFeed */
//                        echo date('Y-m-d H:i:s') . $likeFeed->getUser()->getUserName() . ':点亮了爱心' . PHP_EOL;
//                    }
//
//                    foreach ($scWebFeedPush->getShareFeeds() as $shareFeed) {
//                        /** @var WebShareFeed $shareFeed */
//                        echo date('Y-m-d H:i:s') . $shareFeed->getUser()->getUserName() . ':分享了直播间' . PHP_EOL;
//                    }
//
//                    foreach ($scWebFeedPush->getSystemNoticeFeeds() as $systemFeed) {
//                        /** @var WebSystemNoticeFeed $systemFeed */
//                        echo date('Y-m-d H:i:s') . '系统信息:' . $systemFeed->getUser()->getUserName() . ':' . $systemFeed->getContent() . PHP_EOL;
//                    }

                    (new KuaiShouPushStreamLogic())->handlePushStream($scWebFeedPush, $spider);

                }
            } catch (Throwable $e) {
                echo $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
            }

            return true;
        };

    }
}