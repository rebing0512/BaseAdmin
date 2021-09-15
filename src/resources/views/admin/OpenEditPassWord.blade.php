<div id="OpenEditPassWord" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> 重设密码</h4>
            </div>
            <div class="modal-body form-horizontal" >

                <input type="hidden" name="id" value="">

                <div class="form-group">
                    <label class="col-sm-2 control-label">姓名</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="fullName" value="">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">邮箱</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="email" value="">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">密码</label>

                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="password" value="">
                        <span class="help-block m-b-none">密码为空不进行修改</span>
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
    <script>
    $(document).ready(function () {


        // 隐藏提示窗口
        var ajaxInfo =   $("#OpenEditPassWord").find("#ajaxInfo");

        $(".EditPassWord").click(function(){

            ajaxInfo.hide();

            // 信息ID
            var thisid = $(this).attr("rel");
            var thisTr = $('tr[rel='+thisid+']');
            var DateDiv = $("#OpenEditPassWord");


            // 关键信息
            DateDiv.find("input[name=id]").val(thisid);

            var fullName = thisTr.find('td[data-name=fullName]').text();
            DateDiv.find("input[name=fullName]").val(fullName);

            var email = thisTr.find('td[data-name=email]').text();
            DateDiv.find("input[name=email]").val(email);
            //console.log(thisid)
        });

        $("#OpenEditPassWord").find(".modal-footer").find('.saveButton').click(function(){
            //console.log("test");
            //三秒后关闭
            /*
             setTimeout(function(){
             $(".modal-footer").find('.closeButton').click();
             },3000);
             */

            var DateDiv = $("#OpenEditPassWord");
            $.ajax({
                type: "POST",
                url: "{{route('admin.editsave')}}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'id':DateDiv.find("input[name=id]").val(),
                    'fullName':DateDiv.find("input[name=fullName]").val(),
                    'email':DateDiv.find("input[name=email]").val(),
                    'password':DateDiv.find("input[name=password]").val()
                },
                dataType: "json",
                success: function(data, textStatus, jqXHR){
                    if(data.code){
                        ajaxInfo.removeClass("alert-danger");
                        ajaxInfo.addClass("alert-info");
                        ajaxInfo.show();
                        ajaxInfo.find('li').text(data.result+"(3秒后自动关闭。)");

                        // 更新表格内的信息
                        var thisid = DateDiv.find("input[name=id]").val();
                        var thisTr = $('tr[rel='+thisid+']');
                        thisTr.find('td[data-name=fullName]').text(DateDiv.find("input[name=fullName]").val());
                        thisTr.find('td[data-name=email]').text(DateDiv.find("input[name=email]").val());


                        setTimeout(function(){
                            $("#OpenEditPassWord").find(".modal-footer").find('.closeButton').click();
                        },3000);
                    }else{
                        ajaxInfo.removeClass("alert-info");
                        ajaxInfo.addClass("alert-danger");
                        ajaxInfo.show();
                        ajaxInfo.find('li').text(data.result);
                    }

                    //alert("code:"+data.code+"\nresult:"+data.result+"\ntextStatus:"+textStatus+"\njqXHR:"+jqXHR);
                }
            });
        });

    });
</script>
@endpush