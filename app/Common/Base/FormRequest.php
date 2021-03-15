<?php

namespace App\Common\Base;

use Seffeng\LaravelHelpers\Helpers\Arr;

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

    /**
     *
     * @var array
     */
    protected $operateLogPamams = [];

    /**
     *
     * @var integer
     */
    protected $loginLogTypeId;
    /**
     *
     * @var integer
     */
    protected $loginLogModuleId;
    /**
     *
     * @var array
     */
    protected $loginLogData;

    /**
     *
     * {@inheritDoc}
     * @see \Seffeng\Basics\Base\FormRequest::messages()
     */
    public function messages()
    {
        return Arr::merge(parent::messages(), [
            'required' => trans('common.required'),
            'min' => trans('common.min'),
            'max' => trans('common.max'),
            'between' => trans('common.between'),
            'unique' => trans('common.unique'),
            'integer' => trans('common.integer'),
            'string' => trans('common.string'),
            'in' => trans('common.in'),
        ]);
    }

    /**
     *
     * @author zxf
     * @date   2020年12月24日
     * @return array
     */
    public function getOperateLogParams()
    {
        return $this->operateLogPamams;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月24日
     * @param mixed $operateLogModel
     * @param int $operateLogTypeId
     * @param int $operateLogModuleId
     * @param array $operateLogDiffChanges
     */
    public function setOperateLogParams($operateLogModel, int $operateLogTypeId, int $operateLogModuleId, array $operateLogDiffChanges = [])
    {
        array_push($this->operateLogPamams, [
            'model' => $operateLogModel,
            'typeId' => $operateLogTypeId,
            'moduleId' => $operateLogModuleId,
            'diffChanges' => $operateLogDiffChanges
        ]);
    }

    /**
     *
     * @author zxf
     * @date   2020年12月25日
     * @param int $loginTypeId
     * @param int $loginLogModuleId
     */
    public function setLoginLogParams(int $loginTypeId, int $loginLogModuleId, array $data = [])
    {
        $this->loginLogTypeId = $loginTypeId;
        $this->loginLogModuleId = $loginLogModuleId;
        $this->loginLogData = $data;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月25日
     * @return array
     */
    public function getLoginLogParams()
    {
        return [
            'typeId' => $this->loginLogTypeId,
            'moduleId' => $this->loginLogModuleId,
            'data' => $this->loginLogData,
        ];
    }
}
