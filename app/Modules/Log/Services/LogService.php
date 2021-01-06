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
use App\Modules\Log\Requests\OperateLogSearchRequest;
use App\Modules\Log\Requests\AdminLoginLogSearchRequest;
use Seffeng\LaravelHelpers\Helpers\Arr;
use App\Common\Constants\FormatConst;
use Illuminate\Support\Carbon;
use App\Common\Constants\TypeConst;

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

    /**
     *
     * @author zxf
     * @date   2021年1月6日
     * @param AdminLoginLogSearchRequest $form
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAdminLoginLogPaginate(AdminLoginLogSearchRequest $form)
    {
        /**
         *
         * @var AdminLoginLog $query
         */
        $query = AdminLoginLog::on()->with($form->getWith());
        if ($id = $form->getFillItems('id')) {
            $query->byId($id);
        }
        if ($adminId = $form->getFillItems('adminId')) {
            $query->byStatusId($adminId);
        }
        if ($username = $form->getFillItems('username')) {
            $query->whereHas('admin', function($query) use ($username) {
                $query->likeUsername($username);
            });
        }
        if ($statusId = $form->getFillItems('statusId')) {
            $query->byStatusId($statusId);
        }
        if ($typeId = $form->getFillItems('typeId')) {
            $query->byTypeId($typeId);
        }
        if ($createdStartAt = $form->getFillItems('startDate')) {
            $query->where('created_at', '>=', strtotime($createdStartAt));
        }
        if ($createdEndAt = $form->getFillItems('endDate')) {
            $query->where('created_at', '<=', strtotime($createdEndAt));
        }

        if ($orderItems = $form->getOrderBy()) {
            foreach ($orderItems as $attribute => $order) {
                $query->orderBy($attribute, $order);
            }
        } else {
            $query->orderBy('id', TypeConst::ORDERBY_DESC);
        }
        return $query->paginate($form->getPerPage());
    }

    /**
     *
     * @author zxf
     * @date   2021年1月6日
     * @param  AdminLoginLogSearchRequest $form
     * @return array
     */
    public function getAdminLoginLogStore(AdminLoginLogSearchRequest $form)
    {
        $paginator = $this->getAdminLoginLogPaginate($form);
        $items = [];
        /**
         *
         * @var AdminLoginLog $model
         */
        if ($paginator) foreach ($paginator as $model) {
            $admin = $model->admin;
            $items[] = $this->filterByFillable([
                'id' => $model->id,
                'username' => Arr::get($admin, 'username', ''),
                'statusId' => $model->status_id,
                'statusName' => $model->getStatus()->getName(),
                'statusIsSuccess' => $model->getStatus()->isSuccess(),
                'typeId' => $model->type_id,
                'typeName' => $model->getType()->getName(),
                'content' => $model->content,
                'createDate' => Carbon::parse($model->created_at)->format(FormatConst::DATE_YMDHI),
            ]);
        }
        return [
            'items' => $items,
            'page' => $this->getPaginate($paginator)
        ];
    }

    /**
     *
     * @author zxf
     * @date   2021年1月6日
     * @param OperateLogSearchRequest $form
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getOperateLogPaginate(OperateLogSearchRequest $form)
    {
        /**
         *
         * @var OperateLog $query
         */
        $query = OperateLog::on()->with($form->getWith());
        if ($id = $form->getFillItems('id')) {
            $query->byId($id);
        }
        if ($resId = $form->getFillItems('resId')) {
            $query->byResId($resId);
        }
        if ($moduleId = $form->getFillItems('moduleId')) {
            $query->byModuleId($moduleId);
        }
        if ($operatorId = $form->getFillItems('operatorId')) {
            $query->byOperatorId($operatorId);
        }
        if ($username = $form->getFillItems('username')) {
            $query->whereHas('operator', function($query) use ($username) {
                $query->byUsername($username);
            });
        }
        if ($statusId = $form->getFillItems('statusId')) {
            $query->byStatusId($statusId);
        }
        if ($typeId = $form->getFillItems('typeId')) {
            $query->byTypeId($typeId);
        }
        if ($createdStartAt = $form->getFillItems('startDate')) {
            $query->where('created_at', '>=', strtotime($createdStartAt));
        }
        if ($createdEndAt = $form->getFillItems('endDate')) {
            $query->where('created_at', '<=', strtotime($createdEndAt));
        }

        if ($orderItems = $form->getOrderBy()) {
            foreach ($orderItems as $attribute => $order) {
                $query->orderBy($attribute, $order);
            }
        } else {
            $query->orderBy('id', TypeConst::ORDERBY_DESC);
        }
        return $query->paginate($form->getPerPage());
    }

    /**
     *
     * @author zxf
     * @date   2021年1月6日
     * @param OperateLogSearchRequest $form
     * @return array
     */
    public function getOperateLogStore(OperateLogSearchRequest $form)
    {
        $paginator = $this->getOperateLogPaginate($form);
        $items = [];
        /**
         *
         * @var OperateLog $model
         */
        if ($paginator) foreach ($paginator as $model) {
            $operator = $model->operator;
            $items[] = $this->filterByFillable([
                'id' => $model->id,
                'operatorId' => Arr::get($operator, 'id', ''),
                'username' => Arr::get($operator, 'username', ''),
                'statusId' => $model->status_id,
                'statusName' => $model->getStatus()->getName(),
                'statusIsSuccess' => $model->getStatus()->isSuccess(),
                'typeId' => $model->type_id,
                'typeName' => $model->getType()->getName(),
                'fromId' => $model->getFrom()->getValue(),
                'fromName' => $model->getFrom()->getName(),
                'content' => $model->content,
                'createDate' => Carbon::parse($model->created_at)->format(FormatConst::DATE_YMDHI),
            ]);
        }
        return [
            'items' => $items,
            'page' => $this->getPaginate($paginator)
        ];
    }
}
