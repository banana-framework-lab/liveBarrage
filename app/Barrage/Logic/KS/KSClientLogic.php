<?php

namespace App\Barrage\Logic\KS;

use App\Barrage\Constant\KSMap;
use App\Barrage\Object\KS\KSSpiderObject;
use Closure;
use Google\Protobuf\Internal\CodedInputStream;
use KuaiShouPack\SCWebEnterRoomAck;
use KuaiShouPack\SCWebFeedPush;
use KuaiShouPack\SocketMessage;
use KuaiShouPack\WebComboCommentFeed;
use KuaiShouPack\WebCommentFeed;
use KuaiShouPack\WebGiftFeed;
use KuaiShouPack\WebLikeFeed;
use KuaiShouPack\WebShareFeed;
use KuaiShouPack\WebSystemNoticeFeed;
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

//                    Timer::after($scWebEnterRoomAck->getMinReconnectMs(), function () use ($client, $spider) {
//                        echo date('Y-m-d H:i:s') . '----------------------------------------发送reconnect' . PHP_EOL;
//                        $client->push(
//                            (new KSMessageLogic())->getEnterRoomMessage($spider)->serializeToString(),
//                            WEBSOCKET_OPCODE_BINARY
//                        );
//                    });

                    Timer::tick($scWebEnterRoomAck->getHeartbeatIntervalMs(), function () use ($client, $spider) {
                        echo '----------------------------------------' . date('Y-m-d H:i:s') . '发送心跳包' . PHP_EOL;
                        $client->push(
                            (new KSMessageLogic())->getHeartBeatMessage()->serializeToJsonString(),
                            WEBSOCKET_OPCODE_BINARY
                        );
                    });

                } elseif ($socketMessage->getPayloadType() == 310) {
                    $csWebEnterRoom = new SCWebFeedPush();
                    $csWebEnterRoom->mergeFromString($socketMessage->getPayload());


                    foreach ($csWebEnterRoom->getComboCommentFeed() as $comoCommentFeed) {
                        /** @var WebComboCommentFeed $comoCommentFeed */
                        echo '连击评论----' . $comoCommentFeed->getContent() . PHP_EOL;
                    }

                    foreach ($csWebEnterRoom->getCommentFeeds() as $commentFeed) {
                        /** @var WebCommentFeed $commentFeed */
                        echo $commentFeed->getUser()->getUserName() . ':' . $commentFeed->getContent() . PHP_EOL;
                    }

                    foreach ($csWebEnterRoom->getGiftFeeds() as $giftFeed) {
                        /** @var WebGiftFeed $giftFeed */
                        echo $giftFeed->getUser()->getUserName() . ':送出了' . KSMap::getGiftName($giftFeed->getGiftId()) . PHP_EOL;
                    }

                    foreach ($csWebEnterRoom->getLikeFeeds() as $likeFeed) {
                        /** @var WebLikeFeed $likeFeed */
                        echo $likeFeed->getUser()->getUserName() . ':点亮了爱心' . PHP_EOL;
                    }

                    foreach ($csWebEnterRoom->getShareFeeds() as $shareFeed) {
                        /** @var WebShareFeed $shareFeed */
                        echo $shareFeed->getUser()->getUserName() . ':分享了直播间' . PHP_EOL;
                    }

                    foreach ($csWebEnterRoom->getSystemNoticeFeeds() as $systemFeed) {
                        /** @var WebSystemNoticeFeed $systemFeed */
                        echo '系统信息:' . $systemFeed->getUser()->getUserName() . ':' . $systemFeed->getContent() . PHP_EOL;
                    }

                    if (
                        $csWebEnterRoom->getComboCommentFeed()->count() +
                        $csWebEnterRoom->getCommentFeeds()->count() +
                        $csWebEnterRoom->getGiftFeeds()->count() +
                        $csWebEnterRoom->getLikeFeeds()->count() +
                        $csWebEnterRoom->getSystemNoticeFeeds()->count() +
                        $csWebEnterRoom->getShareFeeds()->count() <= 0
                    ) {
                        echo '空信息:' . PHP_EOL;
                        var_dump(new CodedInputStream($stream));
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