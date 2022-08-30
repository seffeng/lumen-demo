<?php

namespace App\Common\Constants;

use Seffeng\LaravelHelpers\Helpers\Arr;
use Seffeng\ArrHelper\ReplaceArrayValue;

/**
 * 错误常量
 * @author zxf
 */
class ErrorConst extends \Seffeng\Basics\Constants\ErrorConst
{
    /**
     *
     * @var integer
     */
    const SERVER_ERROR = 500;

    /**
     *
     * @author zxf
     * @date    2019年12月5日
     * @return array
     */
    public static function fetchNameItems()
    {
        return Arr::merge(parent::fetchNameItems(), [
            static::NOT_FOUND => new ReplaceArrayValue('接口不存在！'),
            static::SERVER_ERROR => '服务器错误！',
        ]);
    }
}
