<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: KS.proto

namespace KuaiShouPack;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>KuaiShouPack.SimpleUserInfo</code>
 */
class SimpleUserInfo extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string principalId = 1;</code>
     */
    private $principalId = '';
    /**
     * Generated from protobuf field <code>string userName = 2;</code>
     */
    private $userName = '';
    /**
     * Generated from protobuf field <code>string headUrl = 3;</code>
     */
    private $headUrl = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $principalId
     *     @type string $userName
     *     @type string $headUrl
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\KS::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string principalId = 1;</code>
     * @return string
     */
    public function getPrincipalId()
    {
        return $this->principalId;
    }

    /**
     * Generated from protobuf field <code>string principalId = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setPrincipalId($var)
    {
        GPBUtil::checkString($var, True);
        $this->principalId = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string userName = 2;</code>
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Generated from protobuf field <code>string userName = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setUserName($var)
    {
        GPBUtil::checkString($var, True);
        $this->userName = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string headUrl = 3;</code>
     * @return string
     */
    public function getHeadUrl()
    {
        return $this->headUrl;
    }

    /**
     * Generated from protobuf field <code>string headUrl = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setHeadUrl($var)
    {
        GPBUtil::checkString($var, True);
        $this->headUrl = $var;

        return $this;
    }

}

