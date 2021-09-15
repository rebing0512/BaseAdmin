@inject('BaseAdminHelper', 'MBCore\BaseAdmin\Libraries\Helper')
@extends('mbcore.baseadmin::layout.iframe')
@section('title', '管理员列表')

@section('content')
    <div class="row wrapper wrapper-content animated fadeInRight">


        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>管理员列表</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>用户名</th>
                                    <th>姓名</th>
                                    <th>邮箱</th>
                                    <th>创建时间</th>
                                    <th>最后登录时间</th>
                                    <th>最后登录IP</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>


                                 @forelse($data as $item)
                                        <tr rel="{{$item['id']}}">
                                            <td>{{$item['username']}}</td>
                                            <td data-name="fullName">{{$item['fullName']}}</td>
                                            <td data-name="email">{{$item['email']}}</td>
                                            <td>{{$item['created_at']}}</td>
                                            <td>{{$item['last_login_time']}}</td>
                                            <td>{{$item['last_login_ip']}}</td>
                                            <td>
                                                <p>
                                                 @if( $BaseAdminHelper::isSuperUser($item['roles']) )
                                                            <span class="label">最高管理员</span>
                                                 @else
                                                        @if( $BaseAdminHelper::hasRoles("admin_password",$rolesArr['system']) )
                                                        <a data-toggle="modal" class="label label-success EditPassWord" data-target="#OpenEditPassWord"  rel="{{$item['id']}}">重设密码</a>
                                                        @endif

                                                        @if( $BaseAdminHelper::hasRoles("admin_roles",$rolesArr['system']) )    &nbsp;
                                                        <a data-toggle="modal" class="label label-success EditRoles" data-target="#OpenEditRoles"  rel="{{$item['id']}}">设定权限</a>
                                                        @endif
                                                  @endif
                                                </p>
                                            </td>

                                        </tr>
                                 @empty
                                     <tr>
                                         <td colspan="7">
                                             没有更多了
                                         </td>
                                     </tr>
                                 @endforelse

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>


    </div>

    @include('mbcore.baseadmin::admin.OpenEditPassWord')
    @include('mbcore.baseadmin::admin.OpenEditRoles')

@stop

@section('myscript')

@stop