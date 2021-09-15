<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--360浏览器优先以webkit内核解析-->


    <title> @yield('title') </title>

    <!--<link rel="shortcut icon" href="favicon.ico">-->
  	<link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/animate.css" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/style.css?v=4.1.0" rel="stylesheet">
    {{--自定义样式文件--}}
    @if( in_array("baseadmin_ButtonCssGroup",array_keys(config('mbcore_baseadmin'))) && count(config('mbcore_baseadmin.baseadmin_ButtonCssGroup')) >0 )
        @foreach(config('mbcore_baseadmin.baseadmin_ButtonCssGroup') as $value)
            <link href="{{url($value)}}" rel="stylesheet">
        @endforeach
    @endif
    @stack('startcss')
    <!-- 全局js -->
    <script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/jquery.min.js?v=2.1.4"></script>
    <script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/jquery.cookie.js"></script>
</head>

<body class="gray-bg">

@yield('content')


<!-- 全局js -->
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/bootstrap.min.js?v=3.3.6"></script>
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/layer/layer.min.js"></script>

<!-- Flot -->
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/flot/jquery.flot.js"></script>
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/flot/jquery.flot.resize.js"></script>
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/flot/jquery.flot.pie.js"></script>


<!-- Sweet alert -->
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/sweetalert/sweetalert.min.js"></script>

<!-- layer javascript -->
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/layer/layer.min.js"></script>
<script>
    layer.config({
        extend: 'extend/layer.ext.js'
    });
</script>


<!-- 自定义js -->
<script>
    var gohomeurl = '{{ route("admin.default") }}';
</script>
<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/content.js"></script>

<!--flotdemo-->
@yield('myscript')
@stack('endscripts')
</body>

</html>
