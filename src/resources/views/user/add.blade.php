@extends('mbcore.baseadmin::layout.iframe')
@section('title', $subtitle)

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-sm-12">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{$subtitle}}</h5>
                    </div>
                    <div class="ibox-content">

                        {!! Form::open(['enctype'=>'multipart/form-data','class'=>'form-horizontal']) !!}
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            {!! Form::label('username','用户名',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('username',null,['class'=>'form-control','placeholder'=>'用户名','required']) !!}
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            {!! Form::label('password','登录密码',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::password('password',['class'=>'form-control','placeholder'=>'登录密码','required']) !!}
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            {!! Form::label('phone','手机号',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('phone',null,['class'=>'form-control','placeholder'=>'手机号','required']) !!}
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            {!! Form::label('email','邮箱',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('email',null,['class'=>'form-control','placeholder'=>'邮箱']) !!}
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            {!! Form::label('fullname','用户全称',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('fullname',null,['class'=>'form-control','placeholder'=>'用户全称']) !!}
                            </div>
                        </div>
                        @if(config('mbcore_baseadmin.baseadmin_user_role'))
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">用户权限 </label>

                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label>
                                            <input type="radio"  value="user" name="roles"   checked="checked"  >普通用户</label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"  value="is_super_user" name="roles">付费用户</label>
                                    </div>

                                </div>
                            </div>
                        @endif
                        @if (count($errors) > 0  && $errors->all()[0] != "success")
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                {!! Form::submit('保存内容',['class'=>'btn btn-primary']) !!}
                            </div>
                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>


            </div>
        </div>


    </div>

@stop

@section('myscript')

    @if(count($errors)==1 && $errors->all()[0] == "success")
        <!--添加成功提示-->
        <script>
            var url = '{{route("admin.user.lists")}}';
            $(document).ready(function () {
                swal({
                    title: "内容添加",
                    text: "您可以选择“继续添加”或“进入用户列表”页面",
                    timer: 2000,
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "进入用户列表",
                    cancelButtonText: "继续添加"
                },function (isConfirm) {
                    if (isConfirm) {
                        // parent.location.href= url ;
                        window.open(url,'J_iframe');
                        // window.top.location =  url;
                    }
                });
            });
        </script>
    @endif
@stop