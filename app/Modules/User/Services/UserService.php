<?php

namespace App\Modules\User\Services;

use App\Modules\User\Models\User;
use App\Modules\User\Exceptions\UserNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Modules\User\Exceptions\UserException;
use App\Modules\User\Exceptions\UserStatusException;
use App\Common\Constants\DeleteConst;
use App\Modules\User\Requests\UserUpdateRequest;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Common\Exceptions\BaseException;
use App\Modules\User\Requests\UserLoginRequest;
use App\Common\Constants\TypeConst;
use App\Common\Constants\ModuleConst;
use App\Common\Constants\StatusConst;
use App\Modules\User\Exceptions\UserPasswordException;
use Illuminate\Support\Facades\Date;
use App\Common\Constants\FormatConst;
use App\Modules\User\Requests\UserSearchRequest;
use App\Common\Base\Service;

class UserService extends Service
{
    /**
     *
     * @var string
     */
    private $auth = 'frontend';

    /**
     *
     * @author zxf
     * @date    2019年12月26日
     * @param  int $id
     * @return User
     */
    public function getUserById(int $id)
    {
        return User::where('id', $id)->where('delete_id', DeleteConst::NOT)->first();
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @param  int $id
     * @throws UserNotFoundException
     * @throws \Exception
     * @return User
     */
    public function notNullById(int $id)
    {
        try {
            $model = $this->getUserById($id);
            if ($model) {
                return $model;
            }
            throw new UserNotFoundException(trans('user.notFound'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年9月29日
     * @param  string $username
     * @return User
     */
    public function getUserByUsername(string $username)
    {
        return User::where('username', $username)->where('delete_id', DeleteConst::NOT)->first();
    }

    /**
     *
     * @author zxf
     * @date   2019年10月19日
     * @param  string $username
     * @throws UserNotFoundException
     * @throws \Exception
     * @return \App\Modules\User\Models\User
     */
    public function notNullByUsername(string $username)
    {
        try {
            $model = $this->getUserByUsername($username);
            if ($model) {
                return $model;
            }
            throw new UserNotFoundException(trans('user.notFound'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年9月29日
     * @param UserLoginRequest $form
     * @throws UserException
     * @throws UserStatusException
     * @throws UserNotFoundException
     * @throws \Exception
     * @return boolean
     */
    public function userLogin(UserLoginRequest $form)
    {
        try {
            $userItem = $this->notNullByUsername($form->getFillItems('username'));
            if ($userItem->getStatus()->getIsNormal()) {
                if ($userItem->verifyPassword($form->getFillItems('password'))) {
                    $token = $this->getAuthGuard()->login($userItem, $form->getFillItems('remember'));
                    $form->setLoginLogParams(TypeConst::LOG_LOGIN, ModuleConst::USER);
                    return $token;
                }
                $form->setLoginLogParams(TypeConst::LOG_LOGIN, ModuleConst::USER, [
                    'model' => $userItem,
                    'statusId' => StatusConst::FAILD
                ]);
                throw new UserPasswordException(trans('user.passError'));
            }
            throw new UserStatusException(trans('user.locked'));
        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException(trans('user.passError'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年9月29日
     */
    public function userLogout()
    {
        try {
            $this->getAuthGuard()->logout();
            return true;
        } catch (TokenExpiredException $e) {
            return true;
        } catch (JWTException $e) {
            throw new BaseException($e->getMessage());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年9月29日
     * @return boolean
     */
    public function userIsLogin()
    {
        return $this->getAuthGuard()->check();
    }

    /**
     *
     * @author zxf
     * @date    2019年10月21日
     * @return \Illuminate\Contracts\Auth\Authenticatable|NULL
     */
    public function getLoginUser()
    {
        return $this->getAuthGuard()->user();
    }

    /**
     *
     * @author zxf
     * @date    2019年10月21日
     * @return NULL[]|array
     */
    public function getLoginUserToArray()
    {
        $user = $this->getLoginUser();
        if ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
            ];
        }
        return [];
    }

    /**
     *
     * @author zxf
     * @date    2019年12月26日
     * @param  UserUpdateRequest $form
     * @throws \Exception
     * @return boolean
     */
    public function updateUser(UserUpdateRequest $form)
    {
        try {
            if ($form->getIsPass()) {
                $model = $this->notNullById($form->getFillItems('id'));
                $password = $form->getFillItems('password');
                $model->fill([
                    'username' => $form->getFillItems('username'),
                ]);
                if ($password) {
                    $model->fill([
                        'password' => $form->getFillItems('password'),
                    ]);
                    $model->encryptPassword();
                }
                $diffChanges = $model->diffChanges(['username']);
                if ($model->save()) {
                    $form->setOperateLogParams($model, TypeConst::LOG_UPDATE, ModuleConst::USER, $diffChanges);
                    return true;
                }
                return false;
            }
            throw new UserException(trans('common.validatorError'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date   2021年4月12日
     * @param UserSearchRequest $form
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserPaginate(UserSearchRequest $form)
    {
        /**
         *
         * @var User $query
         */
        $query = User::on();
        if ($id = $form->getFillItems('id')) {
            $query->byId($id);
        }
        if ($username = $form->getFillItems('username')) {
            $query->likeUsername($username);
        }
        if ($createdStartAt = $form->getFillItems('startDate')) {
            $query->where('created_at', '>=', Date::parse($createdStartAt)->format((new User())->getDateFormat()));
        }
        if ($createdEndAt = $form->getFillItems('endDate')) {
            $query->where('created_at', '<=', Date::parse($createdEndAt)->addDay()->format((new User())->getDateFormat()));
        }

        if ($orderItems = $form->getOrderBy()) {
            foreach ($orderItems as $attribute => $order) {
                $query->orderBy($attribute, $order);
            }
        } else {
            $query->orderBy('id', TypeConst::ORDERBY_DESC);
        }
        return $query->paginate($form->getPerPage());
    }

    /**
     *
     * @author zxf
     * @date    2021年4月12日
     * @param  UserSearchRequest $form
     * @return array
     */
    public function getUserStore(UserSearchRequest $form)
    {
        $paginator = $this->getUserPaginate($form);
        $items = [];
        /**
         *
         * @var User $model
         */
        if ($paginator) foreach ($paginator as $model) {
            $items[] = $this->filterByFillable([
                'id' => $model->id,
                'username' => $model->username,
                'statusId' => $model->status_id,
                'statusName' => $model->getStatus()->getName(),
                'statusIsNormal' => $model->getStatus()->getIsNormal(),
                'loginDate' => Date::parse($model->login_at)->getTimestamp() > 0 ? Date::parse($model->login_at)->format(FormatConst::DATE_YMDHI) : '',
                'createDate' => Date::parse($model->created_at)->format(FormatConst::DATE_YMDHI),
                'updateDate' => Date::parse($model->updated_at)->format(FormatConst::DATE_YMDHI),
            ]);
        }
        return [
            'items' => $items,
            'page' => $this->getPaginate($paginator)
        ];
    }

    /**
     *
     * @author zxf
     * @date   2020年3月23日
     * @param string $auth
     */
    public function setAuth(string $auth)
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年3月23日
     * @return string
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     *
     * @author zxf
     * @date    2019年9月29日
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    public function getAuthGuard()
    {
        return Auth::guard($this->getAuth());
    }
}
