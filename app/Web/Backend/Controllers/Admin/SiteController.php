<?php

namespace App\Web\Backend\Controllers\Admin;

use Illuminate\Http\Request;
use App\Web\Backend\Common\Controller;
use App\Modules\Admin\Services\AdminService;
use App\Web\Backend\Requests\Admin\AdminSearchRequest;
use Illuminate\Support\Facades\Validator;
use App\Web\Backend\Requests\Admin\AdminCreateRequest;
use App\Web\Backend\Requests\Admin\AdminUpdateRequest;
use App\Modules\Admin\Exceptions\AdminException;
use App\Web\Backend\Requests\Admin\AdminDeleteRequest;
use App\Web\Backend\Requests\Admin\AdminStatusRequest;

class SiteController extends Controller
{
    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $form = $this->getSearchRequest();
            $form->load($request->input());
            $perPage = $request->get($form->getPerPageName());
            $perPage > 0 && $form->setPerPage($perPage);
            $form->sortable();
            $items = $this->getService()->getAdminStore($form);
            return $this->responseSuccess($items);
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $form = $this->getCreateRequest();
            $validator = Validator::make($form->load($request->post()), $form->rules(), $form->messages(), $form->attributes());
            if ($errorItems = $form->getErrorItems($validator)) {
                return $this->responseError($errorItems['message'], $errorItems['data']);
            } else {
                if ($this->getService()->createAdmin($form)) {
                    $request->merge(['operateLogParams' => $form->getOperateLogParams()]);
                    return $this->responseSuccess([], trans('admin.createSuccess'));
                }
            }
            return $this->responseError(trans('admin.createFailure'));
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $form = $this->getUpdateRequest();
            $validator = Validator::make($form->load($request->input()), $form->rules(), $form->messages(), $form->attributes());
            if ($errorItems = $form->getErrorItems($validator)) {
                return $this->responseError($errorItems['message'], $errorItems['data']);
            } else {
                if ($this->getService()->updateAdmin($form)) {
                    $request->merge(['operateLogParams' => $form->getOperateLogParams()]);
                    return $this->responseSuccess([], trans('admin.updateSuccess'));
                }
            }
            return $this->responseError(trans('admin.updateFailure'));
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            $form = $this->getDeleteRequest();
            $validator = Validator::make($form->load($request->input()), $form->rules(), $form->messages(), $form->attributes());
            if ($errorItems = $form->getErrorItems($validator)) {
                return $this->responseError($errorItems['message'], $errorItems['data']);
            } else {
                if ($this->getService()->deleteAdmin($form)) {
                    $request->merge(['operateLogParams' => $form->getOperateLogParams()]);
                    return $this->responseSuccess([], trans('admin.deleteSuccess'));
                }
                return $this->responseError(trans('admin.deleteFailure'));
            }
        } catch (AdminException $e) {
            return $this->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function on(Request $request)
    {
        try {
            $form = $this->getStatusRequest();
            $validator = Validator::make($form->load($request->input()), $form->rules(), $form->messages(), $form->attributes());
            if ($errorItems = $form->getErrorItems($validator)) {
                return $this->responseError($errorItems['message'], $errorItems['data']);
            } else {
                if ($this->getService()->onAdmin($form)) {
                    $request->merge(['operateLogParams' => $form->getOperateLogParams()]);
                    return $this->responseSuccess([], trans('admin.onSuccess'));
                }
                return $this->responseError(trans('admin.onFailure'));
            }
        } catch (AdminException $e) {
            return $this->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function off(Request $request)
    {
        try {
            $form = $this->getStatusRequest();
            $validator = Validator::make($form->load($request->input()), $form->rules(), $form->messages(), $form->attributes());
            if ($errorItems = $form->getErrorItems($validator)) {
                return $this->responseError($errorItems['message'], $errorItems['data']);
            } else {
                if ($this->getService()->offAdmin($form)) {
                    $request->merge(['operateLogParams' => $form->getOperateLogParams()]);
                    return $this->responseSuccess([], trans('admin.offSuccess'));
                }
                return $this->responseError(trans('admin.offFailure'));
            }
        } catch (AdminException $e) {
            return $this->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @return \App\Modules\Admin\Services\AdminService
     */
    private function getService()
    {
        return new AdminService();
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @return AdminSearchRequest
     */
    private function getSearchRequest()
    {
        return new AdminSearchRequest();
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @return AdminCreateRequest
     */
    private function getCreateRequest()
    {
        return new AdminCreateRequest();
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @return AdminUpdateRequest
     */
    private function getUpdateRequest()
    {
        return new AdminUpdateRequest();
    }

    /**
     *
     * @author zxf
     * @date   2020年12月24日
     * @return AdminDeleteRequest
     */
    private function getDeleteRequest()
    {
        return new AdminDeleteRequest();
    }

    /**
     *
     * @author zxf
     * @date   2020年12月24日
     * @return AdminStatusRequest
     */
    private function getStatusRequest()
    {
        return new AdminStatusRequest();
    }
}
