<?php
namespace App\Modules\Log\Listeners;

use App\Modules\Log\Events\OperateLogCreateEvent;
use App\Modules\Log\Services\LogService;
use App\Modules\Log\Requests\OperateLogCreateRequest;
use App\Common\Constants\StatusConst;
use Seffeng\LaravelHelpers\Helpers\Arr;
use App\Modules\Log\Illuminate\OperateLogType;
use App\Modules\Log\Illuminate\LogStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Log\Illuminate\OperateLogModule;

class OperateLogCreateListener implements ShouldQueue
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
     * @param  OperateLogCreateEvent  $event
     * @return void
     */
    public function handle(OperateLogCreateEvent $event)
    {
        $model = $event->getModel();
        $data = $event->getData();
        $form = new OperateLogCreateRequest();
        $logType = new OperateLogType(Arr::get($data, 'typeId', 0));
        $logStatus = new LogStatus(StatusConst::SUCCESS);
        $logModule = new OperateLogModule(Arr::get($data, 'moduleId', 0));
        $content = Arr::get($data, 'content', $logModule->getName() . '：' . $logType->getName() . $logStatus->getName());
        $form->skipValidator()->load(Arr::merge($data, [
            'resId' => $model->id,
            'statusId' => $logStatus->getValue(),
            'content' => $content,
            'operatorIp' => Arr::get($event->getData(), 'clientIp', ''),
            'operatorId' => Arr::get($event->getData(), 'operatorId', 0),
            'detail' => Arr::get($data, 'diffChanges')
        ]));
        $this->getLogService()->createOperateLog($form);
    }

    /**
     *
     * @author zxf
     * @date   2020年6月9日
     * @return LogService
     */
    private function getLogService()
    {
        return new LogService();
    }
}
