<!--菜单编辑-->
<div class="ibox-content" id="thisMenu" style="display: none;">
    <form  class="form-horizontal" action="{{route('menu.editsave')}}" method="post">

        <input type="hidden" name="id" value="">

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
                <select id="select_parent_id" class="form-control m-b" name="parent_id">
                    <option value="0">顶级菜单</option>
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
                @push('endscripts')
                <!--添加成功提示-->
                <script>
                    $(document).ready(function () {
                        $('#thisMenu').hide();
                        swal({
                            title: "菜单更新成功！",
                            text: "2秒后自动关闭。",
                            type: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    });
                </script>
                @endpush

            @else
                @push('endscripts')
                        <script>
                            $(document).ready(function () {
                                var node_id = $.cookie('node_id');
                                $.cookie('node_id_err', node_id);  //错误的
                                //console.log(node_id);
                                $("#treeviewMenu").find("li[data-nodeid="+node_id+"]").click();
                                //console.log(test)
                            });
                        </script>
                @endpush
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
                <button class="btn btn-primary" type="submit">更新内容</button>
            </div>
        </div>



        {{ csrf_field() }}

    </form>
</div>


@push('endscripts')

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
                //$("#menu_group_id").val(0);
            }
        });

    });
</script>
@endpush

<script>

    var topmenuJson= '{!! $topmenuJson !!}';

    function creatSelectFun(removeId){
        //重新初始化select内的项目
        $("#select_parent_id").empty();
        $("#select_parent_id").append("<option value='0'>顶级菜单</option>");


        json = eval(topmenuJson);
        for(var i=0; i<json.length; i++){
            //console.log(json[i].name);
            if(json[i].id != removeId){
                $("#select_parent_id").append("<option value='"+json[i].id+"'>"+json[i].name+"</option>");
            }
        }

     }

    function NodeSelectedFun(node){

        // 需要删除父级标签ID
        var removeId = 0;

        //console.log(node);
        var node_id = node.nodeId;
        $.cookie('node_id', node_id);

        var node_id_err = $.cookie('node_id_err');
        if(node_id == node_id_err){
            $("#thisMenu").find(".alert-danger").show();
        }else{
            $("#thisMenu").find(".alert-danger").hide();
        }

        var str = '';
        if(node.tags.isGroup) {
            // 如果是分组,则执行此操作。
            $('#event_output').html('<p class="alert alert-success" >您单击了菜单分组项</p>');
            return ;
        }else if(node.tags.isFather){
            $("#menu_group_id_div").show();
            str += ' [顶级菜单] ';
            if(node.tags.hasChild){
                str += ' [存在子菜单] ';
            }else{
                str += ' [无子菜单] ';
            }
            // 父级标签不能包含自己作为可选父级标签
            removeId = node.tags.data.id;

        }else{
            $("#menu_group_id_div").hide();
            $('#menu_group_id').val(0);
            str += '[子菜单]';
        }
        $('#event_output').html('<p class="alert alert-success" >您单击了 <span class="alert-link"> ' + node.text +' </span> 为：<span class="alert-link">'+  str + '</spam></p>');


        //重新初始化select内的项目
        creatSelectFun(removeId);
        //$("#select_parent_id").append("<option value='Value'>Text</option>");

        //初始化数据
        $('#thisMenu').find('input[name="id"]').val(node.tags.data.id);
        $('#thisMenu').find('input[name="name"]').val(node.tags.data.name);
        $('#thisMenu').find('input[name="link"]').val(node.tags.data.link);
        $('#thisMenu').find('input[name="sort"]').val(node.tags.data.sort);
        $('#thisMenu').find('input[name="i_ico_class"]').val(node.tags.data.i_ico_class);
        $('#thisMenu').find('select[name="parent_id"]').val(node.tags.data.parent_id);
        //分组ID
        $('#menu_group_id').val(node.tags.data.group_id);

        $('#thisMenu').show();
    }
</script>


