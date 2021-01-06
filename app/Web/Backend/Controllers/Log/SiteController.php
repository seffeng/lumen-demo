<?php

namespace App\Web\Backend\Controllers\Log;

use Illuminate\Http\Request;
use App\Web\Backend\Common\Controller;
use App\Modules\Log\Services\LogService;
use App\Web\Backend\Requests\Log\AdminLoginLogSearchRequest;
use App\Web\Backend\Requests\Log\OperateLogSearchRequest;

class SiteController extends Controller
{
    /**
     *
     * @author zxf
     * @date   2020年12月31日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function operateLog(Request $request)
    {
        try {
            $form = $this->getOperateLogSearchRequest();
            $form->load($request->input());
            $perPage = $request->get($form->getPerPageName());
            $perPage > 0 && $form->setPerPage($perPage);
            $form->sortable();
            $form->setWith(['operator']);
            $items = $this->getService()->getOperateLogStore($form);
            return $this->responseSuccess($items);
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年12月31日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminLoginLog(Request $request)
    {
        try {
            $form = $this->getAdminLoginLogSearchRequest();
            $form->load($request->input());
            $perPage = $request->get($form->getPerPageName());
            $perPage > 0 && $form->setPerPage($perPage);
            $form->sortable();
            $form->setWith(['admin']);
            $items = $this->getService()->getAdminLoginLogStore($form);
            return $this->responseSuccess($items);
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年12月31日
     * @return LogService
     */
    private function getService()
    {
        return new LogService();
    }

    /**
     *
     * @author zxf
     * @date   2020年12月31日
     * @return AdminLoginLogSearchRequest
     */
    private function getAdminLoginLogSearchRequest()
    {
        return new AdminLoginLogSearchRequest();
    }

    /**
     *
     * @author zxf
     * @date   2020年12月31日
     * @return OperateLogSearchRequest
     */
    private function getOperateLogSearchRequest()
    {
        return new OperateLogSearchRequest();
    }
}
