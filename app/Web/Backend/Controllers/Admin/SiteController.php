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
            $form = $this->getAdminSearchRequest();
            $form->load($request->input());
            $perPage = $request->get($form->getPerPageName());
            $perPage > 0 && $form->setPerPage($perPage);
            $items = $this->getAdminService()->getAdminStore($form);
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
            $form = $this->getAdminCreateRequest();
            $validator = Validator::make($form->load($request->post()), $form->rules(), $form->messages(), $form->attributes());
            if ($errorItems = $form->getErrorItems($validator)) {
                return $this->responseError($errorItems['message'], $errorItems['data']);
            } else {
                if ($this->getAdminService()->createAdmin($form)) {
                    return $this->responseSuccess([], trans('admin.create_success'));
                }
            }
            return $this->responseError(trans('admin.create_failure'));
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
            $form = $this->getAdminUpdateRequest();
            $validator = Validator::make($form->load($request->input()), $form->rules(), $form->messages(), $form->attributes());
            if ($errorItems = $form->getErrorItems($validator)) {
                return $this->responseError($errorItems['message'], $errorItems['data']);
            } else {
                if ($this->getAdminService()->updateAdmin($form)) {
                    return $this->responseSuccess([], trans('admin.update_success'));
                }
            }
            return $this->responseError(trans('admin.update_failure'));
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
            $id = $request->get('id');
            if ($this->getAdminService()->deleteAdmin($id)) {
                return $this->responseSuccess([], trans('admin.delete_success'));
            }
            return $this->responseError(trans('admin.delete_failure'));
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
    private function getAdminService()
    {
        return new AdminService();
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @return AdminSearchRequest
     */
    private function getAdminSearchRequest()
    {
        return new AdminSearchRequest();
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @return AdminCreateRequest
     */
    private function getAdminCreateRequest()
    {
        return new AdminCreateRequest();
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @return AdminUpdateRequest
     */
    private function getAdminUpdateRequest()
    {
        return new AdminUpdateRequest();
    }
}
