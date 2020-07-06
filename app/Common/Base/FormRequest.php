<?php

namespace App\Common\Base;

/**
 *
 * @author zxf
 * @date    2019年11月15日
 */
class FormRequest extends \Seffeng\Basics\Base\FormRequest
{
    /**
     * fillable 参数格式
     * true-驼峰，false-下划线
     * 驼峰参数格式时$fillItems将同时存在驼峰和下划线两种值
     * @var boolean
     */
    protected $isCamel = true;
}
