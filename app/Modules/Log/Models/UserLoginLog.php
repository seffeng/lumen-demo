<?php
namespace App\Modules\Log\Models;

use App\Common\Base\Model;
use App\Modules\Log\Illuminate\LoginLogType;
use App\Modules\Log\Illuminate\LogFrom;
use App\Modules\Log\Illuminate\LogStatus;
use App\Common\Constants\DeleteConst;

class UserLoginLog extends Model
{
    /**
     *
     * @var string
     */
    protected $table = 'user_login_log';

    /**
     *
     * @var array
     */
    protected $fillable = ['user_id', 'status_id', 'type_id', 'from_id', 'login_ip', 'content'];

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
    }
}
