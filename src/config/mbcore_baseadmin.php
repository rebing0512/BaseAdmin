<?php
return [
    'baseadmin_development' => false, //true false 开发模式显示菜单配置，设置false不显示开发模式
    'baseadmin_assets_path' =>'/assets/Jenson/BaseAdmin',  //设置发布后样式路径
    'baseadmin_name' => '通用管理后台系统',  //登录页面的显示系统名称名字
    'baseadmin_basemodule_name' => '',  //登录页面的显示系统名称名字
    'baseadmin_background_image' => '/img/login-background.jpg',  //登录页面的背景图片
    'baseadmin_background_image_custom' => false,// true-自定义登录页面的背景图片 false-默认登录页面的背景图片
    'baseadmin_homeView' => '',  //显示的模板内容，默认空为系统内置首页模板
    'baseadmin_homeRoute' => '',  //显示的路由内容，设置此项会覆盖baseadmin_homeView的设置，为应用最高级别  baseadmin.test
    'baseadmin_homeView_name' => '主页',  //显示的"主页"的名称，默认空为为"主页"
    'baseadmin_menuGroup' => ['测试模块1','测试模块2'], //菜单的分组
    'baseadmin_roles_home_subroles' => [
        ['name'=>'超级管理员','flag'=>'home_1'],
        ['name'=>'高级管理员','flag'=>'home_2'],
        ['name'=>'中级管理员','flag'=>'home_3'],
        ['name'=>'低级管理员','flag'=>'home_4'],
    ],
    'baseadmin_user_manage' => false,//true  是否显示用户管理，设置false不显示用户管理
    'baseadmin_user_role' => false,  // 是否显示设置用户权限，false 【默认】不显示， true 显示
    'baseadmin_system_name' => 'Jenson',  //显示公司名称，不设置默认显示MBCore
    'baseadmin_leftMenuCss' => '',  //自定义左侧菜单样式，默认''为系统内置样式
    'baseadmin_ButtonCssGroup' => [

    ],  // //自定义样式，默认[]为系统内置样式
    'baseadmin_itemLogo' => '',  //自定义项目Logo，默认''为系统内置Logo
    'baseadmin_message' => false,  //是否显示消息提醒，true显示，false 不显示，默认false
    'baseadmin_admin_manage' => true,// 是否显示管理员管理，false 不显示，默认 true 显示
    'baseadmin_admin_log_nav' => '',// 是否显示操作日志顶部导航，默认 '' 不显示， 否则加入导航路径
    'baseadmin_admin_log_add' => false,// 是否添加管理员操作日志，默认 false 不添加， true 添加
    'baseadmin_login_title' => '', //登录标题，'' 不显示，默认 '登录：' ,否则自定义
    'baseadmin_login_second_title' => '',  //登录副标题，'' 不显示，,否则自定义
    'baseadmin_login_footer' => '&copy; Jenson.COM',  //是否显示登录页底部文字，'' 不显示，默认 &copy; MBCore.COM,否则自定义
    'baseadmin_admin_pending_task' => false,// 是否显示任务提醒，true显示，false 不显示，默认 false
    'baseadmin_admin_user_login'=>false,//用户是否可以注册
    'baseadmin_admin_menu_role'=>false,//后台菜单权限
];