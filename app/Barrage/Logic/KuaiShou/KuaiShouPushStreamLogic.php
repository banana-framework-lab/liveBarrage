<?php

namespace App\Barrage\Logic\KuaiShou;

use App\Barrage\Constant\KuaiShou\KuaiShouMap;
use App\Barrage\Model\HttpModel\KuaiShou\KuaiShouModel;
use App\Barrage\Model\RedisModel\KuaiShou\KuaiShouBarrageModel;
use App\Barrage\Model\RedisModel\KuaiShou\KuaiShouUserInfoModel;
use App\Barrage\Object\KuaiShou\KuaiShouMessageObject;
use App\Barrage\Object\KuaiShou\KuaiShouSpiderObject;
use KuaiShouLive\SCWebFeedPush;
use KuaiShouLive\WebCommentFeed;
use KuaiShouLive\WebGiftFeed;
use KuaiShouLive\WebLikeFeed;
use Library\Container;

class KuaiShouPushStreamLogic
{
    /**
     * @param SCWebFeedPush $scWebFeedPush
     * @param KuaiShouSpiderObject $spider
     */
    public function handlePushStream(SCWebFeedPush $scWebFeedPush, KuaiShouSpiderObject $spider)
    {
        $kuaiShouBarrageModel = new KuaiShouBarrageModel();
        $kuaiShouUserInfoModel = new KuaiShouUserInfoModel();

        $kuaiShouHttpModel = new KuaiShouModel();

        foreach ($scWebFeedPush->getCommentFeeds() as $commentFeed) {
            /** @var WebCommentFeed $commentFeed */

            $result = $kuaiShouUserInfoModel->getUserInfo($commentFeed->getUser()->getPrincipalId());

            if (!$result) {
                $result = $kuaiShouHttpModel->getUserInfo(
                    Container::getConfig()->get('ks_barrage.cookie'),
                    $spider->live_id,
                    $commentFeed->getUser()->getPrincipalId()
                );
            }

            $data = json_decode($result, true);

            if ($data['data']['userCardInfo']['avatar'] ?? false) {
                $kuaiShouUserInfoModel->addUserInfo(
                    $commentFeed->getUser()->getPrincipalId(),
                    $data
                );
                $kuaiShouBarrageModel->addBarrage(new KuaiShouMessageObject([
                    'id' => $commentFeed->getUser()->getPrincipalId(),
                    'name' => $commentFeed->getUser()->getUserName(),
                    'headUrl' => $data['data']['userCardInfo']['avatar'],
                    'content' => $commentFeed->getContent(),
                    'type' => KuaiShouMap::MESSAGE_COMMENT_TYPE
                ]));
            } else {
                var_dump(
                    $spider->live_id,
                    $commentFeed->getUser()->getPrincipalId(),
                    $result
                );
            }
        }

//        foreach ($scWebFeedPush->getGiftFeeds() as $giftFeed) {
//            /** @var WebGiftFeed $giftFeed */
//
//            $kuaiShouBarrageModel->addBarrage(new KuaiShouMessageObject([
//                'id' => $giftFeed->getUser()->getPrincipalId(),
//                'name' => $giftFeed->getUser()->getUserName(),
//                'headUrl' => $giftFeed->getUser()->getHeadUrl(),
//                'content' => KuaiShouMap::getGiftName($giftFeed->getGiftId()),
//                'value' => 1,
//                'type' => KuaiShouMap::MESSAGE_GIFT_TYPE
//            ]));
//        }

//        foreach ($scWebFeedPush->getLikeFeeds() as $likeFeed) {
//            /** @var WebLikeFeed $likeFeed */
//
//            $kuaiShouBarrageModel->addBarrage(new KuaiShouMessageObject([
//                'id' => $likeFeed->getUser()->getPrincipalId(),
//                'name' => $likeFeed->getUser()->getUserName(),
//                'headUrl' => $likeFeed->getUser()->getHeadUrl(),
//                'content' => '',
//                'type' => KuaiShouMap::MESSAGE_LIKE_TYPE
//            ]));
//        }
    }
}