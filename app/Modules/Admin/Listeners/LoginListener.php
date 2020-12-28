<?php

namespace App\Modules\Admin\Listeners;

use App\Modules\Admin\Events\LoginEvent;
use App\Modules\Log\Services\LogService;
use App\Common\Constants\StatusConst;
use App\Common\Constants\TypeConst;
use App\Modules\Log\Requests\AdminLoginLogCreateRequest;
use App\Common\Constants\FromConst;
use Seffeng\LaravelHelpers\Helpers\Arr;
use App\Modules\Log\Illuminate\LogStatus;
use App\Common\Constants\LimitConst;
use App\Modules\Admin\Services\AdminService;
use App\Modules\Log\Events\OperateLogCreateEvent;
use App\Common\Constants\ModuleConst;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Admin\Requests\AdminStatusRequest;

class LoginListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LoginEvent  $event
     * @return void
     */
    public function handle(LoginEvent $event)
    {
        $user = $event->getUser();
        $data = $event->getData();
        $ip = Arr::get($data, 'clientIp', '');
        $user->updateLoginValues($ip);
        $user->save();

        $statusId = Arr::getValue($data, 'statusId', StatusConst::SUCCESS);
        $faildCount = Arr::getValue($data, 'faildCount', 0);

        $loginStatus = new LogStatus($statusId);
        $form = new AdminLoginLogCreateRequest();
        $form->skipValidator()->load([
            'adminId' => $user->id,
            'statusId' => $statusId,
            'typeId' => TypeConst::LOG_LOGIN,
            'fromId' => Arr::get($data, 'fromId', FromConst::BACKEND),
            'content' => '登录' . $loginStatus->getName() . '[username='. $user->username .']',
            'loginIp' => $ip,
        ]);
        $this->getLogService()->createAdminLoginLog($form);

        if ($faildCount > LimitConst::LOGIN_FAILD_MAX) {
            $form = new AdminStatusRequest();
            $form->skipValidator()->setFillItem('id', $user->id);
            if ($this->getAdminService()->offAdmin($form)) {
                event(new OperateLogCreateEvent($user, [
                    'fromId' => FromConst::BACKEND,
                    'typeId' => TypeConst::LOG_LOCK,
                    'moduleId' => ModuleConst::ADMIN,
                    'operatorId' => 0,
                    'clientIp' => $ip,
                    'content' => trans('admin.systemLocked')
                ]));
            }
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @return LogService
     */
    private function getLogService()
    {
        return new LogService();
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @return AdminService
     */
    private function getAdminService()
    {
        return new AdminService();
    }
}
