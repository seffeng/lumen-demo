<?php
namespace App\Modules\Log\Illuminate;

use App\Common\Illuminate\TypeIlluminate;
use App\Common\Constants\FromConst;

class LogFrom extends TypeIlluminate
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
            FromConst::FRONTEND => '前台',
            FromConst::API => 'API',
            FromConst::BACKEND => '后台',
        ];
    }
}