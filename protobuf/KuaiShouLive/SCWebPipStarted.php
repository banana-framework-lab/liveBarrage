<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: KS.proto

namespace KuaiShouLive;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>KuaiShouLive.SCWebPipStarted</code>
 */
class SCWebPipStarted extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint64 time = 1;</code>
     */
    private $time = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int|string $time
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

}

