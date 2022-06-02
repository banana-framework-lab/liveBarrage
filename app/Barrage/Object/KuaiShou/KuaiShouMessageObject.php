<?php


namespace App\Barrage\Object\KuaiShou;


use Library\Abstracts\Object\AbstractObject;

class KuaiShouMessageObject extends AbstractObject
{
    public $id;
    public $name;
    public $headUrl;
    public $type;
    public $content = '';
    public $value = 0;
    public $time;

    public function assignHook()
    {
        if (!$this->time) {
            $this->time = time();
        }
    }
}