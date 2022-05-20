<?php

namespace App\Barrage\Logic\KS;

use App\Barrage\Constant\KSMap;
use App\Barrage\Object\KS\KSSpiderObject;
use Closure;
use KuaiShouPack\SCWebFeedPush;
use KuaiShouPack\SocketMessage;
use KuaiShouPack\WebComboCommentFeed;
use KuaiShouPack\WebCommentFeed;
use KuaiShouPack\WebGiftFeed;
use KuaiShouPack\WebLikeFeed;
use KuaiShouPack\WebShareFeed;
use KuaiShouPack\WebSystemNoticeFeed;
use Swoole\Coroutine\Http\Client;
use Throwable;

class KSClientLogic
{
    /**
     * @return Closure
     */
    public function getOnConnectHandler()
    {
        return function (Client $client, KSSpiderObject $spider) {
            echo '----------------------------------------开始抓取' . PHP_EOL;
            $client->push($spider->reg_data, WEBSOCKET_OPCODE_BINARY);
        };
    }

    /**
     * @return Closure
     */
    public function getHeartBeatHandler()
    {
        return function (Client $client, KSSpiderObject $spider) {
            echo '----------------------------------------发送心跳包' . PHP_EOL;

            $client->push((new KSSpiderLogic())->getLiveHeartBeatData($spider), WEBSOCKET_OPCODE_BINARY);
        };
    }

    /**
     * @return Closure
     */
    public function getLiveStreamHandler()
    {
        return function ($stream) {
            try {
                $socketMessage = new SocketMessage();
                $socketMessage->mergeFromString($stream);
                echo '----------------------------------------推流分析:' .
                    KSMap::getPayLoadTypeName($socketMessage->getPayloadType()) .
                    '_' . $socketMessage->getCompressionType().
                    $socketMessage->getCompressionType() . PHP_EOL;
                if (in_array($socketMessage->getPayloadType(), [310])) {
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
                        echo '空信息:' . $csWebEnterRoom->getPushInterval() . PHP_EOL;
                    }
                }
            } catch (Throwable $e) {
                echo $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
            }

            return true;
        };

    }
}