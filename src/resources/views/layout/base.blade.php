<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title> @yield('title') </title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/animate.css" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/style.css" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/login.css" rel="stylesheet">
    <!--config-->
    @if(isset($background) && $background)
        <style>
            body.signin {
                background:url("{{url($background)}}")  no-repeat center center;
            }
        </style>
    @else
        @if(config('mbcore_baseadmin.baseadmin_background_image'))
            <style>
                body.signin {
                    background:url("{{config('mbcore_baseadmin.baseadmin_assets_path')}}/{{config('mbcore_baseadmin.baseadmin_background_image')}}");
                }
            </style>
            @endif
    @endif

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>
        if (window.top !== window.self) {
            window.top.location = window.location;
        }
    </script>

</head>

<body class="signin">
    @yield('content')
</body>

</html>