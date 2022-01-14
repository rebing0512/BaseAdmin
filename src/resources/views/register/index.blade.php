<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>  注册</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--<link rel="shortcut icon" href="favicon.ico"> --}}
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/animate.css" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/style.css?v=4.1.0" rel="stylesheet">
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>
    <style>
        .btn-w-m{
            min-width: 100px !important;
        }

        .phone86 {
            width: 43px;
            white-space: nowrap;
            border: 1px solid #c3c5c6;
            height: 30px;
            padding-top: 4px;
            font-size: 15px;
        }
        .phone {
            width: 87%;
            margin-left:13% !important;
            position: absolute;
            bottom: 160px;
        }

    </style>
</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen   animated fadeInDown">
    <div>
        <div>

            {{--<h1 class="logo-name">皮皮狗</h1>--}}

        </div>
        <h2>欢迎注册</h2>
        <p><h3>{{config('mbcore_baseadmin.baseadmin_name')}}</h3> </p>
        {!! Form::open(['class'=>'m-t','role'=>'form','url' =>route('admin.register.auth')]) !!}
        {{--<form class="m-t" role="form" action="{{route('user.register.auth')}}" method="post">--}}
        <div class="form-group">
            {!! Form::text('username',null,['class'=>'form-control','placeholder'=>'请输入用户名','required'=>'']) !!}
        </div>
        <div class="form-group">
            {!! Form::password('password',['class'=>'form-control','placeholder'=>'请输入密码','required'=>'']) !!}
        </div>
        <div class="form-group">
            {!! Form::password('confirm_password',['class'=>'form-control','placeholder'=>'请再次输入密码','required'=>'']) !!}
        </div>

        <div class="form-group">
            <div class="phone86" style="border-right: 0;">+86</div>
            {{--<label class="no-padding" style="float: left; margin-top: 7px;font-weight: 730 !important;"><i></i> +86</label>--}}
            {!! Form::text('phone',null,['class'=>'form-control phone mobile-phone-input','placeholder'=>'请输入手机号','required'=>'','id'=>'phone']) !!}
        </div>

{{--        <div class="form-group">--}}
{{--            {!! Form::text('code',null,['class'=>'form-control','placeholder'=>'请输入验证码','required'=>'','style'=>'width: 190px;']) !!}--}}
{{--            {!! Form::button('获取验证码',['class'=>'btn btn-w-m btn-primary code-button','style'=>'float: right; position: absolute; bottom: 25.5%; right: 0px;','id'=>'getCode']) !!}--}}
{{--        </div>--}}

        <div class="form-group text-left">
            <div class="checkbox i-checks">
                <label class="no-padding chk">
                    <input type="checkbox" class="chk" checked>
                    <i class="chk"></i> <span class="registerProtocol chk" style="color: #23527c;">我同意注册协议</span>
                    {{--<small style="color:#d6d3e6;">请点击汉字部分</small>--}}
                </label>
            </div>
            {!! Form::hidden('registerProtocol','1',['id'=>'registerProtocol']) !!}
        </div>
        {!! Form::submit('注 册',['class'=>'btn btn-primary block full-width m-b']) !!}
        <p class="text-muted text-center">
            <small>已经有账户了？</small>
            <a href="{{ route('login.login')}}">点此登录</a>
        </p>
        {!! Form::close() !!}
    </div>
</div>
<div class="middle-box text-center loginscreen   animated fadeInDown" style="padding-top: 0 !important;">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<!-- 全局js -->
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/jquery.min.js?v=2.1.4"></script>
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/bootstrap.min.js?v=3.3.6"></script>
<!-- iCheck -->
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/iCheck/icheck.min.js"></script>

<!-- Sweet Alert -->
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/sweetalert/sweetalert.min.js"></script>
<link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

{{-- 发送验证码 --}}
{{--<script>--}}
{{--    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});--}}
{{--    var type = 'REGISTER';--}}
{{--    var url = '{{route("user.register.getCode")}}';--}}
{{--</script>--}}
{{--@include('mbcore.baseuser::layout.get_code_js')--}}

<script>
    $(document).ready(function () {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        // 复选框点击事件 todo：点击复选框不生效，初步判断是由于复选框增加了定位的原因
        $('.chk').click(function () {

            if($("input[type='checkbox']").is(':checked')){
                $('#registerProtocol').val(1);
                // console.log(1);
            }else{
                $('#registerProtocol').val(0);
                // console.log(0 );
            }
        });
    });
</script>

<script>
    var success = "{{$success}}";
    if(success == 1) {
//        console.log(212121);
        $(document).ready(function () {
            swal({
                title: "恭喜，注册成功！",
                text: "2秒后自动跳转至登录页面。",
                type: "success",
                timer: 2000,
                showConfirmButton: false
            }, function () {
                location.href = "{{ route('login.login')}}";
            });
        });
    }
</script>
</body>

</html>
