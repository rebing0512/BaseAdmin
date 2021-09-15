<?php
use Jenson\BaseAdmin\Middleware\BaseAdminMiddleware;
use Jenson\BaseAdmin\Middleware\BaseAdminMenuMiddleware;

Route::get('baseadmin/test', function () {
    return 'baseadmin.test welcome';
})->name("baseadmin.test")->middleware('web');
Route::get('baseadmin/rolestest', 'AdminController@rolestest')->name("baseadmin.rolestest")->middleware('web');



// Login 路由
Route::group([
    'prefix' =>'login',
    'middleware'=>'web'
],function(){
    if(config('mbcore_baseadmin.baseadmin_background_image_custom')){
        Route::get('login','LoginController@login')->name('login.login')->middleware('loginPageBGI');
    }else{
        Route::get('login','LoginController@login')->name('login.login');
    }
    Route::post('auth','LoginController@auth')->name("login.auth"); //验证登录
});




// Admin 路由
Route::group([
    'prefix' =>'admin',
    'middleware' => ['web',BaseAdminMiddleware::class]
],function() {
    //Api 接口
    Route::post('edit', 'AdminController@editsave')->name("admin.editsave"); //管理员保存
    Route::post('getRole', 'AdminController@getRole')->name("admin.getRole"); //获得权限
    Route::post('saveRole', 'AdminController@saveRole')->name("admin.saveRole"); //保存权限



    // 退出登录
    Route::get('logout', 'LoginController@logout')->name("admin.login.logout");  //退出登录

    // 控制台首页
    Route::get('index', 'AdminController@index')->name("admin.default");//控制台
    Route::get('home', 'AdminController@home')->name("admin.home");//控制台

    // 管理员账号管理
    Route::get('add', 'AdminController@add')->name("admin.add");//管理员添加
    Route::post('add', 'AdminController@addsave')->name("admin.addsave"); //管理员保存
    Route::get('list', 'AdminController@list')->name("admin.list");//管理员列表

    // 管理员-密码-相关
    Route::group([
        'prefix' =>'password',
    ],function() {
        # 修改密码
        Route::match(['post','get'],'/change', 'AdminController@change_password')->name("admin.password.change");

    });

    // 管理员操作日志相关
    Route::group([
        'prefix' =>'log',
    ],function() {
        # 操作日志列表
        Route::match(['post','get'],'list', 'AdminLogController@list')->name("admin.log.list");
        # 操作日志-删除
        Route::post('delete/{id?}', 'AdminLogController@delete')->name("admin.log.delete");
        # Excel数据导出
        Route::match(['post','get'],'excel/export','AdminLogController@excel_export')->name('admin.log.excel.export');
        # Excel数据导出-检查数据
        Route::match(['post','get'],'excel/check','AdminLogController@check_data')->name('admin.log.excel.check_data');

    });

    // 菜单相关
    Route::group([
        'prefix' => 'menu',
        'middleware' => [BaseAdminMenuMiddleware::class]
    ], function () {
        Route::get('add', 'MenuController@add')->name("menu.add");//菜单添加
        Route::post('add', 'MenuController@addsave')->name("menu.addsave"); //菜单保存
        Route::get('list', 'MenuController@list')->name("menu.list");//菜单列表
        Route::post('edit', 'MenuController@editsave')->name("menu.editsave"); //菜单保存
    });

    // 用户管理
    Route::group([
        'prefix' => 'user'
    ], function () {
        Route::match(['post','get'],'add', 'UserController@add')->name("admin.user.add");//添加
        Route::match(['post','get'],'lists', 'UserController@lists')->name("admin.user.lists");//列表
        Route::match(['post','get'],'edit/{id?}', 'UserController@edit')->name("admin.user.edit"); //保存
        Route::post('lockUser/{id?}', 'UserController@lockUser')->name("admin.user.lockUser"); //锁定用户
    });

});