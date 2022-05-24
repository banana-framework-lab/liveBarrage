<?php

namespace App\Barrage\Logic\KS;

use App\Barrage\Constant\KSMap;
use App\Barrage\Object\KS\KSSpiderObject;
use Closure;
use Google\Protobuf\Internal\CodedInputStream;
use KuaiShouLive\SCWebEnterRoomAck;
use KuaiShouLive\SCWebFeedPush;
use KuaiShouLive\SCWebLiveWatchingUsers;
use KuaiShouLive\SocketMessage;
use KuaiShouLive\WebComboCommentFeed;
use KuaiShouLive\WebCommentFeed;
use KuaiShouLive\WebGiftFeed;
use KuaiShouLive\WebLikeFeed;
use KuaiShouLive\WebShareFeed;
use KuaiShouLive\WebSystemNoticeFeed;
use Swoole\Coroutine\Http\Client;
use Swoole\Timer;
use Throwable;

/**
 * Class KSClientLogic
 * @package App\Barrage\Logic\KS
 */
class KSClientLogic
{
    /**
     * @return Closure
     */
    public function getOnConnectHandler()
    {
        return function (Client $client, KSSpiderObject $spider) {
            echo '----------------------------------------' . date('Y-m-d H:i:s') . '开始抓取' . PHP_EOL;
            $client->push(
                (new KSMessageLogic())->getEnterRoomMessage($spider)->serializeToString(),
                WEBSOCKET_OPCODE_BINARY
            );
//            $client->push(
//                (new KSMessageLogic())->getHeartBeatMessage()->serializeToJsonString(),
//                WEBSOCKET_OPCODE_BINARY
//            );
        };
    }

    /**
     * @return Closure
     */
    public function getLiveStreamHandler()
    {
        return function ($stream, Client $client, KSSpiderObject $spider) {
            try {
                $socketMessage = new SocketMessage();
                $socketMessage->mergeFromString($stream);
                echo '----------------------------------------' . date('Y-m-d H:i:s') . '推流分析:' .
                    KSMap::getPayLoadTypeName($socketMessage->getPayloadType()) .
                    ' ' . $socketMessage->getCompressionType() . ' ' . $client->errCode . PHP_EOL;

                if ($socketMessage->getPayloadType() == 300) {

                    $scWebEnterRoomAck = new SCWebEnterRoomAck();
                    $scWebEnterRoomAck->mergeFromString($socketMessage->getPayload());
//
//                    Timer::after($scWebEnterRoomAck->getMinReconnectMs(), function () use ($client, $spider) {
//                        echo '----------------------------------------' . date('Y-m-d H:i:s') . '发送reconnect' . PHP_EOL;
//                        $client->push(
//                            (new KSMessageLogic())->getEnterRoomMessage($spider)->serializeToString(),
//                            WEBSOCKET_OPCODE_BINARY
//                        );
//                    });

                    Timer::tick($scWebEnterRoomAck->getHeartbeatIntervalMs(), function () use ($client, $spider) {
                        $client->push(
                            (new KSMessageLogic())->getHeartBeatMessage()->serializeToJsonString(),
                            WEBSOCKET_OPCODE_BINARY
                        );
                        echo '----------------------------------------' . date('Y-m-d H:i:s') . '发送心跳包' . PHP_EOL;
                    });
                } elseif ($socketMessage->getPayloadType() == 340) {
                    $scWebEnterRoomAck = new SCWebLiveWatchingUsers();
                    $scWebEnterRoomAck->mergeFromString($socketMessage->getPayload());

//                    var_dump($scWebEnterRoomAck->serializeToJsonString());
                } elseif ($socketMessage->getPayloadType() == 310) {
                    $scWebFeedPush = new SCWebFeedPush();
                    $scWebFeedPush->mergeFromString($socketMessage->getPayload());


                    foreach ($scWebFeedPush->getComboCommentFeeds() as $comoCommentFeed) {
                        /** @var WebComboCommentFeed $comoCommentFeed */
                        echo '连击评论----' . $comoCommentFeed->getContent() . PHP_EOL;
                    }

                    foreach ($scWebFeedPush->getCommentFeeds() as $commentFeed) {
                        /** @var WebCommentFeed $commentFeed */
                        echo $commentFeed->getUser()->getUserName() . ':' . $commentFeed->getContent() . PHP_EOL;
                    }

                    foreach ($scWebFeedPush->getGiftFeeds() as $giftFeed) {
                        /** @var WebGiftFeed $giftFeed */
                        echo $giftFeed->getUser()->getUserName() . ':送出了' . KSMap::getGiftName($giftFeed->getGiftId()) . PHP_EOL;
                    }

                    foreach ($scWebFeedPush->getLikeFeeds() as $likeFeed) {
                        /** @var WebLikeFeed $likeFeed */
                        echo $likeFeed->getUser()->getUserName() . ':点亮了爱心' . PHP_EOL;
                    }

                    foreach ($scWebFeedPush->getShareFeeds() as $shareFeed) {
                        /** @var WebShareFeed $shareFeed */
                        echo $shareFeed->getUser()->getUserName() . ':分享了直播间' . PHP_EOL;
                    }

                    foreach ($scWebFeedPush->getSystemNoticeFeeds() as $systemFeed) {
                        /** @var WebSystemNoticeFeed $systemFeed */
                        echo '系统信息:' . $systemFeed->getUser()->getUserName() . ':' . $systemFeed->getContent() . PHP_EOL;
                    }


                }
//                elseif ($socketMessage->getPayloadType() == 101) {
//
//                }
            } catch (Throwable $e) {
                echo $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
            }

            return true;
        };

    }
}