<?php

namespace App\Common\Illuminate;

use App\Common\Constants\StatusConst;

class StatusIlluminate extends BaseIlluminate
{
    /**
     *
     * @author zxf
     * @date    2019年9月25日
     * @return boolean
     */
    public function getIsNormal()
    {
        return $this->getValue() == StatusConst::NORMAL;
    }

    /**
     *
     * @author zxf
     * @date    2019年9月25日
     * @return string[]
     */
    public static function fetchNameItems()
    {
        return [
            StatusConst::NORMAL => '正常',
            StatusConst::LOCK => '锁定',
        ];
    }
}
