<?php
namespace App\Modules\Log\Requests;

use App\Common\Base\FormRequest;
/**
 *
 * @author zxf
 * @date    2020年12月10日
 * @property int $adminId
 * @property int $statusId
 * @property int $typeId
 * @property string $content
 */
class AdminLoginLogSearchRequest extends FormRequest
{
    /**
     *
     * @var array
     */
    protected  $fillable = ['id', 'adminId', 'username', 'statusId', 'typeId', 'fromId', 'startDate', 'endDate', 'orderBy'];

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
