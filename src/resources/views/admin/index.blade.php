@extends('mbcore.baseadmin::layout.home')
@section('title', '后台首页')

@section('content')
{{route($adminHomeRoute)}}
@stop