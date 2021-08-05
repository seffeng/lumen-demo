<?php

namespace App\Modules\Log\Illuminate;

use App\Common\Illuminate\TypeIlluminate;
use App\Common\Constants\ModuleConst;
use App\Modules\Admin\Models\Admin;
use App\Modules\User\Models\User;

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

    /**
     *
     * @author zxf
     * @date   2021年8月5日
     * @return string[]
     */
    public static function fetchResourceClassItems()
    {
        return [
            ModuleConst::ADMIN => Admin::class,
            ModuleConst::USER => User::class,
        ];
    }
}
