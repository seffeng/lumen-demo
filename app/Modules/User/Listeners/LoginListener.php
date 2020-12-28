<?php

namespace App\Modules\User\Listeners;

use App\Modules\User\Events\LoginEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Seffeng\LaravelHelpers\Helpers\Arr;
use App\Common\Constants\StatusConst;
use App\Modules\Log\Illuminate\LogStatus;
use App\Modules\Log\Requests\UserLoginLogCreateRequest;
use App\Common\Constants\TypeConst;
use App\Common\Constants\FromConst;
use App\Modules\Log\Services\LogService;

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
        $loginStatus = new LogStatus($statusId);
        $form = new UserLoginLogCreateRequest();
        $form->skipValidator()->load([
            'userId' => $user->id,
            'statusId' => $statusId,
            'typeId' => TypeConst::LOG_LOGIN,
            'fromId' => Arr::get($data, 'fromId', FromConst::FRONTEND),
            'content' => '登录' . $loginStatus->getName() . '[username='. $user->username .']',
            'loginIp' => $ip,
        ]);
        $this->getLogService()->createUserLoginLog($form);
    }

    /**
     *
     * @author zxf
     * @date   2020年12月28日
     * @return LogService
     */
    public function getLogService()
    {
        return new LogService();
    }
}
