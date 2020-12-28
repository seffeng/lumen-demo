<?php
namespace App\Modules\Log\Illuminate;

use App\Common\Illuminate\TypeIlluminate;
use App\Common\Constants\TypeConst;

class OperateLogType extends TypeIlluminate
{
    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @return string[]
     */
    public static function fetchNameItems()
    {
        return [
            TypeConst::LOG_CREATE => '添加',
            TypeConst::LOG_UPDATE => '编辑',
            TypeConst::LOG_DELETE => '删除',
            TypeConst::LOG_LOCK => '锁定',
            TypeConst::LOG_UNLOCK => '解锁',
            TypeConst::LOG_ON => '启用',
            TypeConst::LOG_OFF => '禁用',
        ];
    }
}
