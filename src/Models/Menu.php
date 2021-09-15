<?php

namespace MBCore\BaseAdmin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    // 软删除
    use SoftDeletes;

    //设置表名
    protected $table = 'mbadmin_menus';

    //设置主键
    public $primaryKey = 'id';

    // 字段
    public  $fillable = [
        'group_id','parent_id','name','i_ico_class','link','sort','status'
    ];

    // 软删除字段
    protected $dates = ['delete_at'];
}
