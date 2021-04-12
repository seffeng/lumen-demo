<?php

namespace App\Web\Backend\Controllers\User;

use Illuminate\Http\Request;
use App\Web\Backend\Common\Controller;
use App\Modules\User\Services\UserService;
use App\Web\Backend\Requests\User\UserSearchRequest;

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
            $items = $this->getService()->getUserStore($form);
            return $this->responseSuccess($items);
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @return UserService
     */
    private function getService()
    {
        return new UserService();
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     * @return UserSearchRequest
     */
    private function getSearchRequest()
    {
        return new UserSearchRequest();
    }
}
