<?php

namespace MBCore\BaseAdmin\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{

    // 软删除
    use SoftDeletes;
    protected $table = 'mbadmin_admins_log';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // 字段
    public  $fillable = [
        'admin_id','operation','ip'
    ];

    public function admin(){
        return $this->hasOne(Admin::class,'id','admin_id');
    }


}
