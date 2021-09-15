@extends('mbcore.baseadmin::layout.iframe')
@section('title', '菜单增加')

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">


        <div class="row">
            <div class="col-sm-12">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>添加菜单</h5>
                    </div>
                    <div class="ibox-content">
                        <form  class="form-horizontal" action="{{route('menu.addsave')}}" method="post">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">菜单名称</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">模块地址</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="link"> <span class="help-block m-b-none">如果是顶级菜单，且子菜单内有内容，则此项属性自动忽略。<span class="badge badge-danger">请使用route名称</span></span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">菜单排序</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="sort" value="0">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">菜单图标</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="i_ico_class" value="fa fa-gear"><span class="help-block m-b-none">只有顶级菜单应用此属性。默认值：<i class="fa fa-gear"></i></span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">菜单父级</label>

                                <div class="col-sm-10">
                                    <select class="form-control m-b" name="parent_id">
                                        <option value="0">顶级菜单</option>
                                        @forelse ($topmenu as $menu)
                                            <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                        @empty
                                            <!--暂无其它顶级菜单-->
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div id="menu_group_id_div">
                                @if(config('mbcore_baseadmin.baseadmin_menuGroup'))
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">菜单分组</label>

                                    <div class="col-sm-10">
                                        <select id="menu_group_id" class="form-control m-b" name="group_id">
                                            @foreach (config('mbcore_baseadmin.baseadmin_menuGroup') as $key=>$val)
                                                <option value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @else
                                    <input id="menu_group_id" type="hidden" name="group_id" value="0">
                                @endif
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


        <!--添加成功提示-->
        <script>
            $(document).ready(function () {

                //name="parent_id"
                $('select[name="parent_id"]').change(function(){
                    //console.log($(this).val())
                    var parent_id = $(this).val();
                    if(parent_id==0){
                        $("#menu_group_id_div").show();
                        //console.log( $('select[name="group_id"]').val())
                        if( $('select[name="group_id"]').val()==null){
                            $('select[name="group_id"]').val(0);
                        }
                    }else{
                        $("#menu_group_id_div").hide();
                        $("#menu_group_id").val(0);
                    }
                });

                @if(count($errors)==1 && $errors->all()[0] == "is_save_success")
                        swal({
                            title: "菜单添加成功",
                            text: "您可以选择“继续添加菜单”或“进入菜单管理”页面",
                            timer: 2000,
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "进入菜单管理",
                            cancelButtonText: "继续添加菜单"
                        },function (isConfirm) {
                            if (isConfirm) {
                                location.href="{{ route("menu.list") }}";
                            }
                        });
                @endif
            });
        </script>

@stop