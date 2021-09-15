@extends('mbcore.baseadmin::layout.iframe')
@section('title', '菜单列表')

@section('content')

    <div class="row wrapper wrapper-content animated fadeInRight">

        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>菜单管理</h5>
                </div>
                <div class="ibox-content">
                    <div class="col-sm-6">
                        <div id="treeviewMenu" class="test"></div>
                    </div>
                    <div class="col-sm-6">
                        <h5>菜单编辑：</h5>
                        <hr>
                        <div id="event_output"></div>

                        <div class="clearfix"></div>

                        @include('mbcore.baseadmin::menu.edit')

                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

    </div>


@stop

@section('myscript')
    <!-- Bootstrap-Treeview plugin javascript -->
    <script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/treeview/bootstrap-treeview.js"></script>
    <script>
        var json = '{!! $menusJson !!}';

        $('#treeviewMenu').treeview({
            color: "#428bca",
            levels: 3,
            data: json,
            onNodeSelected: function (event, node) {
                NodeSelectedFun(node);
            }
        });

    </script>
@stop