<?php

namespace App\Modules\Admin\Listeners;

use App\Modules\Log\Services\LogService;
use App\Common\Constants\StatusConst;
use App\Common\Constants\TypeConst;
use App\Modules\Log\Requests\AdminLoginLogCreateRequest;
use App\Modules\Admin\Events\LogoutEvent;
use App\Common\Constants\FromConst;
use Seffeng\LaravelHelpers\Helpers\Arr;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogoutListener implements ShouldQueue
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
     * @param  LogoutEvent  $event
     * @return void
     */
    public function handle(LogoutEvent $event)
    {
        $user = $event->getUser();
        $data = $event->getData();
        $form = new AdminLoginLogCreateRequest();
        $form->skipValidator()->load([
            'adminId' => $user->id,
            'statusId' => StatusConst::SUCCESS,
            'typeId' => TypeConst::LOG_LOGOUT,
            'fromId' => Arr::get($data, 'fromId', FromConst::BACKEND),
            'content' => '登出成功[username='. $user->username .']',
            'loginIp' => Arr::get($data, 'clientIp', ''),
        ]);
        $this->getLogService()->createAdminLoginLog($form);
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
}
