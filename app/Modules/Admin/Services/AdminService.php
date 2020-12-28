<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Models\Admin;
use App\Modules\Admin\Exceptions\AdminNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Modules\Admin\Exceptions\AdminException;
use App\Modules\Admin\Exceptions\AdminStatusException;
use App\Common\Base\Service;
use App\Modules\Admin\Requests\AdminSearchRequest;
use App\Modules\Admin\Requests\AdminCreateRequest;
use App\Modules\Admin\Requests\AdminUpdateRequest;
use Illuminate\Support\Carbon;
use App\Common\Constants\FormatConst;
use App\Common\Constants\TypeConst;
use App\Common\Constants\StatusConst;
use App\Common\Constants\CacheKeyConst;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Common\Constants\ModuleConst;
use App\Modules\Admin\Requests\AdminStatusRequest;
use App\Modules\Admin\Requests\AdminDeleteRequest;
use App\Modules\Admin\Requests\AdminLoginRequest;
use App\Modules\Admin\Exceptions\AdminPasswordException;

class AdminService extends Service
{
    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @param int $id
     * @return Admin
     */
    public function getAdminById(int $id)
    {
        return Admin::byId($id)->notDelete()->first();
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @param int $id
     * @throws AdminNotFoundException
     * @throws \Exception
     * @return Admin
     */
    public function notNullById(int $id)
    {
        try {
            $model = $this->getAdminById($id);
            if ($model) {
                return $model;
            }
            throw new AdminNotFoundException(trans('admin.notFound'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年9月29日
     * @param  string $username
     * @return Admin
     */
    public function getAdminByUsername(string $username)
    {
        return Admin::byUsername($username)->notDelete()->first();
    }

    /**
     *
     * @author zxf
     * @date   2019年10月19日
     * @param  string $username
     * @throws AdminNotFoundException
     * @throws \Exception
     * @return Admin
     */
    public function notNullByUsername(string $username)
    {
        try {
            $model = $this->getAdminByUsername($username);
            if ($model) {
                return $model;
            }
            throw new AdminNotFoundException(trans('admin.notFound'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年9月29日
     * @param AdminLoginRequest $form
     * @throws AdminException
     * @throws AdminStatusException
     * @throws AdminNotFoundException
     * @throws \Exception
     * @return boolean
     */
    public function adminLogin(AdminLoginRequest $form)
    {
        try {
            $userItem = $this->notNullByUsername($form->getFillItems('username'));
            if ($userItem->getStatus()->getIsNormal()) {
                if ($userItem->verifyPassword($form->getFillItems('password'))) {
                    $token = $this->getAuthGuard()->login($userItem, $form->getFillItems('remember'));
                    $form->setLoginLogParams(TypeConst::LOG_LOGIN, ModuleConst::ADMIN, ['model' => $userItem]);
                    return $token;
                }
                $form->setLoginLogParams(TypeConst::LOG_LOGIN, ModuleConst::ADMIN, [
                    'model' => $userItem,
                    'statusId' => StatusConst::FAILD,
                    'faildCount' => $this->getLoginFaildCount($userItem)
                ]);
                throw new AdminPasswordException(trans('admin.passError'));
            }
            throw new AdminStatusException(trans('admin.locked'));
        } catch (AdminNotFoundException $e) {
            throw new AdminNotFoundException(trans('admin.passError'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年9月29日
     */
    public function adminLogout()
    {
        try {
            $this->getAuthGuard()->logout();
            return true;
        } catch (TokenExpiredException $e) {
            return true;
        } catch (JWTException $e) {
            return false;
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
    public function adminIsLogin()
    {
        return $this->getAuthGuard()->check();
    }

    /**
     *
     * @author zxf
     * @date    2019年10月21日
     * @return \Illuminate\Contracts\Auth\Authenticatable|NULL
     */
    public function getLoginAdmin()
    {
        return $this->getAuthGuard()->user();
    }

    /**
     *
     * @author zxf
     * @date    2019年10月21日
     * @return NULL[]|array
     */
    public function getLoginAdminToArray()
    {
        $user = $this->getLoginAdmin();
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
     * @date    2020年6月7日
     * @param  AdminSearchRequest $form
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAdminPaginate(AdminSearchRequest $form)
    {
        /**
         *
         * @var Admin $query
         */
        $query = Admin::on();
        if ($id = $form->getFillItems('id')) {
            $query->byId($id);
        }
        if ($username = $form->getFillItems('username')) {
            $query->likeUsername($username);
        }
        if ($username = $form->getFillItems('username')) {
            $query->likeUsername($username);
        }
        if ($createdStartAt = $form->getFillItems('startDate')) {
            $query->where('created_at', '>=', strtotime($createdStartAt));
        }
        if ($createdEndAt = $form->getFillItems('endDate')) {
            $query->where('created_at', '<=', strtotime($createdEndAt));
        }
        $query->notDelete();

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
     * @date    2020年6月7日
     * @param  AdminSearchRequest $form
     * @return array
     */
    public function getAdminStore(AdminSearchRequest $form)
    {
        $paginator = $this->getAdminPaginate($form);
        $items = [];
        /**
         *
         * @var Admin $model
         */
        if ($paginator) foreach ($paginator as $model) {
            $items[] = $this->filterByFillable([
                'id' => $model->id,
                'username' => $model->username,
                'statusId' => $model->status_id,
                'statusName' => $model->getStatus()->getName(),
                'statusIsNormal' => $model->getStatus()->getIsNormal(),
                'createDate' => Carbon::parse($model->created_at)->format(FormatConst::DATE_YMDHI),
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
     * @date    2019年10月29日
     * @param  AdminCreateRequest $form
     * @throws \Exception
     * @return boolean
     */
    public function createAdmin(AdminCreateRequest $form)
    {
        try {
            if ($form->getIsPass()) {
                $model = new Admin();
                $model->fill([
                    'username' => $form->getFillItems('username'),
                    'password' => $form->getFillItems('password'),
                ]);
                $model->encryptPassword();
                $model->loadDefaultValue();

                if ($model->save()) {
                    $form->setOperateLogParams($model, TypeConst::LOG_CREATE, ModuleConst::ADMIN);
                    return true;
                }
                return false;
            }
            throw new AdminException(trans('common.validatorError'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年10月30日
     * @param AdminUpdateRequest $form
     * @throws \Exception
     * @return boolean
     */
    public function updateAdmin(AdminUpdateRequest $form)
    {
        try {
            if ($form->getIsPass()) {
                $model = $this->notNullById($form->getFillItems('id'));
                $password = $form->getFillItems('password');
                $model->fill(array_filter($form->getFillItems(), function($value) {
                    return $value;
                }));
                if ($password) {
                    $model->fill([
                        'password' => $password
                    ]);
                    $model->encryptPassword();
                }
                $diffChanges = $model->diffChanges(array_diff(array_keys($form->getFillItems()), ['password']));
                if ($model->save()) {
                    $form->setOperateLogParams($model, TypeConst::LOG_UPDATE, ModuleConst::ADMIN, $diffChanges);
                    return true;
                }
                return false;
            }
            throw new AdminException(trans('common.validatorError'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @param  AdminDeleteRequest $form
     * @throws \Exception
     * @return boolean
     */
    public function deleteAdmin(AdminDeleteRequest $form)
    {
        try {
            if ($form->getIsPass()) {
                $model = $this->notNullById($form->getFillItems('id'));
                $model->delete();
                if ($model->save()) {
                    $form->setOperateLogParams($model, TypeConst::LOG_DELETE, ModuleConst::ADMIN);
                    return true;
                }
                return false;
            }
            throw new AdminException(trans('common.validatorError'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @param AdminStatusRequest $form
     * @throws \Exception
     * @return boolean
     */
    public function onAdmin(AdminStatusRequest $form)
    {
        try {
            if ($form->getIsPass()) {
                $model = $this->notNullById($form->getFillItems('id'));
                $model->onAdmin();
                if ($model->save()) {
                    $form->setOperateLogParams($model, TypeConst::LOG_UNLOCK, ModuleConst::ADMIN);
                    Cache::forget($this->getLoginFailedCacheKey($model->id));
                    return true;
                }
                return false;
            }
            throw new AdminException(trans('common.validatorError'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @param AdminStatusRequest $form
     * @throws \Exception
     * @return boolean
     */
    public function offAdmin(AdminStatusRequest $form)
    {
        try {
            if ($form->getIsPass()) {
                $model = $this->notNullById($form->getFillItems('id'));
                $model->offAdmin();
                if ($model->save()) {
                    $form->setOperateLogParams($model, TypeConst::LOG_LOCK, ModuleConst::ADMIN);
                    return true;
                }
                return false;
            }
            throw new AdminException(trans('common.validatorError'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @param int $id
     * @return string
     */
    protected function getLoginFailedCacheKey(int $id)
    {
        return CacheKeyConst::BACKEND_LOGIN_FALID_USER . $id;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @param Admin $model
     * @return number
     */
    public function getLoginFaildCount(Admin $model)
    {
        $count = intval(Cache::get($this->getLoginFailedCacheKey($model->id)));
        $count++;
        Cache::put($this->getLoginFailedCacheKey($model->id), $count, $this->getLoginFaildCacheTTL());
        return $count;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @return number
     */
    private function getLoginFaildCacheTTL()
    {
        return CacheKeyConst::TTL_TEN_MINUTE;
    }

    /**
     *
     * @author zxf
     * @date    2019年9月29日
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    public function getAuthGuard()
    {
        return Auth::guard('backend');
    }
}
