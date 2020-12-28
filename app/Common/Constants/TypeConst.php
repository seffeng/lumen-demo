<?php

namespace App\Common\Constants;

/**
 * 类型常量
 * @author zxf
 */
class TypeConst extends \Seffeng\Basics\Constants\TypeConst
{
    /**
     * 登录
     * @var integer
     */
    const LOG_LOGIN = 100;
    /**
     * 登出
     * @var integer
     */
    const LOG_LOGOUT = 101;

    /**
     * 添加
     * @var integer
     */
    const LOG_CREATE = 102;
    /**
     * 修改
     * @var integer
     */
    const LOG_UPDATE = 103;
    /**
     * 删除
     * @var integer
     */
    const LOG_DELETE = 104;
    /**
     * 解锁
     * @var integer
     */
    const LOG_UNLOCK = 105;
    /**
     * 锁定
     * @var integer
     */
    const LOG_LOCK = 106;
    /**
     * 启用
     * @var integer
     */
    const LOG_ON = 107;
    /**
     * 禁用
     * @var integer
     */
    const LOG_OFF = 108;
}
