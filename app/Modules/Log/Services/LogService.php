<?php
namespace App\Modules\Log\Services;

use App\Common\Base\Service;
use App\Modules\Log\Models\AdminLoginLog;
use App\Modules\Log\Requests\AdminLoginLogCreateRequest;
use App\Modules\Log\Requests\OperateLogCreateRequest;
use App\Modules\Log\Models\OperateLog;
use Seffeng\LaravelHelpers\Helpers\Json;
use App\Modules\Log\Models\UserLoginLog;
use App\Modules\Log\Requests\UserLoginLogCreateRequest;
use App\Modules\Log\Exceptions\LoginLogException;

class LogService extends Service
{
    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @param AdminLoginLogCreateRequest $form
     * @throws \Exception
     * @return boolean
     */
    public function createAdminLoginLog(AdminLoginLogCreateRequest $form)
    {
        try {
            if ($form->getIsPass()) {
                $model = new AdminLoginLog();
                $model->fill($form->getFillItems());
                $model->loadDefaultValue();
                return $model->save();
            }
            throw new LoginLogException(trans('common.validatorError'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年12月25日
     * @param UserLoginLogCreateRequest $form
     * @throws \Exception
     * @return boolean
     */
    public function createUserLoginLog(UserLoginLogCreateRequest $form)
    {
        try {
            if ($form->getIsPass()) {
                $model = new UserLoginLog();
                $model->fill($form->getFillItems());
                $model->loadDefaultValue();
                return $model->save();
            }
            throw new LoginLogException(trans('common.validatorError'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @param OperateLogCreateRequest $form
     * @throws \Exception
     * @return boolean
     */
    public function createOperateLog(OperateLogCreateRequest $form)
    {
        try {
            if ($form->getIsPass()) {
                $model = new OperateLog();
                $form->setFillItem('detail', Json::encode($form->getFillItems('detail', [])));
                $model->fill($form->getFillItems());
                $model->loadDefaultValue();
                return $model->save();
            }
            throw new LoginLogException(trans('common.validatorError'));
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
