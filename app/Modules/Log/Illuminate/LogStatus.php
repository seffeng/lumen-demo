<?php
namespace App\Modules\Log\Illuminate;

use App\Common\Illuminate\StatusIlluminate;
use App\Common\Constants\StatusConst;

class LogStatus extends StatusIlluminate
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
            StatusConst::SUCCESS => '成功',
            StatusConst::FAILD => '失败'
        ];
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->getValue() == StatusConst::SUCCESS;
    }
}