<?php
namespace App\Modules\Log\Requests;

use App\Common\Base\FormRequest;

class OperateLogCreateRequest extends FormRequest
{
    /**
     *
     * @var array
     */
    protected  $fillable = ['resId', 'statusId', 'typeId', 'fromId', 'moduleId', 'content', 'detail', 'operatorId', 'operatorIp'];
}
