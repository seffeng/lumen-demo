<?php

namespace App\Common\Base;

use App\Common\Constants\ErrorConst;
use Illuminate\Support\Facades\Request;
use Seffeng\LaravelHelpers\Helpers\Arr;

/**
 *
 * @author zxf
 * @date    2019年11月15日
 */
class Controller extends \Seffeng\Basics\Base\Controller
{
    /**
     * 重新定义错误常量类
     * @var ErrorConst
     */
    protected $errorClass = ErrorConst::class;

    /**
     *
     * {@inheritDoc}
     * @see \Seffeng\Basics\Base\Controller::responseSuccess()
     */
    public function responseSuccess($data = [], string $message = 'success', array $headers = [])
    {
        $customHeaders = [];
        if ($token = Request::header('Refresh-Token')) {
            $customHeaders['Refresh-Token'] = $token;
        }
        $headers = Arr::merge($headers, $customHeaders);
        return parent::responseSuccess($data, $message, $headers);
    }
}
