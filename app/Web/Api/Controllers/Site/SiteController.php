<?php

namespace App\Web\Api\Controllers\Site;

use App\Web\Backend\Common\Controller;
use Illuminate\Http\Request;
use App\Common\Actions\DownListAction;

class SiteController extends Controller
{
    /**
     *
     * @author zxf
     * @date   2019年10月19日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDownList(Request $request)
    {
        try {
            $data = $this->getDownListAction()->run($request);
            return $this->responseSuccess($data);
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年12月25日
     * @return \App\Common\Actions\DownListAction
     */
    private function getDownListAction()
    {
        return new DownListAction();
    }
}
