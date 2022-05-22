<?php


namespace App\Barrage\Model\HttpModel\KS;


use Library\Abstracts\Model\AbstractHttpModel;

class KSModel extends AbstractHttpModel
{
//    const GET_LIVE_URL = 'https://m.gifshow.com/fw/live/';
//    const GET_LIVE_URL = 'https://livev.m.chenzhongtech.com/fw/live/';
    const GET_STREAM_ID_URL = 'https://live.kuaishou.com/u/';
    const GET_WEB_SOCKET_INFO_URL = "https://live.kuaishou.com/live_graphql";

    /**
     * @param $id
     * @param $cookie
     * @return string
     */
    public function getStreamId($id, $cookie)
    {
        $headers = [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.0.0 Safari/537.36',
            'Cookie:' . $cookie,
            'Host: live.kuaishou.com',
            'Referer: https://www.kuaishou.com/',
//            'origin: https://livev.m.chenzhongtech.com',
//            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
//            'Accept-Encoding:gzip, deflate, br',
//            'Accept-Language:zh-CN,zh;q=0.9',
//            'Cache-Control:keep-alive',
//            'Pragma:no-cache',
            'Sec-Fetch-Dest:document',
            'Sec-Fetch-Mode:navigate',
            'Sec-Fetch-Site:none',
            'Sec-Fetch-User:?1',
            'Upgrade-Insecure-Requests:1'
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

        return $this->getCurl(self::GET_STREAM_ID_URL . "{$id}", 20, $headers);
    }

    public function getWebSocketInfo($liveId, $streamId, $cookie)
    {
        $headers = [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.0.0 Safari/537.36',
            "Cookie: {$cookie}",
            "accept: */*",
            "Accept-Language: zh-CN,zh;q=0.9",
            "Connection: keep-alive",
//            "Content-Length: 245",
            "content-type: application/json",
            "Host: live.kuaishou.com",
            "Origin: https://live.kuaishou.com",
            "Referer: https://live.kuaishou.com/u/" . $liveId,
            "Sec-Fetch-Dest: empty",
            "Sec-Fetch-Mode: cors",
            "Sec-Fetch-Site: same-origin",
        ];

        $data = json_encode([
            "operationName" => 'WebSocketInfoQuery',
            "variables" => ["liveStreamId" => $streamId],
            "query" => "query WebSocketInfoQuery(\$liveStreamId: String) {\n  webSocketInfo(liveStreamId: \$liveStreamId) {\n    token\n    webSocketUrls\n    __typename\n  }\n}\n"
        ]);

        return $this->postCurl(self::GET_WEB_SOCKET_INFO_URL, $data, 20, $headers);
    }

}