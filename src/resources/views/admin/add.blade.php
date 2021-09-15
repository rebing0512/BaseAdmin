@extends('mbcore.baseadmin::layout.iframe')
@section('title', '管理员增加')

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">


        <div class="row">
            <div class="col-sm-12">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>添加管理员</h5>
                    </div>
                    <div class="ibox-content">
                        <form  class="form-horizontal" action="{{route('admin.addsave')}}" method="post">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">用户名</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="username" value="{{ old('username', '') }}">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">密码</label>

                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="password" value="{{ old('password', '') }}">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">姓名</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="fullName" value="{{ old('fullName', '') }}">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">邮箱</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="email" value="{{ old('email', '') }}">
                                </div>
                            </div>


                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">状态</label>

                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label>
                                            <input checked="" value="1" id="optionsRadios1" name="status" type="radio">正常</label>
                                        <label>
                                            <input value="2" id="optionsRadios2" name="status" type="radio">禁用</label>
                                    </div>

                                </div>
                            </div>


                            <div class="hr-line-dashed"></div>


                            @if (count($errors) > 0)
                                @if(count($errors)==1 && $errors->all()[0] == "is_save_success")
                                    <!--添加成功提示-->
                                @else
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endif
                            <input type="hidden" class="form-control" name="group_id" value="{{$group_id}}">
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit">保存内容</button>
                                </div>
                            </div>



                            {{ csrf_field() }}
                        </form>

                    </div>
                </div>


            </div>
        </div>


    </div>

@stop

@section('myscript')

    @if(count($errors)==1 && $errors->all()[0] == "is_save_success")
        <!--添加成功提示-->
        <script>
            @if($back_url)
                var url = '{{$back_url}}';
            @else
                var url = "{{ route("admin.list") }}";
            @endif
            $(document).ready(function () {
                swal({
                    title: "管理员添加成功",
                    text: "您可以选择“继续添加管理员”或“进入管理员管理”页面",
                    timer: 2000,
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "进入管理员管理",
                    cancelButtonText: "继续添加菜单"
                },function (isConfirm) {
                    if (isConfirm) {
                        location.href=url;
                    }
                });
            });
        </script>
    @endif
@stop