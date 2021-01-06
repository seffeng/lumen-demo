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
        Relation::morphMap(LogFrom::fetchOperatorClassItems());
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
        return $this->morphTo(null, 'from_id', 'operator_id');
    }
}
