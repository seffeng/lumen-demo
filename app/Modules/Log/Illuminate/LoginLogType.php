<?php
namespace App\Modules\Log\Illuminate;

use App\Common\Illuminate\TypeIlluminate;
use App\Common\Constants\TypeConst;

class LoginLogType extends TypeIlluminate
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
            TypeConst::LOG_LOGIN => '登录',
            TypeConst::LOG_LOGOUT => '登出',
        ];
    }
}
