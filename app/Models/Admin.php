<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use App\Models\Traits\HasPermission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Admin
 * @property int                                                                                                            $id
 * @property string                                                                                                         $name          用户名
 * @property string                                                                                                         $email         邮箱
 * @property string                                                                                                         $password      密码
 * @property string|null                                                                                                    $portrait      头像
 * @property int|null                                                                                                       $role_id       角色ID
 * @property int                                                                                                            $login_count   登录次数
 * @property string|null                                                                                                    $last_login_ip 最后登录IP
 * @property int                                                                                                            $status        状态，1正常 2禁止 3删除
 * @property \Illuminate\Support\Carbon|null                                                                                $created_at
 * @property \Illuminate\Support\Carbon|null                                                                                $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[]                                       $clients
 * @property-read int|null                                                                                                  $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null                                                                                                  $notifications_count
 * @property-read \App\Models\Role|null                                                                                     $role
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[]                                        $tokens
 * @property-read int|null                                                                                                  $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereLoginCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin wherePortrait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable, HasPermission;

    public const        Normal  = 1;
    public const        Hidden  = 2;
    public const        Deleted = 3;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'portrait',
        'role_id',
        'login_count',
        'last_login_ip',
        'status',
    ];


    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password'
    ];


    /**
     * 登录支持用户名和邮箱
     * @param $username
     * @return mixed
     */
    public function findForPassport($username)
    {
        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['name'] = $username;
        return self::where($credentials)->first();
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }


    /**
     * 用户关联角色反向一对一关系.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
