@inject('BaseAdminHelper', 'MBCore\BaseAdmin\Libraries\Helper')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title>
     	@if(config('mbcore_baseadmin.baseadmin_name'))
            {{config('mbcore_baseadmin.baseadmin_name')}}
        @else
            @yield('title')
        @endif
    </title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

    <link rel="shortcut icon" href="favicon.ico"> <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/animate.css" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/style.css?v=4.1.0" rel="stylesheet">
    @if( in_array("baseadmin_leftMenuCss",array_keys(config('mbcore_baseadmin'))) && config('mbcore_baseadmin.baseadmin_leftMenuCss') )
        <link href="{{url(config("mbcore_baseadmin.baseadmin_leftMenuCss"))}}" rel="stylesheet">
    @endif
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs" style="font-size:20px;">
                                         {{--自定义系统logo--}}
                                        @if(config('mbcore_baseadmin.baseadmin_itemLogo'))
                                            <img src="{{url(config('mbcore_baseadmin.baseadmin_itemLogo'))}}"  style="width:15% !important; height: auto; padding:0 1% 3% 0;" >
                                        @else
                                            <i class="fa fa-desktop"></i>
                                        @endif
                                        <strong class="font-bold">
                                             @if(config('mbcore_baseadmin.baseadmin_system_name'))
                                                {{config('mbcore_baseadmin.baseadmin_system_name')}}
                                             @else
                                                MBCore
                                             @endif
                                        </strong>
                                    </span>
                                </span>
                        </a>
                    </div>
                    <div class="logo-element">
                        @if(config('mbcore_baseadmin.baseadmin_system_name'))
                            {{config('mbcore_baseadmin.baseadmin_system_name')}}
                        @else
                            MBCore
                        @endif
                    </div>
                </li>

                <!---->
                @if(config('mbcore_baseadmin.baseadmin_development'))
                    @if( $BaseAdminHelper::hasRoles("menu",$rolesArr['system']) )
                        <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                            <span class="ng-scope">菜单配置中心</span>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa fa-cogs"></i>
                                <span class="nav-label">菜单管理</span>
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a class="J_menuItem" href="{{ route("menu.add") }}">菜单增加</a>
                                </li>
                                <li>
                                    <a class="J_menuItem" href="{{ route("menu.list") }}">菜单列表</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                @endif
                <!---->


                @if(config('mbcore_baseadmin.baseadmin_basemodule_name'))
                    <li class="line dk"></li>
                    <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                        <span class="ng-scope">{{config('mbcore_baseadmin.baseadmin_basemodule_name')}}</span>
                    </li>
                @endif

                @if( $BaseAdminHelper::hasRoles("home",$rolesArr['system']) )
                <li @if($status == 'home') class="active" @endif >
                    <a class="J_menuItem" href="{{route($adminHomeRoute)}}">

                        <i class="fa fa-home"></i>
                         <span class="nav-label">
                            @if(config('mbcore_baseadmin.baseadmin_homeView_name'))
                                {{config('mbcore_baseadmin.baseadmin_homeView_name')}}
                            @else
                                主页
                            @endif
                        </span>
                    </a>
                </li>
                @endif

                @if(config('mbcore_baseadmin.baseadmin_admin_manage'))
                    @if( $BaseAdminHelper::hasRoles("admin",$rolesArr['system']) )
                        <li>
                            <a href="#">
                                <i class="fa fa-user"></i>
                                <span class="nav-label">管理员管理</span>
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="nav nav-second-level">

                                @if( $BaseAdminHelper::hasRoles("admin_add",$rolesArr['system']) )
                                    <li>
                                        <a class="J_menuItem" href="{{ route("admin.add") }}">账号增加</a>
                                    </li>
                                @endif

                                @if( $BaseAdminHelper::hasRoles(["admin_list","admin_add","admin_password","admin_roles"],$rolesArr['system']) )
                                    <li>
                                        <a class="J_menuItem" href="{{ route("admin.list") }}">账号列表</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                @endif
                @if( in_array("baseadmin_user_manage",array_keys(config('mbcore_baseadmin'))) && config('mbcore_baseadmin.baseadmin_user_manage') )
                <li>
                    <a href="#">
                        <i class="fa fa-user"></i>
                        <span class="nav-label">用户管理</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">

                        @if( $BaseAdminHelper::hasRoles("admin_add",$rolesArr['system']) )
                            <li>
                                <a class="J_menuItem" href="{{ route("admin.user.add") }}">添加用户</a>
                            </li>
                        @endif

                        @if( $BaseAdminHelper::hasRoles(["admin_list","admin_add","admin_password","admin_roles"],$rolesArr['system']) )
                            <li>
                                <a class="J_menuItem" href="{{ route("admin.user.lists") }}">用户列表</a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(config('mbcore_baseadmin.baseadmin_menuGroup'))

                    @foreach (config('mbcore_baseadmin.baseadmin_menuGroup') as $key=>$val)

                        @if( $BaseAdminHelper::hasRoles("G".$key,$rolesArr['menu']) )

                                @if (isset($menus[$key]) && is_array($menus[$key]))

                                    <li class="line dk"></li>
                                    <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                                        <span class="ng-scope">{{$val}}</span>
                                    </li>

                                    @foreach ($menus[$key] as $menu)

                                    @if( $BaseAdminHelper::hasRoles($menu['id'],$rolesArr['menu']) )
                                            @if($menu['hasChild'])
                                                <li>
                                                    <a href="#">
                                                        <i class="{{ $menu['i_ico_class'] }}"></i>
                                                        <span class="nav-label">{{ $menu['name'] }}</span>
                                                        <span class="fa arrow"></span>
                                                    </a>
                                                    <ul class="nav nav-second-level">
                                                        @if (is_array($menu['nodes']))
                                                            @foreach ($menu['nodes'] as $submenu)
                                                                @if( $BaseAdminHelper::hasRoles($submenu['id'],$rolesArr['menu']) )
                                                                <li>
                                                                    <a class="J_menuItem" href="@getLinkUrl($submenu['link'])">{{ $submenu['name'] }}</a>
                                                                </li>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </li>
                                            @else
                                                <li>
                                                    <a class="J_menuItem" href="@getLinkUrl($menu['link'])">
                                                        <i class="{{ $menu['i_ico_class'] }}"></i>
                                                        <span class="nav-label">{{ $menu['name'] }} </span>
                                                    </a>
                                                </li>
                                            @endif

                                    @endif

                                    @endforeach
                                @endif

                        @endif
                    @endforeach

                @else

                    <li class="line dk"></li>
                    <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                        <span class="ng-scope">应用模块</span>
                    </li>
                    @if (is_array($menus))
                        @foreach ($menus as $menu)

                            @if( $BaseAdminHelper::hasRoles($menu['id'],$rolesArr['menu']) )
                                    @if($menu['hasChild'])
                                        <li>
                                            <a href="#">
                                                <i class="{{ $menu['i_ico_class'] }}"></i>
                                                <span class="nav-label">{{ $menu['name'] }}</span>
                                                <span class="fa arrow"></span>
                                            </a>
                                            <ul class="nav nav-second-level">
                                                @if (is_array($menu['nodes']))
                                                    @foreach ($menu['nodes'] as $submenu)
                                                        @if( $BaseAdminHelper::hasRoles($submenu['id'],$rolesArr['menu']) )
                                                        <li>
                                                            <a class="J_menuItem" href="@getLinkUrl($submenu['link'])">{{ $submenu['name'] }}</a>
                                                        </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </li>
                                    @else
                                        <li>
                                            <a class="J_menuItem" href="@getLinkUrl($menu['link'])">
                                                <i class="{{ $menu['i_ico_class'] }}"></i>
                                                <span class="nav-label">{{ $menu['name'] }} </span>
                                            </a>
                                        </li>
                                    @endif
                            @endif
                        @endforeach
                    @endif

                @endif



            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-info " href="#"><i class="fa fa-bars"></i> </a>
                    <span class="minimalize-styl-2 font-bold" id="mbcore_nav_title">
                        @if(config('mbcore_baseadmin.baseadmin_homeView_name'))
                            {{config('mbcore_baseadmin.baseadmin_homeView_name')}}
                        @else
                            主页
                        @endif
                    </span>
                    <!--
                    <form role="search" class="navbar-form-custom" method="post" action="search_results.html">
                        <div class="form-group">
                            <input type="text" placeholder="请输入您需要查找的内容 …" class="form-control" name="top-search" id="top-search">
                        </div>
                    </form>
                    -->
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    @if(config('mbcore_baseadmin.baseadmin_message'))
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-envelope"></i> <span class="label label-warning message-count">{{$message_count}}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-messages">
                                @if(count($message) > 0 )
                                    @foreach($message as $key=>$val)
                                        <li class="m-t-xs messages-li_{{$val['id']}}">
                                            <div class="dropdown-messages-box">
                                                {{--<div data-href="{{$val['url']}}" data-id="{{$val['id']}}" class="pull-left messages-click">--}}
                                                <div data-href="{{$val['url']}}" data-id="{{$val['id']}}" class="messages-click">
                                                    @if($val['user_img'])
                                                        <img alt="image" class="img-circle" src="img/a7.jpg">
                                                    @endif
                                                    <div class="media-body">
                                                        {{--<small class="pull-right">46小时前</small>--}}
                                                        <strong>{{$val['username']}}</strong> {!! $val['message_content'] !!}
                                                        <br>
                                                        <small class="text-muted">{{$val['created_at']}}</small>
                                                        {{--<small class="text-muted read" style="float: right; display: none;">已读</small>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="divider messages-li_{{$val['id']}}"></li>
                                    @endforeach
                                    <li>
                                        <div class="text-center link-block">
                                            <a class="J_menuItem" href="{{$more_messages_url}}">
                                                <i class="fa fa-envelope"></i> <strong> 查看所有消息</strong>
                                            </a>
                                        </div>
                                    </li>
                                @else
                                    <li>
                                        <div class="text-center link-block">
                                            <i class="fa fa-envelope"></i> <strong> 暂无消息</strong>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(config('mbcore_baseadmin.baseadmin_admin_pending_task'))
                        <li class="dropdown">
                            @if($task_untreated_count >0 )
                                <style>
                                    .task-count{
                                        background: red;
                                        animation: flash 1s linear infinite;
                                    }

                                    @keyframes flash{
                                        from {
                                            opacity: 0;
                                        }
                                        to {
                                            opacity: 1;
                                        }
                                    }
                                </style>
                            @endif
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-bell"></i>
                                <span class="label label-primary @if($task_untreated_count >0 )task-count @endif" >
                                    {{$task_untreated_count}}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                @if($task)
                                    @foreach($task as $key=>$val)
                                        <li>
                                            <div class="task-click" data-id="{{$val['id']}}" data-href="{{$val['url']}}">
                                                {{--<i class="fa fa-envelope fa-fw"></i> 您有16条未读消息--}}
                                                <i class="fa fa-bell"></i> {{$val['title']}}
                                                <span class="pull-right text-muted small">{!! $val['status'] !!}</span>
                                                <br>
                                                <small class="text-muted">{{$val['created_at']}}</small>
                                            </div>
                                        </li>
                                        <li class="divider"></li>
                                    @endforeach
                                    <li>
                                        <div class="text-center link-block">
                                            <a class="J_menuItem" href="{{$more_task_url}}">
                                                <strong>查看所有</strong>
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                        </div>
                                    </li>
                                @else
                                    <li>
                                        <div class="text-center link-block">
                                            <strong>暂无任务 </strong>
                                            <i class="fa fa-angle-right"></i>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i class="fa fa-user"></i> {{Session::get('username')}}
                        </a>
                        <ul class="dropdown-menu dropdown-alerts">
                            <!--
                            <li>
                                <a href="mailbox.html">
                                    <div>
                                        <i class="fa fa-envelope fa-fw"></i> 您有16条未读消息
                                        <span class="pull-right text-muted small">4分钟前</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="profile.html">
                                    <div>
                                        <i class="fa fa-qq fa-fw"></i> 3条新回复
                                        <span class="pull-right text-muted small">12分钟钱</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            -->
                            <li>
                                <div class="text-center link-block" style="float: left;">
                                    <a class="J_menuItem" href="{{ route('admin.password.change') }}">
                                        <strong>修改密码 </strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                                <div class="text-center link-block">
                                    <a class="J_menuItem" href="{{ route('admin.login.logout') }}">
                                        <strong>退出登录 </strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe id="J_iframe" name="J_iframe" width="100%" height="100%" src="@yield('content')" frameborder="0" data-id="admin_home" seamless></iframe>
        </div>
    </div>
    <!--右侧部分结束-->
</div>

<!-- 全局js -->
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/jquery.min.js?v=2.1.4"></script>
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/bootstrap.min.js?v=3.3.6"></script>
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- 自定义js -->
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/hAdmin.js?v=4.1.0"></script>
<script type="text/javascript" src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/index.js"></script>

<!-- 第三方插件 -->
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/pace/pace.min.js"></script>

<!-- layer javascript -->
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/layer/layer.min.js"></script>
<script>
    layer.config({
        extend: 'extend/layer.ext.js'
    });
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
</script>


{{--阅读消息 start--}}
@if(config('mbcore_baseadmin.baseadmin_message'))
    <script>
        $('body').on('click', '.messages-click', function() {
            var url = $(this).attr('data-href');
            layer.open({
                type: 2,
                title: '信息查看',
                shadeClose: true,
                shade: 0.8,
                maxmin: true,
                area: ['90%', '90%'],
                content: url, //iframe的url
                end: function(){
                    $.ajax({
                        url: "{{$get_messages_api}}",
                        type: "post",
                        dataType: 'json',
                        success: function (res) {
                            // 更新消息数量
                            $('.message-count').html(res.result.message_count);
                            // 更新消息内容
                            $('.messages-content').html(res.result.data);
                        }
                    });
                }
            });
        });
    </script>
@endif
{{--阅读消息 end--}}

{{--任务查看 start--}}
@if(config('mbcore_baseadmin.baseadmin_admin_pending_task'))
    <script>
        $('body').on('click', '.task-click', function() {
            var id = $(this).attr('data-id');
            var url = "{{route('admin.task.show',['id'=>'__id__'])}}".replace('__id__',id);
            layer.open({
                type: 2,
                title: '任务查看',
                shadeClose: true,
                shade: 0.8,
                maxmin: true,
                area: ['90%', '90%'],
                content: url, //iframe的url
            });
        });
    </script>
@endif
{{--任务查看 end--}}

</body>

</html>