<?php
namespace App\Modules\Log\Requests;

use App\Common\Base\FormRequest;
use Seffeng\LaravelHelpers\Helpers\Arr;
/**
 *
 * @author zxf
 * @date    2020年12月10日
 * @property int $admin_id
 * @property int $status_id
 * @property int $type_id
 * @property string $content
 */
class AdminLoginLogCreateRequest extends FormRequest
{
    /**
     *
     * @var array
     */
    protected  $fillable = ['adminId', 'statusId', 'typeId', 'fromId', 'loginIp', 'content'];

    /**
     *
     * {@inheritDoc}
     * @see \Seffeng\Basics\Base\FormRequest::rules()
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Common\Base\FormRequest::messages()
     */
    public function messages()
    {
        return Arr::merge(parent::messages(), []);
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Common\Base\FormRequest::attributes()
     */
    public function attributes()
    {
        return [
        ];
    }
}
