<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: KuaiShouLive.proto

namespace KuaiShouLive;

/**
 * Protobuf type <code>KuaiShouLive.PayloadType</code>
 */
class PayloadType
{
    /**
     * Generated from protobuf enum <code>UNKNOWN = 0;</code>
     */
    const UNKNOWN = 0;
    /**
     * Generated from protobuf enum <code>CS_HEARTBEAT = 1;</code>
     */
    const CS_HEARTBEAT = 1;
    /**
     * Generated from protobuf enum <code>CS_ERROR = 3;</code>
     */
    const CS_ERROR = 3;
    /**
     * Generated from protobuf enum <code>CS_PING = 4;</code>
     */
    const CS_PING = 4;
    /**
     * Generated from protobuf enum <code>PS_HOST_INFO = 51;</code>
     */
    const PS_HOST_INFO = 51;
    /**
     * Generated from protobuf enum <code>SC_HEARTBEAT_ACK = 101;</code>
     */
    const SC_HEARTBEAT_ACK = 101;
    /**
     * Generated from protobuf enum <code>SC_ECHO = 102;</code>
     */
    const SC_ECHO = 102;
    /**
     * Generated from protobuf enum <code>SC_ERROR = 103;</code>
     */
    const SC_ERROR = 103;
    /**
     * Generated from protobuf enum <code>SC_PING_ACK = 104;</code>
     */
    const SC_PING_ACK = 104;
    /**
     * Generated from protobuf enum <code>SC_INFO = 105;</code>
     */
    const SC_INFO = 105;
    /**
     * Generated from protobuf enum <code>CS_ENTER_ROOM = 200;</code>
     */
    const CS_ENTER_ROOM = 200;
    /**
     * Generated from protobuf enum <code>CS_USER_PAUSE = 201;</code>
     */
    const CS_USER_PAUSE = 201;
    /**
     * Generated from protobuf enum <code>CS_USER_EXIT = 202;</code>
     */
    const CS_USER_EXIT = 202;
    /**
     * Generated from protobuf enum <code>CS_AUTHOR_PUSH_TRAFFIC_ZERO = 203;</code>
     */
    const CS_AUTHOR_PUSH_TRAFFIC_ZERO = 203;
    /**
     * Generated from protobuf enum <code>CS_HORSE_RACING = 204;</code>
     */
    const CS_HORSE_RACING = 204;
    /**
     * Generated from protobuf enum <code>CS_RACE_LOSE = 205;</code>
     */
    const CS_RACE_LOSE = 205;
    /**
     * Generated from protobuf enum <code>CS_VOIP_SIGNAL = 206;</code>
     */
    const CS_VOIP_SIGNAL = 206;
    /**
     * Generated from protobuf enum <code>SC_ENTER_ROOM_ACK = 300;</code>
     */
    const SC_ENTER_ROOM_ACK = 300;
    /**
     * Generated from protobuf enum <code>SC_AUTHOR_PAUSE = 301;</code>
     */
    const SC_AUTHOR_PAUSE = 301;
    /**
     * Generated from protobuf enum <code>SC_AUTHOR_RESUME = 302;</code>
     */
    const SC_AUTHOR_RESUME = 302;
    /**
     * Generated from protobuf enum <code>SC_AUTHOR_PUSH_TRAFFIC_ZERO = 303;</code>
     */
    const SC_AUTHOR_PUSH_TRAFFIC_ZERO = 303;
    /**
     * Generated from protobuf enum <code>SC_AUTHOR_HEARTBEAT_MISS = 304;</code>
     */
    const SC_AUTHOR_HEARTBEAT_MISS = 304;
    /**
     * Generated from protobuf enum <code>SC_PIP_STARTED = 305;</code>
     */
    const SC_PIP_STARTED = 305;
    /**
     * Generated from protobuf enum <code>SC_PIP_ENDED = 306;</code>
     */
    const SC_PIP_ENDED = 306;
    /**
     * Generated from protobuf enum <code>SC_HORSE_RACING_ACK = 307;</code>
     */
    const SC_HORSE_RACING_ACK = 307;
    /**
     * Generated from protobuf enum <code>SC_VOIP_SIGNAL = 308;</code>
     */
    const SC_VOIP_SIGNAL = 308;
    /**
     * Generated from protobuf enum <code>SC_FEED_PUSH = 310;</code>
     */
    const SC_FEED_PUSH = 310;
    /**
     * Generated from protobuf enum <code>SC_ASSISTANT_STATUS = 311;</code>
     */
    const SC_ASSISTANT_STATUS = 311;
    /**
     * Generated from protobuf enum <code>SC_REFRESH_WALLET = 312;</code>
     */
    const SC_REFRESH_WALLET = 312;
    /**
     * Generated from protobuf enum <code>SC_LIVE_CHAT_CALL = 320;</code>
     */
    const SC_LIVE_CHAT_CALL = 320;
    /**
     * Generated from protobuf enum <code>SC_LIVE_CHAT_CALL_ACCEPTED = 321;</code>
     */
    const SC_LIVE_CHAT_CALL_ACCEPTED = 321;
    /**
     * Generated from protobuf enum <code>SC_LIVE_CHAT_CALL_REJECTED = 322;</code>
     */
    const SC_LIVE_CHAT_CALL_REJECTED = 322;
    /**
     * Generated from protobuf enum <code>SC_LIVE_CHAT_READY = 323;</code>
     */
    const SC_LIVE_CHAT_READY = 323;
    /**
     * Generated from protobuf enum <code>SC_LIVE_CHAT_GUEST_END = 324;</code>
     */
    const SC_LIVE_CHAT_GUEST_END = 324;
    /**
     * Generated from protobuf enum <code>SC_LIVE_CHAT_ENDED = 325;</code>
     */
    const SC_LIVE_CHAT_ENDED = 325;
    /**
     * Generated from protobuf enum <code>SC_RENDERING_MAGIC_FACE_DISABLE = 326;</code>
     */
    const SC_RENDERING_MAGIC_FACE_DISABLE = 326;
    /**
     * Generated from protobuf enum <code>SC_RENDERING_MAGIC_FACE_ENABLE = 327;</code>
     */
    const SC_RENDERING_MAGIC_FACE_ENABLE = 327;
    /**
     * Generated from protobuf enum <code>SC_RED_PACK_FEED = 330;</code>
     */
    const SC_RED_PACK_FEED = 330;
    /**
     * Generated from protobuf enum <code>SC_LIVE_WATCHING_LIST = 340;</code>
     */
    const SC_LIVE_WATCHING_LIST = 340;
    /**
     * Generated from protobuf enum <code>SC_LIVE_QUIZ_QUESTION_ASKED = 350;</code>
     */
    const SC_LIVE_QUIZ_QUESTION_ASKED = 350;
    /**
     * Generated from protobuf enum <code>SC_LIVE_QUIZ_QUESTION_REVIEWED = 351;</code>
     */
    const SC_LIVE_QUIZ_QUESTION_REVIEWED = 351;
    /**
     * Generated from protobuf enum <code>SC_LIVE_QUIZ_SYNC = 352;</code>
     */
    const SC_LIVE_QUIZ_SYNC = 352;
    /**
     * Generated from protobuf enum <code>SC_LIVE_QUIZ_ENDED = 353;</code>
     */
    const SC_LIVE_QUIZ_ENDED = 353;
    /**
     * Generated from protobuf enum <code>SC_LIVE_QUIZ_WINNERS = 354;</code>
     */
    const SC_LIVE_QUIZ_WINNERS = 354;
    /**
     * Generated from protobuf enum <code>SC_SUSPECTED_VIOLATION = 355;</code>
     */
    const SC_SUSPECTED_VIOLATION = 355;
    /**
     * Generated from protobuf enum <code>SC_SHOP_OPENED = 360;</code>
     */
    const SC_SHOP_OPENED = 360;
    /**
     * Generated from protobuf enum <code>SC_SHOP_CLOSED = 361;</code>
     */
    const SC_SHOP_CLOSED = 361;
    /**
     * Generated from protobuf enum <code>SC_GUESS_OPENED = 370;</code>
     */
    const SC_GUESS_OPENED = 370;
    /**
     * Generated from protobuf enum <code>SC_GUESS_CLOSED = 371;</code>
     */
    const SC_GUESS_CLOSED = 371;
    /**
     * Generated from protobuf enum <code>SC_PK_INVITATION = 380;</code>
     */
    const SC_PK_INVITATION = 380;
    /**
     * Generated from protobuf enum <code>SC_PK_STATISTIC = 381;</code>
     */
    const SC_PK_STATISTIC = 381;
    /**
     * Generated from protobuf enum <code>SC_RIDDLE_OPENED = 390;</code>
     */
    const SC_RIDDLE_OPENED = 390;
    /**
     * Generated from protobuf enum <code>SC_RIDDLE_CLOESED = 391;</code>
     */
    const SC_RIDDLE_CLOESED = 391;
    /**
     * Generated from protobuf enum <code>SC_RIDE_CHANGED = 412;</code>
     */
    const SC_RIDE_CHANGED = 412;
    /**
     * Generated from protobuf enum <code>SC_BET_CHANGED = 441;</code>
     */
    const SC_BET_CHANGED = 441;
    /**
     * Generated from protobuf enum <code>SC_BET_CLOSED = 442;</code>
     */
    const SC_BET_CLOSED = 442;
    /**
     * Generated from protobuf enum <code>SC_LIVE_SPECIAL_ACCOUNT_CONFIG_STATE = 645;</code>
     */
    const SC_LIVE_SPECIAL_ACCOUNT_CONFIG_STATE = 645;
}

