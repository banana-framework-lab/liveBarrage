<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: KS.proto

namespace KuaiShouLive;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>KuaiShouLive.WebLikeFeed</code>
 */
class WebLikeFeed extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string id = 1;</code>
     */
    private $id = '';
    /**
     * Generated from protobuf field <code>.KuaiShouLive.SimpleUserInfo user = 2;</code>
     */
    private $user = null;
    /**
     * Generated from protobuf field <code>uint64 sortRank = 3;</code>
     */
    private $sortRank = 0;
    /**
     * Generated from protobuf field <code>string deviceHash = 4;</code>
     */
    private $deviceHash = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $id
     *     @type \KuaiShouLive\SimpleUserInfo $user
     *     @type int|string $sortRank
     *     @type string $deviceHash
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\KS::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string id = 1;</code>
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Generated from protobuf field <code>string id = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setId($var)
    {
        GPBUtil::checkString($var, True);
        $this->id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.KuaiShouLive.SimpleUserInfo user = 2;</code>
     * @return \KuaiShouLive\SimpleUserInfo
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Generated from protobuf field <code>.KuaiShouLive.SimpleUserInfo user = 2;</code>
     * @param \KuaiShouLive\SimpleUserInfo $var
     * @return $this
     */
    public function setUser($var)
    {
        GPBUtil::checkMessage($var, \KuaiShouLive\SimpleUserInfo::class);
        $this->user = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 sortRank = 3;</code>
     * @return int|string
     */
    public function getSortRank()
    {
        return $this->sortRank;
    }

    /**
     * Generated from protobuf field <code>uint64 sortRank = 3;</code>
     * @param int|string $var
     * @return $this
     */
    public function setSortRank($var)
    {
        GPBUtil::checkUint64($var);
        $this->sortRank = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string deviceHash = 4;</code>
     * @return string
     */
    public function getDeviceHash()
    {
        return $this->deviceHash;
    }

    /**
     * Generated from protobuf field <code>string deviceHash = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setDeviceHash($var)
    {
        GPBUtil::checkString($var, True);
        $this->deviceHash = $var;

        return $this;
    }

}

