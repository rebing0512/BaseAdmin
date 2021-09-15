<div id="OpenEditRoles" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> 设置权限</h4>
            </div>
            <div class="modal-body form-horizontal" >

                <input type="hidden" name="id" value="">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>系统级别权限 <small>若所选顶级菜单的子菜单仍然包含子菜单，则不默认勾选</small></h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                                <div id="systemRoles">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">权限根节点</label>
                                        <div class="col-sm-9" id="systemRolesRoot">
                                        </div>
                                    </div>

                                </div>
                        </div>
                    </div>
                </div>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>应用级别权限 <small>若所选顶级菜单的子菜单仍然包含子菜单，则不默认勾选</small></h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div id="systemRoles">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">权限根节点</label>
                                    <div class="col-sm-9" id="menuRolesRoot">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div id="ajaxInfo" class="alert alert-danger" style="display: none">
                    <ul>
                        <li></li>
                    </ul>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white closeButton" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary saveButton">保存</button>
            </div>
        </div>
    </div>
</div>

@push('endscripts')
   <link href="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/css/plugins/iCheck/custom.css" rel="stylesheet">
@endpush
@push('endscripts')
    <!-- iCheck -->
    <script src="{{config('mbcore_baseadmin.baseadmin_assets_path')}}/js/plugins/iCheck/icheck.min.js"></script>
    <script>
        var RolesArr = [];
    $(document).ready(function () {
         var roleJson = eval('{!! $rolesJson !!}');
        //console.log(roleJson);
        var divID = ['systemRolesRoot','menuRolesRoot'];

        //动态绑定菜单
        function CheckboxBindClick(){
            $("#OpenEditRoles").on("click",".rolesCheckbox",function(event){
                //$("#OpenEditRoles").on("click",".icheckbox_square-green",function(event){

                //console.log(event.target.tagName);

                //$(this).css("border","5px solid #000");
                //console.log($(this).find("input").attr("rel"));
                var this_rel = $(this).find("input").attr("rel");
                var this_name = $(this).find("input").val();
                var isCheck =  $(this).find("input").prop('checked');
                //console.log(isCheck);

                // 通过模拟点击和鼠标点击区别操作
                var isUserClick = false;
                if(event.clientX>0 && event.clientY>0 ){
                    isUserClick = true;
                    //console.log("user Click");
                }else{
                    //console.log("Not User Click");
                }
                //console.log(event);
                //console.log(from);

                arr=this_rel.split('-');
                //console.log(this_rel);
                //console.log(arr);
                var tempRoleJson = roleJson;
                for(i=0;i<arr.length;i++){
                    if(i<2){
                        tempRoleJson = tempRoleJson[arr[i]];
                    }else{
                        tempRoleJson = tempRoleJson['subroles'][arr[i]];
                    }
                    //console.log(tempRoleJson);
                }
                //console.log(tempRoleJson);
                if(tempRoleJson['subroles']){
                    //console.log("存在子菜单");
                    RolesView(this,this_rel,this_name,isCheck,isUserClick,arr[0],tempRoleJson['subroles']);
                    //RolesView(thisLabel,thisRel,thisName,isCheck,isUserClick,menuFlag,dataJson)
                }else{
                    //console.log("不存在子菜单");
                }

                //如果子菜单全部被取消，则，取消顶级菜单的选择
                if(isUserClick){
                    var label = $(this).siblings("label");
                    var input = $(this).siblings("input");
                    /*
                     $.each(label.find("input:checked"),function(){
                     change_input_check = false;
                     });
                     */
                    //console.log(label.find("input:checked"));
                    var label_length = label.find("input:checked").length;
                    if($(this).find("input").is(":checked")){
                        label_length += 1;
                    }
                    //console.log(label_length);
                    if(label_length>0){
                        input.prop("checked","checked");
                        //console.log("true");
                    }else{
                        input.removeAttr('checked');
                        //console.log("false");
                    }

                    //如果选择了管理员管理，则一定授权账号列表
                    //console.log($("input[value='admin']").is(":checked"));
                    if($("input[value='admin']").is(":checked")){
                        $("input[value='admin_list']").prop("checked","checked");
                    }
                    $("input[value='admin_list']").prop("disabled","disabled");
                }

                //防止事件冒泡
                event.stopPropagation(); //终止冒泡的方法
                //return false;         //冒泡事件和默认事件都阻止
            });
        }

         //设置漂亮的样式
         function iCheck(){
             /*
             $('.i-checks').iCheck({
                 checkboxClass: 'icheckbox_square-green'
             });
             //动态绑定菜单
             //CheckboxBindClick();
             //*/
         }
         //iCheck();

         function RootRoles(){
                //
                //<label class="checkbox-inline i-checks"><input type="checkbox" value="option1">a</label>
             console.log("RootRoles");
             for (var i = 0; i < roleJson.length; i++) {
                 console.log(i,roleJson[i]);
                 var data = roleJson[i];
                 $("#"+divID[i]).html(""); //清空操作。
                 for(var j=0; j < data.length; j++){
                     console.log(data[j]);
                     @if(!config('mbcore_baseadmin.baseadmin_development'))
                        if(i==0 && j==0)continue;
                     @endif
                     $("#"+divID[i]).append('<label class="rolesCheckbox checkbox i-checks"><input name="'+divID[i]+'" type="checkbox" value="'+data[j]['flag']+'"  rel="'+i+"-"+j+'">'+data[j]['name']+'</label>');
                 }
             }
             //美化按钮
             iCheck();
         }
         //初始化根节点
        //RootRoles();

         //渲染权限分组子菜单
         function RolesView(thisLabel,thisRel,thisName,isCheck,isUserClick,menuFlag,dataJson){
             //console.log(thisLabel,thisRel,dataJson);
             //console.log("------");
             $(thisLabel).find(".rolesCheckbox").remove();
             var checkStr = "";

             var inputType = "checkbox";
             if(thisRel=='0-1'){
                 inputType = "radio";
             }


             //console.log(RolesArr[menuFlag]);
             for (var i = 0; i < dataJson.length; i++) {
                 //console.log(i,dataJson[i]);
                 //console.log(dataJson[i]["subroles"]);
                 if($.inArray(""+dataJson[i]['flag'],RolesArr[menuFlag])>=0){
                     checkStr = 'checked=""';
                     if(!isCheck && isUserClick){
                         checkStr = '';
                     }
                 }else{
                     checkStr = "";
                     /*
                     if(dataJson[i]["subroles"]){
                         checkStr = "";  //有子菜单时不自动授权给权限
                     }else

                     */
                     if(isCheck && isUserClick){
                         checkStr = 'checked=""';
                     }
                     //有子菜单不默认授权
                     if(dataJson[i]["subroles"] && isUserClick){
                         checkStr = "";  //有子菜单时不自动授权给权限
                     }
                 }
                 //console.log("------------------");
                 //console.log(dataJson[i]['flag']);
                 //console.log(checkStr);

                 //var arr = ["a", "b", "c"];
                 // var result = $.inArray("c", arr); //返回index为2

                 //$(thisLabel).append('<label class="rolesCheckbox checkbox i-checks"><input name="'+thisName+'" type="'+inputType+'" '+checkStr+' value="'+dataJson[i]['flag']+'"  rel="'+thisRel+"-"+i+'">'+dataJson[i]['name']+'</label>');
                 //$(thisLabel).append('<label class="rolesCheckbox i-checks"><input name="'+thisName+'" type="'+inputType+'" '+checkStr+' value="'+dataJson[i]['flag']+'"  rel="'+thisRel+"-"+i+'">'+dataJson[i]['name']+'</label>');

                 //被选中且有子元素
                 if(dataJson[i]["subroles"] && checkStr == 'checked=""'){
                     //console.log("被选中，且有子元素。");
                     $(thisLabel).append('<label class="rolesCheckbox checkbox i-checks"><input name="'+thisName+'" type="'+inputType+'"  value="'+dataJson[i]['flag']+'"  rel="'+thisRel+"-"+i+'">'+dataJson[i]['name']+'</label>');
                        $(thisLabel).find("input[value='"+dataJson[i]['flag']+"']").click();
                 }else{
                     $(thisLabel).append('<label class="rolesCheckbox checkbox i-checks"><input name="'+thisName+'" type="'+inputType+'" '+checkStr+' value="'+dataJson[i]['flag']+'"  rel="'+thisRel+"-"+i+'">'+dataJson[i]['name']+'</label>');
                 }

             }

             //美化按钮
             iCheck();
         }

        //动态绑定菜单
        CheckboxBindClick();


        $(".EditRoles").click(function(){
            //初始化数组
            RolesArr = [];
            //初始化根节点
            RootRoles();


            var ajaxInfo = $("#OpenEditRoles").find("#ajaxInfo");
            ajaxInfo.hide();


            var thisid = $(this).attr("rel");
            $("#OpenEditRoles").find("input[name='id']").val(thisid);

            $.ajax({
                type: "POST",
                url: "{{route('admin.getRole')}}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'id':thisid
                },
                dataType: "json",
                success: function(data, textStatus, jqXHR){
                    //console.log(data);
                    if(data.code){
                        //result	{"system":["home","home_4"],"menu":["0","11"]}
                        var jsonObject =  eval('(' + data.result + ')');
                        var systemRoles = jsonObject.system;
                        var menuRoles = jsonObject.menu;
                        //console.log(data.result);
                        //console.log(systemRoles);
                        //console.log(menuRoles);
                        RolesArr[0] = systemRoles;
                        RolesArr[1] = menuRoles;
                        //初始化根节点

                        //系统级别菜单
                        $.each($("#systemRolesRoot").find("input"),function(){
                            //console.log($(this).val());
                            if( $.inArray($(this).val(),systemRoles)>=0){
                                //console.log("执行点击事件");
                                $(this).click();
                            }
                        });
                        //菜单级别菜单
                        $.each($("#menuRolesRoot").find("input"),function(){
                            //console.log($(this).val());
                            //console.log($.inArray($(this).val(),menuRoles));
                            if( $.inArray($(this).val(),menuRoles)>=0){
                                //console.log("执行点击事件");
                                $(this).click();
                            }
                        });

                    }else{
                        ajaxInfo.removeClass("alert-info");
                        ajaxInfo.addClass("alert-danger");
                        ajaxInfo.show();
                        ajaxInfo.find('li').text(data.result);
                    }
                }
            });


        });

        $("#OpenEditRoles").find(".modal-footer").find('.saveButton').click(function(){
            //console.log("test");
            // 系统权限
            var systemRoles = [];
            $.each($("#systemRolesRoot").find("input:checked"),function(){
                systemRoles.push($(this).val());
            });
            //console.log(systemRoles);

            // 菜单权限
            var menuRoles = [];
            $.each($("#menuRolesRoot").find("input:checked"),function(){
                menuRoles.push($(this).val());
            });
            //console.log(menuRoles);

            var ajaxInfo = $("#OpenEditRoles").find("#ajaxInfo");

            //保存权限设置
            $.ajax({
                type: "POST",
                url: "{{route('admin.saveRole')}}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'id':$("#OpenEditRoles").find("input[name='id']").val(),
                    'systemRoles':systemRoles,
                    'menuRoles':menuRoles
                },
                dataType: "json",
                success: function(data, textStatus, jqXHR){
                    //console.log(data);
                    if(data.code){
                        ajaxInfo.removeClass("alert-danger");
                        ajaxInfo.addClass("alert-info");
                        ajaxInfo.show();
                        ajaxInfo.find('li').text(data.result+"(3秒后自动关闭。)");
                        setTimeout(function(){
                            $("#OpenEditRoles").find(".modal-footer").find('.closeButton').click();
                        },3000);
                    }else{
                        ajaxInfo.removeClass("alert-info");
                        ajaxInfo.addClass("alert-danger");
                        ajaxInfo.show();
                        ajaxInfo.find('li').text(data.result);
                    }
                }
            });

        });


    });
</script>
@endpush