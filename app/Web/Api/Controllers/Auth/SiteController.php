<?php

namespace App\Web\Api\Controllers\Auth;

use Illuminate\Http\Request;
use App\Modules\User\Services\UserService;
use Illuminate\Support\Facades\Validator;
use App\Modules\User\Exceptions\UserException;
use App\Web\Api\Common\Controller;
use App\Web\Api\Requests\Auth\UserLoginRequest;
use App\Web\Api\Requests\Auth\UserUpdateRequest;
use App\Common\Exceptions\BaseException;
use Seffeng\LaravelHelpers\Helpers\Arr;
use Namshi\JOSE\JWS;

class SiteController extends Controller
{
    /**
     *
     * @author zxf
     * @date   2019年10月20日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $form = $this->getUserLoginRequest();
            $validate = Validator::make($form->load($request->post()), $form->rules(), $form->messages(), $form->attributes());
            if ($errorItems = $form->getErrorItems($validate)) {
                return $this->responseError($errorItems['message'], $errorItems['data']);
            } else {
                $userService = $this->getUserService()->setAuth(config('packet.api.guard'));
                if ($token = $userService->userLogin($form->getFillItems('username'), $form->getFillItems('password'))) {
                    return $this->responseSuccess([
                        'token' => [
                            'token' => $token,
                            'expiredAt' => Arr::get(JWS::load($token)->getPayload(), 'exp', 0)
                        ],
                        'user' => $userService->getLoginUserToArray(),
                    ], trans('user.login_success'));
                }
                return $this->responseError(trans('user.login_failure'));
            }
        } catch (UserException $e) {
            return $this->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date   2019年10月19日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $userService = $this->getUserService()->setAuth(config('packet.api.guard'));
            $userService->userLogout();
            return $this->responseSuccess(['url' => '/login']);
        } catch (BaseException $e) {
            return $this->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date   2019年10月19日
     * @return \Illuminate\Http\JsonResponse
     */
    public function isLogin(Request $request)
    {
        $userService = $this->getUserService()->setAuth(config('packet.api.guard'));
        return $this->responseSuccess([
            'isLogin' => $userService->userIsLogin(),
            'user' => $userService->getLoginUserToArray() ?: new \stdClass()
        ]);
    }

    /**
     *
     * @author zxf
     * @date    2019年12月26日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $form = $this->getUserUpdateRequest();
            $data = $request->all();
            $userService = $this->getUserService()->setAuth(config('packet.api.guard'));
            $data['id'] = $userService->getAuthGuard()->id();
            $data = $form->load($data);
            $validator = Validator::make($data, $form->rules(), $form->messages(), $form->attributes());
            if ($errorItems = $form->getErrorItems($validator)) {
                return $this->responseError($errorItems['message'], $errorItems['data']);
            }
            if ($userService->updateUser($form)) {
                return $this->responseSuccess([], trans('user.self_update_success'));
            }
            return $this->responseSuccess([], trans('user.self_update_failure'));
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年3月23日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(Request $request)
    {
        $userService = $this->getUserService()->setAuth(config('packet.api.guard'));
        return $this->responseSuccess($userService->getLoginUserToArray() ?: new \stdClass());
    }

    /**
     *
     * @author zxf
     * @date    2019年9月29日
     * @return UserService
     */
    private function getUserService()
    {
        return new UserService();
    }

    /**
     *
     * @author zxf
     * @date   2019年10月20日
     * @return UserLoginRequest
     */
    private function getUserLoginRequest()
    {
        return new UserLoginRequest();
    }

    /**
     *
     * @author zxf
     * @date    2019年12月26日
     * @return UserUpdateRequest
     */
    private function getUserUpdateRequest()
    {
        return new UserUpdateRequest();
    }
}
