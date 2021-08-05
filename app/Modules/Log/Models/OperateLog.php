<?php
namespace App\Modules\Log\Models;

use App\Common\Base\Model;
use App\Modules\Log\Illuminate\LogFrom;
use App\Modules\Log\Illuminate\OperateLogType;
use App\Modules\Log\Illuminate\LogStatus;
use App\Common\Constants\DeleteConst;
use App\Common\Traits\DeleteTrait;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Modules\Log\Illuminate\OperateLogModule;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 * @author zxf
 * @date   2021年8月5日
 * @method static OperateLog byId(int $id)
 * @method static OperateLog byResId(int $resId)
 * @method static OperateLog byStatusId(int $statusId)
 * @method static OperateLog byFromId(int $fromId)
 * @method static OperateLog byTypeId(int $typeId)
 * @method static OperateLog byModuleId(int $moduleId)
 * @method static OperateLog byOperatorId(int $operatorId)
 */
class OperateLog extends Model
{
    use DeleteTrait;

    /**
     *
     * @author zxf
     * @date   2021年1月6日
     */
    protected static function boot()
    {
        parent::boot();
    }

    /**
     *
     * @var string
     */
    protected $table = 'operate_log';

    /**
     *
     * @var array
     */
    protected  $fillable = ['res_id', 'status_id', 'type_id', 'module_id', 'from_id', 'content', 'detail', 'operator_id', 'operator_ip'];

    /**
     *
     * @author zxf
     * @date   2020年6月9日
     * @return OperateLogType
     */
    public function getType()
    {
        return new OperateLogType($this->type_id);
    }

    /**
     *
     * @author zxf
     * @date   2020年6月9日
     * @return LogFrom
     */
    public function getFrom()
    {
        return new LogFrom($this->from_id);
    }

    /**
     *
     * @author zxf
     * @date   2020年6月9日
     * @return LogStatus
     */
    public function getStatus()
    {
        return new LogStatus($this->status_id);
    }

    /**
     *
     * @author zxf
     * @date   2021年1月6日
     * @return OperateLogModule
     */
    public function getLogModule()
    {
        return new OperateLogModule($this->module_id);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Seffeng\Basics\Base\Model::loadDefaultValue()
     */
    public function loadDefaultValue()
    {
        $this->setAttribute('delete_id', DeleteConst::NOT);
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2021年1月6日
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function operator()
    {
        Relation::morphMap(LogFrom::fetchOperatorClassItems());
        return $this->morphTo(null, 'from_id', 'operator_id');
    }

    /**
     *
     * @author zxf
     * @date   2021年8月5日
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function resource()
    {
        Relation::morphMap(OperateLogModule::fetchResourceClassItems());
        return $this->morphTo(null, 'module_id', 'res_id');
    }

    /**
     *
     * @author zxf
     * @date   2021年8月5日
     * @param Builder $query
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeById(Builder $query, int $id)
    {
        return $query->where('id', $id);
    }

    /**
     *
     * @author zxf
     * @date   2021年8月5日
     * @param Builder $query
     * @param int $resId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByResId(Builder $query, int $resId)
    {
        return $query->where('res_id', $resId);
    }

    /**
     *
     * @author zxf
     * @date   2021年8月5日
     * @param Builder $query
     * @param int $statusId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatusId(Builder $query, int $statusId)
    {
        return $query->where('status_id', $statusId);
    }

    /**
     *
     * @author zxf
     * @date   2021年8月5日
     * @param Builder $query
     * @param int $typeId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTypeId(Builder $query, int $typeId)
    {
        return $query->where('type_id', $typeId);
    }

    /**
     *
     * @author zxf
     * @date   2021年8月5日
     * @param Builder $query
     * @param int $fromId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByFromId(Builder $query, int $fromId)
    {
        return $query->where('from_id', $fromId);
    }

    /**
     *
     * @author zxf
     * @date   2021年8月5日
     * @param Builder $query
     * @param int $moduleId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByModuleId(Builder $query, int $moduleId)
    {
        return $query->where('module_id', $moduleId);
    }

    /**
     *
     * @author zxf
     * @date   2021年8月5日
     * @param Builder $query
     * @param int $operatorId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByOperatorId(Builder $query, int $operatorId)
    {
        return $query->where('operator_id', $operatorId);
    }
}
