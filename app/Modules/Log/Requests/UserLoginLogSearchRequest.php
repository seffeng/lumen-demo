<?php
namespace App\Modules\Log\Requests;

use App\Common\Base\FormRequest;
/**
 *
 * @author zxf
 * @date    2021年8月5日
 * @property int $userId
 * @property int $statusId
 * @property int $typeId
 * @property int $fromId
 */
class UserLoginLogSearchRequest extends FormRequest
{
    /**
     *
     * @var array
     */
    protected  $fillable = ['id', 'userId', 'username', 'statusId', 'typeId', 'fromId', 'startDate', 'endDate', 'orderBy'];

    /**
     *
     * {@inheritDoc}
     * @see \Seffeng\Basics\Base\FormRequest::fetchSortKeyItems()
     */
    protected function fetchSortKeyItems()
    {
        return [
            'id' => 'id',
            'createDate' => 'created_at'
        ];
    }
}
