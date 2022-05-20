<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: KS.proto

namespace KuaiShouPack;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>KuaiShouPack.WebSystemNoticeFeed</code>
 */
class WebSystemNoticeFeed extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string id = 1;</code>
     */
    private $id = '';
    /**
     * Generated from protobuf field <code>.KuaiShouPack.SimpleUserInfo user = 2;</code>
     */
    private $user = null;
    /**
     * Generated from protobuf field <code>uint64 time = 3;</code>
     */
    private $time = 0;
    /**
     * Generated from protobuf field <code>string content = 4;</code>
     */
    private $content = '';
    /**
     * Generated from protobuf field <code>uint64 displayDuration = 5;</code>
     */
    private $displayDuration = 0;
    /**
     * Generated from protobuf field <code>uint64 sortRank = 6;</code>
     */
    private $sortRank = 0;
    /**
     * Generated from protobuf field <code>.KuaiShouPack.WebSystemNoticeFeed.DisplayType displayType = 7;</code>
     */
    private $displayType = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $id
     *     @type \KuaiShouPack\SimpleUserInfo $user
     *     @type int|string $time
     *     @type string $content
     *     @type int|string $displayDuration
     *     @type int|string $sortRank
     *     @type int $displayType
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
     * Generated from protobuf field <code>.KuaiShouPack.SimpleUserInfo user = 2;</code>
     * @return \KuaiShouPack\SimpleUserInfo
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Generated from protobuf field <code>.KuaiShouPack.SimpleUserInfo user = 2;</code>
     * @param \KuaiShouPack\SimpleUserInfo $var
     * @return $this
     */
    public function setUser($var)
    {
        GPBUtil::checkMessage($var, \KuaiShouPack\SimpleUserInfo::class);
        $this->user = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 time = 3;</code>
     * @return int|string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Generated from protobuf field <code>uint64 time = 3;</code>
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
     * Generated from protobuf field <code>string content = 4;</code>
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Generated from protobuf field <code>string content = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setContent($var)
    {
        GPBUtil::checkString($var, True);
        $this->content = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 displayDuration = 5;</code>
     * @return int|string
     */
    public function getDisplayDuration()
    {
        return $this->displayDuration;
    }

    /**
     * Generated from protobuf field <code>uint64 displayDuration = 5;</code>
     * @param int|string $var
     * @return $this
     */
    public function setDisplayDuration($var)
    {
        GPBUtil::checkUint64($var);
        $this->displayDuration = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 sortRank = 6;</code>
     * @return int|string
     */
    public function getSortRank()
    {
        return $this->sortRank;
    }

    /**
     * Generated from protobuf field <code>uint64 sortRank = 6;</code>
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
     * Generated from protobuf field <code>.KuaiShouPack.WebSystemNoticeFeed.DisplayType displayType = 7;</code>
     * @return int
     */
    public function getDisplayType()
    {
        return $this->displayType;
    }

    /**
     * Generated from protobuf field <code>.KuaiShouPack.WebSystemNoticeFeed.DisplayType displayType = 7;</code>
     * @param int $var
     * @return $this
     */
    public function setDisplayType($var)
    {
        GPBUtil::checkEnum($var, \KuaiShouPack\WebSystemNoticeFeed_DisplayType::class);
        $this->displayType = $var;

        return $this;
    }

}

