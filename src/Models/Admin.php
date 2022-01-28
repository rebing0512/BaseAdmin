<?php

namespace Jenson\BaseAdmin\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    use SoftDeletes;

    protected $table = 'mbadmin_admins';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public  $fillable = [
        'username','email','password','fullName','roles','last_login_time','last_login_ip','status','confirm_email',
        'remember_token','group_id'
    ];

    // 守卫
    protected $guarded = [
        'remember_token','last_login_time ','last_login_ip '
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function log(){
        return $this->hasMany(AdminLog::class,'admin_id','id');
    }
}
