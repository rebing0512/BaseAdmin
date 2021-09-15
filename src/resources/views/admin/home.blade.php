@extends('mbcore.baseadmin::layout.iframe')
@section('title', '后台首页')

@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row row-sm text-center">
                            <div class="col-xs-3">
                                <div class="panel item">
                                    <div class="h2 text-info font-thin h1">521</div>
                                    <span class="text-muted text-xs">今日新增</span>
                                    <div class="top text-right w-full">
                                        <i class="fa fa-caret-down text-warning m-r-sm"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="panel item bg-info">
                                    <div class="h2 text-fff font-thin h1">521</div>
                                    <span class="text-muted text-xs">今日访问</span>
                                    <div class="top text-right w-full">
                                        <i class="fa fa-caret-down text-warning m-r-sm"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="panel item bg-primary">
                                    <div class="h2 text-fff font-thin h1">521</div>
                                    <span class="text-muted text-xs">今日销量</span>
                                    <div class="top text-right w-full">
                                        <i class="fa fa-caret-down text-warning m-r-sm"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="panel item">
                                    <div class="font-thin h2">￥129</div>
                                    <span class="text-muted text-xs">今日销售金额</span>
                                    <div class="bottom text-right">
                                        <i class="fa fa-caret-up text-warning m-l-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>今日代办任务</h5>
                    </div>
                    <div class="ibox-content">
                        <ul class="todo-list m-t small-list ui-sortable">
                            <li>
                                <a href="widgets.html#" class="check-link"><i class="fa fa-check-square"></i> </a>
                                <span class="m-l-xs todo-completed">吃饭</span>

                            </li>
                            <li>
                                <a href="widgets.html#" class="check-link"><i class="fa fa-check-square"></i> </a>
                                <span class="m-l-xs  todo-completed">多吃饭</span>

                            </li>
                            <li>
                                <a href="widgets.html#" class="check-link"><i class="fa fa-square-o"></i> </a>
                                <span class="m-l-xs">睡觉</span>
                                <small class="label label-primary"><i class="fa fa-clock-o"></i> 1小时</small>
                            </li>
                            <li>
                                <a href="widgets.html#" class="check-link"><i class="fa fa-square-o"></i> </a>
                                <span class="m-l-xs">睡觉</span>
                                <small class="label label-primary"><i class="fa fa-clock-o"></i> 1小时</small>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('myscript')

@stop