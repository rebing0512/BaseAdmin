$(function(){
    //菜单点击
    //J_iframe
    $(".J_menuItem").on('click',function(){
        $(this).parents("li").siblings().removeClass("active");
        $(this).parents("li").siblings().find("ul").removeClass("in");//collapse
        $(this).parents("li").addClass("active");
        //console.log(test);
        var url = $(this).attr('href');
        $("#J_iframe").attr('src',url);
        //console.log("test:"+url);
        return false;
    });
    $("#side-menu").find("li").find("a").on("click",function(){
        $("#mbcore_nav_title").html($(this).find(".nav-label").text());
    });
});