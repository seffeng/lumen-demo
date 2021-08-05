<?php

namespace App\Modules\User\Requests;

use App\Common\Base\FormRequest;
use Illuminate\Validation\Rule;
use App\Common\Constants\DeleteConst;
use App\Common\Rules\Password;
use App\Modules\User\Models\User;
use Seffeng\LaravelHelpers\Helpers\Arr;
/**
 *
 * @author zxf
 * @date    2019年10月29日
 * @property int $id
 * @property int $password
 * @property string $phone
 */
class UserUpdateRequest extends FormRequest
{
    /**
     *
     * @var array
     */
    protected  $fillable = ['id', 'username', 'password'];

    /**
     *
     * {@inheritDoc}
     * @see \Seffeng\Basics\Base\FormRequest::rules()
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'username' => [
                'required',
                'min:5',
                'max:16',
                Rule::unique((new User())->getTable())->where(function ($query) {
                    return $query->where('id', '<>', $this->getFillItems('id'))->where('delete_id', DeleteConst::NOT);
                })
            ],
            'password' => [
                'nullable',
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
        return Arr::merge(parent::messages(), [
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Seffeng\Basics\Base\FormRequest::attributes()
     */
    public function attributes()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
        ];
    }
}
