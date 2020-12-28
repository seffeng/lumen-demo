<?php
namespace App\Modules\Log\Requests;

use App\Common\Base\FormRequest;
use Seffeng\LaravelHelpers\Helpers\Arr;
/**
 *
 * @author zxf
 * @date    2020年12月10日
 * @property int $userId
 * @property int $statusId
 * @property int $typeId
 * @property int $fromId
 * @property int $loginIp
 * @property string $content
 */
class UserLoginLogCreateRequest extends FormRequest
{
    /**
     *
     * @var array
     */
    protected  $fillable = ['userId', 'statusId', 'typeId', 'fromId', 'loginIp', 'content'];

    /**
     *
     * {@inheritDoc}
     * @see \App\Common\Base\FormRequest::rules()
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
