<?php

namespace App\Web\Backend\Controllers\Auth;

use Illuminate\Http\Request;
use App\Web\Backend\Common\Controller;
use Illuminate\Support\Facades\Validator;
use App\Modules\Admin\Services\AdminService;
use App\Web\Backend\Requests\Admin\AdminLoginRequest;
use App\Modules\Admin\Exceptions\AdminException;
use App\Web\Backend\Requests\Admin\AdminUpdateRequest;
use Seffeng\LaravelHelpers\Helpers\Arr;
use Namshi\JOSE\JWS;
use App\Common\Constants\TypeConst;
use App\Common\Constants\ModuleConst;
use App\Modules\Admin\Exceptions\AdminPasswordException;

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
            $form = $this->getAdminLoginRequest();
            $validate = Validator::make($form->load($request->post()), $form->rules(), $form->messages(), $form->attributes());
            if ($errorItems = $form->getErrorItems($validate)) {
                return $this->responseError($errorItems['message'], $errorItems['data']);
            } else {
                if ($token = $this->getAdminService()->adminLogin($form)) {
                    $request->merge(['loginLogParams' => $form->getLoginLogParams()]);
                    return $this->responseSuccess([
                        'token' => [
                            'token' => $token,
                            'expiredAt' => Arr::get(JWS::load($token)->getPayload(), 'exp', 0)
                        ],
                        'admin' => $this->getAdminService()->getLoginAdminToArray()
                    ], trans('admin.loginSuccess'));
                }
                return $this->responseError(trans('admin.loginFailure'));
            }
        } catch (AdminPasswordException $e) {
            $request->merge(['loginLogParams' => $form->getLoginLogParams()]);
            return $this->responseError($e->getMessage());
        } catch (AdminException $e) {
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
            if ($this->getAdminService()->adminLogout()) {
                $request->merge(['loginLogParams' => ['typeId' => TypeConst::LOG_LOGOUT, 'moduleId' => ModuleConst::ADMIN]]);
                return $this->responseSuccess(['url' => '/login']);
            }
            return $this->responseError(trans('admin.logoutFailure'));
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
        return $this->responseSuccess([
            'isLogin' => $this->getAdminService()->adminIsLogin(),
            'admin' => $this->getAdminService()->getLoginAdminToArray() ?: new \stdClass()
        ]);
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
        return $this->responseSuccess($this->getAdminService()->getLoginAdminToArray() ?: new \stdClass());
    }

    /**
     *
     * @author zxf
     * @date    2019年12月25日
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $form = $this->getAdminUpdateRequest();
            $data = $request->all();
            $data['id'] = $this->getAdminService()->getAuthGuard()->id();
            $data = $form->load($data);
            $validator = Validator::make($data, $form->rules(), $form->messages(), $form->attributes());
            if ($errorItems = $form->getErrorItems($validator)) {
                return $this->responseError($errorItems['message'], $errorItems['data']);
            }
            if ($this->getAdminService()->updateAdmin($form)) {
                $request->merge(['operateLogParams' => $form->getOperateLogParams()]);
                return $this->responseSuccess([], trans('admin.selfUpdateSuccess'));
            }
            return $this->responseSuccess([], trans('admin.selfUpdateFailure'));
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年9月29日
     * @return AdminService
     */
    private function getAdminService()
    {
        return new AdminService();
    }

    /**
     *
     * @author zxf
     * @date   2019年10月20日
     * @return AdminLoginRequest
     */
    private function getAdminLoginRequest()
    {
        return new AdminLoginRequest();
    }

    /**
     *
     * @author zxf
     * @date    2019年12月25日
     * @return AdminUpdateRequest
     */
    private function getAdminUpdateRequest()
    {
        return new AdminUpdateRequest();
    }
}
