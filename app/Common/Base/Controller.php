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
        return parent::responseSuccess($data, $message, $this->mergeHeaders($headers));
    }

    /**
     *
     * {@inheritDoc}
     * @see \Seffeng\Basics\Base\Controller::responseError()
     */
    public function responseError(string $message, $data = [], int $code = null, array $headers = [])
    {
        return parent::responseError($message, $data, $code, $this->mergeHeaders($headers));
    }

    /**
     *
     * {@inheritDoc}
     * @see \Seffeng\Basics\Base\Controller::responseException()
     */
    public function responseException($e, array $headers = [])
    {
        return parent::responseException($e, $this->mergeHeaders($headers));
    }

    /**
     *
     * @author zxf
     * @date   2021年3月18日
     * @param array $headers
     * @return array
     */
    protected function mergeHeaders(array $headers = [])
    {
        $customHeaders = [];
        if ($token = Request::header('Refresh-Token')) {
            $customHeaders['Refresh-Token'] = $token;
            $customHeaders['Access-Control-Expose-Headers'][] = 'Refresh-Token';
        }
        return Arr::merge($headers, $customHeaders);
    }
}
