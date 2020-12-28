<?php

namespace App\Http\Middleware;

use Closure;
use App\Common\Exceptions\BaseException;
use Illuminate\Support\Facades\Log;
use App\Modules\Log\Events\OperateLogCreateEvent;
use Seffeng\LaravelHelpers\Helpers\Arr;
use Illuminate\Http\Request;
use App\Common\Constants\FromConst;
use App\Modules\Admin\Services\AdminService;
use App\Modules\User\Services\UserService;

class OperateLog
{
    /**
     *
     * @author zxf
     * @date   2020年12月24日
     * @param Request $request
     * @param Closure $next
     * @param int $fromId
     * @return boolean
     */
    public function handle($request, Closure $next, int $fromId)
    {
        $response = $next($request);
        try {
            $operateLogParams = $request->operateLogParams;
            $model = Arr::get($operateLogParams, 'model');
            $model && event(new OperateLogCreateEvent($model, [
                'fromId' => $fromId,
                'typeId' => Arr::get($operateLogParams, 'typeId', 0),
                'moduleId' => Arr::get($operateLogParams, 'moduleId', 0),
                'clientIp' => $request->getClientIp(),
                'operatorId' => $this->getLoginUserId($fromId),
                'diffChanges' => Arr::get($operateLogParams, 'diffChanges', []),
            ]));
        } catch (BaseException $e) {
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return $response;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月28日
     * @param int $fromId
     * @return \Illuminate\Contracts\Auth\Authenticatable|NULL|array
     */
    private function getLoginUser(int $fromId)
    {
        try {
            if ($this->isBackend($fromId)) {
                $loginUser = $this->getAdminService()->getLoginAdmin();
            } else {
                $loginUser = $this->getUserService()->getLoginUser();
            }
            return $loginUser;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年12月28日
     * @param int $fromId
     * @return integer
     */
    private function getLoginUserId(int $fromId)
    {
        try {
            return Arr::get($this->getLoginUser($fromId), 'id', 0);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年12月28日
     * @param int $fromId
     * @return boolean
     */
    private function isBackend(int $fromId)
    {
        return $fromId === FromConst::BACKEND;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月28日
     * @return AdminService
     */
    private function getAdminService()
    {
        return new AdminService();
    }

    /**
     *
     * @author zxf
     * @date   2020年12月28日
     * @return UserService
     */
    private function getUserService()
    {
        return new UserService();
    }
}