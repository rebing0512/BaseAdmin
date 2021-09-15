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

        <!-- Panel Other -->
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{$subtitle}}</h5>
                <h5 style="float: right;">
                    <a href="{{route('homePage')}}">
                        <button class="btn btn-info " type="button" style="padding: 2px 6px !important; margin-top: -4px !important;">
                            {{config('mbcore_baseadmin.baseadmin_homeView_name')}}
                        </button>
                    </a>
                </h5>
            </div>

            <div class="ibox-content">
                <div class="row row-lg">
                    <div id="toolbar" class="btn-group" style="margin-left: 1%;">
                        <button id="add" class="btn btn-default" title="添加">
                            <i class="glyphicon glyphicon-plus"></i> 添加
                        </button>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-4"  style="width: 150px; margin-top: 1%;padding-left: 0!important;">
                            <select id="TypeSelect" class="input-sm form-control input-s-sm inline">
                                <option value="all" selected="selected">全部用户</option>
                                <option value="1">用户注册</option>
                                <option value="2">后台添加</option>
                            </select>
                        </div>

                        <table id="contentTable" data-mobile-responsive="true">
                            <thead>
                            <tr>
                                {{--<th data-field="state" data-checkbox="true"></th>--}}
                                <th data-halign="center" data-align="center"  data-width="50" data-field="id">ID</th>
                                <th data-halign="center" data-align="center" data-field="username" data-show="1">用户名</th>
                                <th data-halign="center" data-align="center" data-field="phone" data-show="1">手机号</th>
                                <th data-halign="center" data-align="center" data-field="last_login_time">最后一次登录时间</th>
                                <th data-halign="center" data-align="center" data-field="last_login_ip">最后一次登录IP</th>
                                @if(config('mbcore_baseadmin.baseadmin_user_role'))
                                <th data-halign="center" data-align="center" data-field="roles" data-formatter="RolesFormat">用户权限</th>
                                @endif
                                <th data-halign="center" data-align="center" data-field="created_at">注册时间</th>
                                <th data-halign="center" data-align="center" data-field="register_method" data-formatter="RegisterFormat">注册方式</th>
                                <th data-halign="center" data-align="center" data-field="status"  data-formatter="StatusFormat">用户状态</th>
                                <th data-halign="center" data-align="center" data-formatter="OperationFormat">操作</th>
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
    <script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/bootstrap-table/bootstrap-table-search.js"></script>

    <!-- Peity -->
    <script>
        @if(config('mbcore_baseadmin.baseadmin_user_role'))
            function RolesFormat(value,row,index){
                if(value == 'is_super_user'){
                    return '<span style="color:green;">付费用户</span>';
                }else{
                    return '<span>普通用户</span>';
                }
            }
        @endif

        function RegisterFormat(value,row,index){
            if(value == 1){
                return '<span style="color:green;">用户注册</span>';
            }else{
                return '<span style="color:red;">后台添加</span>';
            }
        }

        function StatusFormat(value,row,index){
            if(value == 1){
                return '<span style="color:green;">正常</span>';
            }else{
                return '<span style="color:red;">锁定</span>';
            }
        }
        //
        function  OperationFormat(value,row,index){
            // return "<button value="+row.id+" class='btn btn-xs btn-success show-data'><i class='fa fa-eye'></i> 查看</button> "+
            //     "<button value="+row.id+"   class='btn btn-xs btn-info edit-data'><i class='fa fa-edit'></i> 编辑</button> " +
            //     "<button value="+row.id+" class='btn btn-xs btn-danger del-data'><i class='fa fa-trash'></i>  删除</button>";
            if(row.status == 1){
                return "<button value="+row.id+"   class='btn btn-xs btn-info edit-data'><i class='fa fa-edit'></i> 编辑</button> " +
                    "<button value="+row.id+"  data-username="+row.username+" class='btn btn-xs btn-danger lock-data' data-content='锁定'><i class='fa fa-lock'></i>  锁定</button>";
            }else{
                return "<button value="+row.id+"   class='btn btn-xs btn-info edit-data'><i class='fa fa-edit'></i> 编辑</button> " +
                    "<button value="+row.id+"  data-username="+row.username+" class='btn btn-xs btn-success lock-data' data-content='解锁'><i class='fa fa-unlock'></i>  解锁</button>";
            }

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

            var indexUrl = "{{route('admin.user.lists')}}";

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
                        type: $("#TypeSelect").val(),//注册方式
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
                    // console.log(res);
                    if(res.code){
                        return res.result;
                    }else{
                        return [];
                    }

                }
                ,height:$(window).height() - 120
            });



            // 操作部分的JS
            var createUrl = "{{route('admin.user.add')}}";
            var editUrl = '{{route("admin.user.edit",['id'=>'__id__'])}}';
            var lockUrl = '{{route("admin.user.lockUser",['id'=>'__id__'])}}';
            $('body').on('click', '#add', function() { //新增
                var url =createUrl;
                layer.open({
                    type: 2,
                    title: '添加用户',
                    shadeClose: true,
                    shade: 0.8,
                    maxmin: true,
                    area: ['90%', '90%'],
                    content: url,//iframe的url
                    end: function(){
                        //console.log("2222");
                        $('#contentTable').bootstrapTable('refresh');
                    }
                });
            }).on('click', 'button.edit-data', function() { // 编辑 edit-data  layer实现编辑文章
                var url = editUrl.replace('__id__',$(this).val()); //this代表删除按钮的DOM对象;
                layer.open({
                    type: 2,
                    title: '编辑用户',
                    shadeClose: true,
                    shade: 0.8,
                    maxmin: true,
                    area: ['90%', '90%'],
                    content: url, //iframe的url
                    end: function(){
                        //console.log("2222");
                        $('#contentTable').bootstrapTable('refresh');
                    }
                });
            }).on('click', 'button.lock-data', function() { // 删除
                var url = lockUrl.replace('__id__',$(this).val()); //this代表锁定/解锁按钮的DOM对象;
                var content = $(this).attr('data-content');
                var username = $(this).attr('data-username');
                var icon = 5;
                if(content == '解锁'){
                    icon = 6;
                }
                layer.confirm('你确定要'+content+'用户'+username+'吗？', {
                    btn: ['确定', '取消'],
                    icon: icon,
                },function(index){ //index代表当前的弹窗?? //1
                    // console.log(index); //1
                    $.ajax({
                        //type: "DELETE",
                        type: "POST",
                        url: url,
                        success: function(data){
                            // console.log(data);return false;
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

            //触发数据重载
            $("#TypeSelect").change(function () {
                // console.log($("#TypeSelect").val());
                $('#contentTable').bootstrapTable('selectPage',1);
            });
        });

    </script>
@stop