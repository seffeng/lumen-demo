<?php
namespace App\Modules\Log\Models;

use App\Common\Base\Model;
use App\Modules\Log\Illuminate\LoginLogType;
use App\Modules\Log\Illuminate\LogFrom;
use App\Modules\Log\Illuminate\LogStatus;
use App\Common\Constants\DeleteConst;
use App\Common\Traits\DeleteTrait;
use App\Modules\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 * @author zxf
 * @date   2021年8月5日
 * @method static AdminLoginLog byId(int $id)
 * @method static AdminLoginLog byStatusId(int $statusId)
 * @method static AdminLoginLog byTypeId(int $typeId)
 * @method static AdminLoginLog byFromId(int $fromId)
 * @method static AdminLoginLog byAdminId(int $adminId)
 */
class AdminLoginLog extends Model
{
    use DeleteTrait;

    /**
     *
     * @var string
     */
    protected $table = 'admin_login_log';

    /**
     *
     * @var array
     */
    protected $fillable = ['admin_id', 'status_id', 'type_id', 'from_id', 'login_ip', 'content'];

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @return LoginLogType
     */
    public function getType()
    {
        return new LoginLogType($this->type_id);
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @return \App\Modules\Log\Illuminate\LogFrom
     */
    public function getFrom()
    {
        return new LogFrom($this->from_id);
    }

    /**
     *
     * @author zxf
     * @date   2020年12月10日
     * @return LogStatus
     */
    public function getStatus()
    {
        return new LogStatus($this->status_id);
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
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
     * @param int $adminId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAdminId(Builder $query, int $adminId)
    {
        return $query->where('admin_id', $adminId);
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
}
