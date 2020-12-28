<?php

namespace App\Modules\Log\Illuminate;

use App\Common\Illuminate\TypeIlluminate;
use App\Common\Constants\ModuleConst;

class OperateLogModule extends TypeIlluminate
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
            ModuleConst::ADMIN => '管理员',
            ModuleConst::USER => '用户',
        ];
    }
}
