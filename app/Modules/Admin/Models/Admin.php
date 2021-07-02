<?php

namespace App\Modules\Admin\Models;

use App\Common\Base\Model;
use App\Modules\Admin\Illuminate\AdminStatus;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContracts;
use Illuminate\Auth\Authenticatable;
use App\Common\Constants\StatusConst;
use App\Common\Constants\DeleteConst;
use Illuminate\Database\Eloquent\Builder;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Common\Traits\DeleteTrait;

/**
 *
 * @date    2019年9月25日
 * @property integer $id
 * @property string $username
 * @property integer $status_id
 * @property integer $delete_id
 * @method static Admin byId(int $id)
 * @method static Admin byUsername(string $username)
 * @method static Admin likeUsername(string $username, bool $left = false)
 */
class Admin extends Model implements AuthenticatableContracts, JWTSubject
{
    use Authenticatable, DeleteTrait;

    /**
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     *
     * @var array
     */
    protected $fillable = ['username', 'password'];

    /**
     *
     * @var array
     */
    protected $casts = [
        'login_at' => 'datetime'
    ];

    /**
     * 密码加密
     * @date    2019年7月30日
     */
    public function encryptPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return $this->password;
    }

    /**
     * 密码验证
     * @date    2019年7月30日
     * @param  string $password
     * @return boolean
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     *
     * @date    2019年9月29日
     * @return AdminStatus
     */
    public function getStatus()
    {
        return new AdminStatus($this->status_id);
    }

    /**
     * 更新登录信息
     * @author zxf
     * @date   2020年12月24日
     * @param string $ipAddress
     */
    public function updateLoginValues(string $ipAddress = '')
    {
        $this->setAttribute('login_at', $this->freshTimestampString());
        $this->setAttribute('login_count', $this->login_count + 1);
        $this->setAttribute('login_ip', $ipAddress);
    }

    /**
     *
     * @author zxf
     * @date    2019年10月29日
     */
    public function loadDefaultValue()
    {
        $this->setAttribute('status_id', StatusConst::NORMAL);
        $this->setAttribute('delete_id', DeleteConst::NOT);
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月11日
     */
    public function onAdmin()
    {
        $this->setAttribute('status_id', StatusConst::NORMAL);
    }

    /**
     *
     * @author zxf
     * @date   2020年12月11日
     */
    public function offAdmin()
    {
        $this->setAttribute('status_id', StatusConst::LOCK);
    }

    /**
     *
     * @author zxf
     * @date   2020年4月3日
     * @param Builder $query
     * @param int $id
     * @return Admin
     */
    public function scopeById(Builder $query, int $id)
    {
        return $query->where('id', $id);
    }

    /**
     *
     * @author zxf
     * @date    2020年4月3日
     * @param  Builder $query
     * @param  string $username
     * @return Admin
     */
    public function scopeByUsername(Builder $query, string $username)
    {
        return $query->where('username', $username);
    }

    /**
     *
     * @author zxf
     * @date   2020年4月3日
     * @param Builder $query
     * @param string $username
     * @param boolean $left
     * @return Admin
     */
    public function scopeLikeUsername(Builder $query, string $username, bool $left = false)
    {
        return $query->where('username', 'like', ($left ? '%' : ''). $username .'%');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
