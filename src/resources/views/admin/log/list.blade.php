@extends('mbcore.baseadmin::layout.iframe')
@section('title', $subtitle)

@section('content')
    <style>
        select.form-control,select option{ font-size: 12px; vertical-align: middle;}
        #contentTableToolbar .row{line-height:18px;}
        #contentTableToolbar .row .col-sm-8{ margin-top: 6px;}
        .layui-layer-btn{text-align: center !important;}
    </style>
    <div class="row wrapper wrapper-content animated fadeInRight">
    {{--顶部导航--}}
    @if(config('mbcore_baseadmin.baseadmin_admin_log_nav'))
        @include(config('mbcore_baseadmin.baseadmin_admin_log_nav'))
    @endif
    <!-- Panel Other -->
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{$subtitle}}</h5>
                <h5 style="float: right;">
                    <a href="{{route('homePage')}}">
                        <button class="btn btn-info btn1" type="button">
                            {{config('mbcore_baseadmin.baseadmin_homeView_name')}}
                        </button>
                    </a>
                </h5>
            </div>
            <div class="ibox-content">
                <div class="row row-lg">
                    {{--<div id="toolbar" class="btn-group" style="margin-left: 1%;">--}}
                    {{--<button id="add" class="btn btn-default" title="添加">--}}
                    {{--<i class="glyphicon glyphicon-plus"></i> 新增--}}
                    {{--</button>--}}
                    {{--<button id="del" class="btn btn-default" title="删除">--}}
                    {{--<i class="glyphicon glyphicon-minus"></i> 删除--}}
                    {{--</button>--}}
                    {{--</div>--}}
                    <div class="col-sm-12">
                        <div class="col-sm-4"  style="width: 150px; margin-top: 0.4%;padding-left: 0!important;">
                            <select id="Interval" class="input-sm form-control input-s-sm inline" style="height:34px; width: 150px;">
                                @foreach($interval as $key=>$val)
                                    <option value="{{$key}}" @if($key == 'all') selected="selected" @endif>{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4"  style="width: 150px; margin-top: 0.3%;padding-left: 0!important;">
                            <div class="date-div" style=''>
                                <input class="form-control layer-date laydate-icon" onclick="laydate({istime: true, format: 'YYYY-MM-DD '})" id="start" name="date" value="{{\Carbon\Carbon::now()->toDateString()}}" style="margin-left: 6%;">
                            </div>
                        </div>
                        <div class="col-sm-4"  style="width: 150px; margin-top: 0.3%;padding-left: 0!important;">
                            <div class="date-div" style=''>
                                <input class="form-control layer-date laydate-icon" onclick="laydate({istime: true, format: 'YYYY-MM-DD '})" id="end" name="date" value="{{\Carbon\Carbon::now()->toDateString()}}">
                            </div>
                        </div>
                        <div class="col-sm-4" style="margin-top: 0.5%;">
                            <button class="btn btn-default excle" title="导出" style="  font-size: 17px;">
                                <i class="glyphicon glyphicon-share"></i> 导出
                            </button>
                        </div>
                        <table id="contentTable" data-mobile-responsive="true">
                            <thead>
                            <tr>
                                {{--<th data-field="state" data-checkbox="true"></th>--}}
                                <th data-halign="center" data-align="center" data-width="50" data-field="id">ID</th>
                                <th data-halign="center" data-align="center" data-field="username">管理员名称</th>
                                <th data-halign="center" data-align="center" data-field="operation">行为</th>
                                <th data-halign="center" data-align="center" data-field="ip">ip地址</th>
                                <th data-halign="center" data-align="center" data-field="created_at">操作时间</th>
{{--                                <th data-halign="center" data-align="center" data-width="15%"  data-formatter="OperationFormat">操作</th>--}}
                            </tr>
                            </thead>
                        </table>
                        <!-- End Example Events -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Panel Other -->
    </div>
@stop

@push('startcss')
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
@endpush

@section('myscript')
    <!-- Bootstrap table -->
    <script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
    <script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/bootstrap-table/bootstrap-table-mobile.min.js"></script>
    <script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
    {{--<script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/bootstrap-table/bootstrap-table-search.js"></script>--}}
    <script src="{{config('mbcore_baseuser.baseuser_assets_path')}}/js/plugins/layer/laydate/laydate.js"></script>
    <!-- Peity -->
    <script>
        function  OperationFormat(value,row,index){
            return  "<button value="+row.id+"   class='btn btn-xs btn-danger del-data'><i class='fa fa-trash'></i> 删除</button> ";
        }
        // Example Bootstrap Table Events
        // ------------------------------
        $(function() {
            /**
             * 给ajax统一增加csrf请求头
             */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var indexUrl = "{{route('admin.log.list')}}";
            $('#contentTable').bootstrapTable({
                method: 'post',
                contentType: "application/x-www-form-urlencoded",//必须要有！！！！
                url:indexUrl,//要请求数据的文件径
                sidePagination: "server", //服务端处理分页路
                queryParams: function (params) {//自定义参数，这里的参数是传给后台的，我这是是分页用的
                    return {//这里的params是table提供的
                        offset: params.offset,//从数据库第几条记录开始
                        limit: params.limit , //找多少条
                        keywords: params.search,  //关键词
                        condition: $("#sel_column").val(),  //检索条件
                        // status: $("#StatusSelect").val(),
                    };
                },
                search: true,  //是否启用搜索框
                // searchText:'用户昵称',  //初始化搜索文字
                showColumns: true, //是否显示内容列下拉框
                pagination: true,  //设置为 true 会在表格底部显示分页条。
                showRefresh: true,  //是否显示刷新按钮
                showFullscreen: true, //是否显示全屏按钮
                //showToggle: true,  //是否显示切换视图（table/card）按钮。
                iconSize: 'outline',
                toolbar: '#contentTableToolbar',
                clickToSelect: true,//是否启用点击选中行
                striped: true, //是否显示行间隔色
                dataField: "data",//bootstrap table 可以前端分页也可以后端分页，这里
                //我们使用的是后端分页，后端分页时需返回含有total：总记录数,这个键值好像是固定的
                //rows： 记录集合 键值可以修改  dataField 自己定义成自己想要的就好
                pageNumber: 1, //初始化加载第一页，默认第一页
                pageSize:10,//单页记录数
                pageList:[5,10,20,30],//分页步进值
                icons: {
                    refresh: 'glyphicon-repeat',
                    //toggle: 'glyphicon-list-alt',
                    columns: 'glyphicon-list'
                }
                ,responseHandler:function(res){
                    //在ajax获取到数据，渲染表格之前，修改数据源
                    console.log(res);
                    if(res.code){
                        return res.result;
                    }else{
                        return [];
                    }
                }
                ,height:$(window).height() - 120
            });
            // 操作部分的JS
            var deleteUrl = '{{route("admin.log.delete",['id'=>'__id__'])}}';
            @include('admin.common.operation_js')
            $('body').on('click', 'button.del-data', function() { // 删除
                var url = deleteUrl.replace('__id__',$(this).val()); //this代表删除按钮的DOM对象;
                layer.confirm('你确定要将该信息删除吗？', {
                    btn: ['确定', '取消'],
                    icon: 2,
                },function(index){ //index代表当前的弹窗?? //1
                    //                     console.log(index); //1
                    $.ajax({
                        //type: "DELETE",
                        type: "POST",
                        url: url,
                        success: function(data){
                            if (data.code){
                                //刷新dt
                                $('#contentTable').bootstrapTable('refresh');
                                //layer.msg(data.result);
                            }
                            // console.log(index);
                            layer.close(index); //关闭larvel弹出框
                            layer.msg(data.result);
                        }
                    });
                });
            });
            // 手动选择起始和结束时间
            $('body').on('click', 'button.excle', function() {
                var excelUrl = '{{route('admin.log.excel.export')}}' + '?start_time=' + $('#start').val() + '&end_time=' + $('#end').val();
                $.ajax({
                    type: "POST",
                    data:{
                        'start_time':$('#start').val(),
                        'end_time':$('#end').val(),
                        'type':1
                    },
                    url: '{{route("admin.log.excel.check_data")}}',
                    success: function(data){
                        if (data.code == 1){
                            window.location.href = excelUrl;
                            layer.close(index); //关闭larvel弹出框
                            // layer.msg(data.result);
                        }else{
                            var url ="{{route('admin.log.list')}}";
                            var content = data.result.msg;
                            {{--没有信息的提示--}}
                            layer.confirm(content, {
                                title: '温馨提示',
                                btn: ['知道了'],
                                closeBtn: 0,
                                icon: 5,
                                time: 5000 //定时自动关闭窗口
                            },function(index){ //index代表当前的弹窗?? //1
                                layer.close(index); //关闭larvel弹出框
                                // parent.location.href= url ;
                            });
                        }
                    }
                });
            });
            // 根据指定时间段导出数据
            $("#Interval").change(function () {
                var value = $("#Interval").val();
                if(value != 'all') {
                    var excelUrl = '{{route('admin.log.excel.export')}}' + '?interval=' + $('#Interval').val() + '&type=2';
                    $.ajax({
                        type: "POST",
                        data:{
                            'interval':$('#Interval').val(),
                            'type':2
                        },
                        url: '{{route("admin.log.excel.check_data")}}',
                        success: function(data){
                            if (data.code == 1){
                                window.location.href = excelUrl;
                                layer.close(index); //关闭larvel弹出框
                                // layer.msg(data.result);
                            }else{
                                var url ="{{route('admin.log.list')}}";
                                var content = data.result.msg;
                                {{--没有信息的提示--}}
                                layer.confirm(content, {
                                    title: '温馨提示',
                                    btn: ['知道了'],
                                    closeBtn: 0,
                                    icon: 5,
                                    time: 5000 //定时自动关闭窗口
                                },function(index){ //index代表当前的弹窗?? //1
                                    layer.close(index); //关闭larvel弹出框
                                    // parent.location.href= url ;

                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@stop