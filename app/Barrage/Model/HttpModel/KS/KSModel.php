<?php


namespace App\Barrage\Model\HttpModel\KS;


use Library\Abstracts\Model\AbstractHttpModel;

class KSModel extends AbstractHttpModel
{
    const GET_LIVE_URL = 'https://livev.m.chenzhongtech.com/fw/live/';

    /**
     * @param $url
     * @param $data
     * @return string
     */
    public function getLiveInfo($id, $cookie)
    {
        $headers = [
            'User-Agent:Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1',
            'Cookie:' . $cookie,
//            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
//            'Accept-Encoding:gzip, deflate, br',
//            'Accept-Language:zh-CN,zh;q=0.9',
//            'Cache-Control:keep-alive',
//            'Pragma:no-cache',
//            'Sec-Fetch-Dest:document',
//            'Sec-Fetch-Mode:navigate',
//            'Sec-Fetch-Site:none',
//            'Sec-Fetch-User:?1',
//            'Upgrade-Insecure-Requests:1',
        ];

        $data = http_build_query([
            "cc" => "share_wxms",
            "followRefer" => "151",
            "shareMethod" => "CARD",
            "kpn" => "GAME_ZONE",
            "subBiz" => "LIVE_STEARM_OUTSIDE",
            "shareId" => "16944895155523",
            "shareToken" => "X7ywsTFxbUwvoUk",
            "shareMode" => "APP",
            "originShareId" => "16944895155523",
            "shareObjectId" => "web_pc",
            "shareUrlOpened" => "0",
            "timestamp" => "1652951102079",
        ]);

        $text = $this->getCurl(self::GET_LIVE_URL . "{$id}?$data", 20, $headers);

        return $text;
    }

}