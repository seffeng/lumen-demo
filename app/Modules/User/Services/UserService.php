<?php

namespace App\Modules\User\Services;

use App\Modules\User\Models\User;
use App\Modules\User\Exceptions\UserNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Modules\User\Exceptions\UserException;
use App\Modules\User\Exceptions\UserStatusException;
use App\Modules\User\Events\LoginEvent;
use App\Common\Constants\DeleteConst;
use App\Modules\User\Requests\UserUpdateRequest;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Common\Exceptions\BaseException;

class UserService
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
            throw new UserNotFoundException(trans('user.not_found'));
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
            throw new UserNotFoundException(trans('user.not_found'));
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
     * @throws UserException
     * @throws UserStatusException
     * @throws UserNotFoundException
     * @throws \Exception
     * @return boolean
     */
    public function userLogin(string $username, string $password, bool $remember = false)
    {
        try {
            $userItem = $this->notNullByUsername($username);
            if ($userItem->getStatus()->getIsNormal()) {
                if ($userItem->verifyPassword($password)) {
                    $token = $this->getAuthGuard()->login($userItem, $remember);
                    event(new LoginEvent($userItem));
                    return $token;
                }
                throw new UserException(trans('user.pass_error'));
            }
            throw new UserStatusException(trans('user.forbid'));
        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException(trans('user.pass_error'));
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
            return $this->getAuthGuard()->logout();
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
            return $model->save();
        } catch (\Exception $e) {
            throw $e;
        }
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
