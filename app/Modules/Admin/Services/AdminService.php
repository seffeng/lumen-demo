<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Models\Admin;
use App\Modules\Admin\Exceptions\AdminNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Modules\Admin\Exceptions\AdminException;
use App\Modules\Admin\Exceptions\AdminStatusException;
use App\Modules\Admin\Events\LoginEvent;
use App\Common\Base\Service;
use App\Modules\Admin\Requests\AdminSearchRequest;
use App\Modules\Admin\Requests\AdminCreateRequest;
use App\Modules\Admin\Requests\AdminUpdateRequest;

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
            throw new AdminNotFoundException(trans('admin.not_found'));
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
            throw new AdminNotFoundException(trans('admin.not_found'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年9月29日
     * @param string $username
     * @param string $password
     * @param bool $remember
     * @throws AdminException
     * @throws AdminStatusException
     * @throws AdminNotFoundException
     * @throws \Exception
     * @return boolean
     */
    public function adminLogin(string $username, string $password, bool $remember = false)
    {
        try {
            $userItem = $this->notNullByUsername($username);
            if ($userItem->getStatus()->getIsNormal()) {
                if ($userItem->verifyPassword($password)) {
                    $token = $this->getAuthGuard()->login($userItem, $remember);
                    event(new LoginEvent($userItem));
                    return $token;
                }
                throw new AdminException(trans('admin.pass_error'));
            }
            throw new AdminStatusException(trans('admin.forbid'));
        } catch (AdminNotFoundException $e) {
            throw new AdminNotFoundException(trans('admin.pass_error'));
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
        return $this->getAuthGuard()->logout();
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
        $query->notDelete();
        return $query->orderBy('id', 'desc')->paginate($form->getPerPage());
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
        if ($paginator) foreach ($paginator as $model) {
            $items[] = $this->filterByFillable([
                'id' => $model->id,
                'username' => $model->username,
                'statusId' => $model->status_id,
                'statusName' => $model->getStatus()->getName(),
                'createDate' => date('Y-m-d H:i', $model->created_at),
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
            $model = new Admin();
            $model->fill([
                'username' => $form->getFillItems('username'),
                'password' => $form->getFillItems('password'),
            ]);
            $model->encryptPassword();
            $model->loadDefaultValue();
            return $model->save();
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
            $model = $this->notNullById($form->getFillItems('id'));
            $model->fill([
                'username' => $form->getFillItems('username'),
                'password' => $form->getFillItems('password'),
            ]);
            $model->encryptPassword();
            return $model->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @param  int $id
     * @throws \Exception
     * @return boolean
     */
    public function deleteAdmin(int $id)
    {
        try {
            $model = $this->notNullById($id);
            $model->delete();
            return $model->save();
        } catch (\Exception $e) {
            throw $e;
        }
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
