<?php

namespace App\Modules\Admin\Requests;

use App\Common\Base\FormRequest;
use Seffeng\LaravelHelpers\Helpers\Arr;
/**
 *
 * @author zxf
 * @date    2020年12月24日
 * @property int $id
 */
class AdminDeleteRequest extends FormRequest
{
    /**
     *
     * @var array
     */
    protected $fillable = ['id'];

    /**
     *
     * {@inheritDoc}
     * @see \App\Common\Base\FormRequest::rules()
     */
    public function rules()
    {
        return [
            'id' => 'required|integer',
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Common\Base\FormRequest::messages()
     */
    public function messages()
    {
        return Arr::merge(parent::messages(), [
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Common\Base\FormRequest::attributes()
     */
    public function attributes()
    {
        return [
            'id' => 'ID',
        ];
    }
}
