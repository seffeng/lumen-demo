<?php

namespace App\Modules\User\Models;

use App\Common\Base\Model;
use App\Modules\User\Illuminate\UserStatus;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContracts;
use Illuminate\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Builder;
use App\Common\Constants\DeleteConst;
/**
 *
 * @date    2019年9月25日
 * @property integer $id
 * @property string $username
 * @property integer $status_id
 * @property integer $delete_id
 * @method static User byId(int $id)
 * @method static User byUsername(string $username)
 * @method static User likeUsername(string $username, bool $left = false)
 * @method static User notDelete()
 */
class User extends Model implements AuthenticatableContracts, JWTSubject
{
    use Authenticatable;

    /**
     *
     * @var string
     */
    protected $table = 'user';

    /**
     *
     * @var array
     */
    protected $fillable = ['username', 'password'];

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
     * @return \App\Modules\User\Illuminate\UserStatus
     */
    public function getStatus()
    {
        return new UserStatus($this->status_id);
    }

    /**
     * 更新登录信息
     * @author zxf
     * @date    2019年10月21日
     * @param string $ipAddress
     */
    public function updateLoginValues(string $ipAddress = '')
    {
        $this->setAttribute('login_at', time());
        $this->setAttribute('login_count', $this->login_count + 1);
        $this->setAttribute('login_ip', $ipAddress);
    }

    /**
     *
     * @author zxf
     * @date   2020年4月3日
     * @param Builder $query
     * @param int $id
     * @return User
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
     * @return User
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
     * @return User
     */
    public function scopeLikeUsername(Builder $query, string $username, bool $left = false)
    {
        return $query->where('username', 'like', ($left ? '%' : ''). $username .'%');
    }

    /**
     *
     * @author zxf
     * @date   2020年4月3日
     * @param  Builder $query
     * @return User
     */
    public function scopeNotDelete(Builder $query)
    {
        return $query->where('delete_id', DeleteConst::NOT);
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
