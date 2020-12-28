<?php

namespace App\Common\Constants;

/**
 * 缓存KEY常量
 * @author zxf
 * @date    2020年12月10日
 */
class CacheKeyConst
{
    /**
     * 缓存时长
     * @var integer
     */
    const TTL_FIVE_MINUTE = 300;
    /**
     * 缓存时长
     * @var integer
     */
    const TTL_TEN_MINUTE = 600;
    /**
     * 缓存时长
     * @var integer
     */
    const TTL_ONE_HOUR = 3600;
    /**
     * 缓存时长
     * @var integer
     */
    const TTL_ONE_DAY = 86400;

    /**
     * 登录失败账号前缀
     * @var string
     */
    const BACKEND_LOGIN_FALID_USER = 'backend_login_faild_user:';
}
