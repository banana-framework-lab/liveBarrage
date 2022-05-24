<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: KS.proto

namespace KuaiShouLive;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>KuaiShouLive.SCWebAuthorPause</code>
 */
class SCWebAuthorPause extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint64 time = 1;</code>
     */
    private $time = 0;
    /**
     * Generated from protobuf field <code>.KuaiShouLive.WebPauseType pauseType = 2;</code>
     */
    private $pauseType = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int|string $time
     *     @type int $pauseType
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\KS::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>uint64 time = 1;</code>
     * @return int|string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Generated from protobuf field <code>uint64 time = 1;</code>
     * @param int|string $var
     * @return $this
     */
    public function setTime($var)
    {
        GPBUtil::checkUint64($var);
        $this->time = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.KuaiShouLive.WebPauseType pauseType = 2;</code>
     * @return int
     */
    public function getPauseType()
    {
        return $this->pauseType;
    }

    /**
     * Generated from protobuf field <code>.KuaiShouLive.WebPauseType pauseType = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setPauseType($var)
    {
        GPBUtil::checkEnum($var, \KuaiShouLive\WebPauseType::class);
        $this->pauseType = $var;

        return $this;
    }

}

