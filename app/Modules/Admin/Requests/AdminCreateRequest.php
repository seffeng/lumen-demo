<?php

namespace App\Modules\Admin\Requests;

use App\Common\Base\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\Admin\Models\Admin;
use App\Common\Constants\DeleteConst;
use App\Common\Rules\Password;
/**
 *
 * @author zxf
 * @date    2019年10月29日
 * @property int $password
 * @property string $username
 */
class AdminCreateRequest extends FormRequest
{
    /**
     *
     * @var array
     */
    protected  $fillable = ['username', 'password'];

    /**
     *
     * {@inheritDoc}
     * @see \App\Common\Base\FormRequest::rules()
     */
    public function rules()
    {
        return [
            'username' => [
                'required',
                'min:5',
                'max:16',
                Rule::unique((new Admin())->getTable())->where(function ($query) {
                    return $query->where('delete_id', DeleteConst::NOT);
                })
            ],
            'password' => [
                'required',
                'between:6,20',
                new Password()
            ],
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Common\Base\FormRequest::messages()
     */
    public function messages()
    {
        return [
            'required' => trans('common.required'),
            'min' => trans('common.min'),
            'max' => trans('common.max'),
            'between' => trans('common.between'),
            'unique' => trans('common.unique'),
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Common\Base\FormRequest::attributes()
     */
    public function attributes()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
        ];
    }
}
