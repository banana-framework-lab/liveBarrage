<?php


namespace App\Barrage\Controller\KuaiShou;


use App\Barrage\Logic\KuaiShou\KuaiShouBarrageLogic;
use Library\Abstracts\Controller\AbstractController;
use Library\Abstracts\Model\AbstractHttpModel;

class KuaiShouBarrageController extends AbstractController
{
    //http://192.168.18.56:9503/Barrage/KuaiShouBarrage/getBarrage?offset_time=1654149320000
    public function getBarrage(): array
    {
        $result = (new KuaiShouBarrageLogic())->getBarrage((int)($this->request['offset_time'] ?? microTimes()));
        return [
            'code' => AbstractHttpModel::$successCode,
            'data' => $result
        ];
    }
}