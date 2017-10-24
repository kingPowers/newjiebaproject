<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script type="text/javascript">var WWW = "_WWW_", STATIC = "_STATIC_", APP = "_APP_";</script>    
    <link rel="stylesheet" href="_STATIC_/2015/css/public.css">
    <link rel="stylesheet" href="_STATIC_/2015/css/reCSS.css">
    <link rel="stylesheet" href="_STATIC_/manager/box/wbox.css">
    <script src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>    
    <script src="_STATIC_/2015/js/public.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/manager/js/jquery.box.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/manager/js/wbox.js" type="text/javascript" charset="utf-8"></script>
<style type="text/css">
a{color: #fff}
</style>
<script type="text/javascript">

$(function(){
    $("#logOut").click(function(){
        jdbox.alert(2,'退出中');
        $.post("<?php echo U('Public/logout');?>",'',function(){
            window.location.href = '/login.html';
        })
    })
    $("#hide").click(function(){
      if($(".left_menu").css('width') == '240px'){
            $(".left_menu").css({width:0},"fast");
            $(".left_menu > ul").css("display","none");
            $(".right_con").css("margin-left","0");
            $("#hide").text('显示');
        }
      else{
            $(".left_menu").animate({width:240},"fast");
            $(".left_menu > ul").css("display","block");
            $(".right_con").css("margin-left","240px");
            $("#hide").text('隐藏');
        }
      resize();
    });
    getMenu();
    resize();
    setInterval(refreshTime,1000*60*5);
})
function up(_this)
{
    var first_nav = $(_this).next(".child_list").find("li:first").find("a");
    var src = first_nav.attr("url");
    var moduleid = first_nav.attr("moduleid");
    var actionid = first_nav.attr("actionid");
    changeHash(moduleid,actionid);
    changeIframeSrc(src);
    $(".left_menu").find(".normals").removeClass("fontb");
    first_nav.addClass("fontb");
    $(".father_list").next(".child_list").stop().slideUp("slow");
    $(_this).next(".child_list").stop().slideToggle("slow");
}
function changeHash(moduleid,actionid)
{
    window.location.hash = moduleid + "/" + actionid;
}
function changeIframeSrc(url='',modulename='',actionidname='')
{
    $("iframe[name='content-body']").attr("src",(!url)?(modulename + "/" + actionidname):url);
}
function getMenu()
{
    var moduleid = window.location.hash.split("/")[0].split("#")[1];
    var actionid = window.location.hash.split("/")[1];
    var modulename = '';
    var actionidname = '';
    var display = 0;
    $.getJSON("<?php echo U('/Public/getJsMenu');?>",{"is_menu":1},function(data){
        //console.log(data);
        var menu_str = '<ul>';
        $.each(data,function(i,item){
            if (!moduleid) moduleid = i;
            if (item.id == moduleid) {
                display++;
                modulename = item.name;
            }
            menu_str += '<li class="father_list up" onclick="up(this);"><a><img src="_STATIC_/' + item.icon + '"><font>' + item.title +'</font><img src="_STATIC_/2015/index/image/ico_left.png"></a></li>';
            var module_dis = display;
            menu_str += (display === 1)?'<ul class="child_list" style="display:block">':'<ul class="child_list">';
            for (var i = 0;i<item.child.length;i++){
                if (!actionid) actionid = item.child[i].id;
                if (item.child[i].id == actionid) {
                    display++;
                    actionidname = item.child[i].name.split("-")[1];
                }
                var classname = (display === 2)?"fontb":'';
                menu_str += '<li><a moduleid="' + item.id +'" actionid="' + item.child[i].id + '" url="/' + item.child[i].href + '" onclick="skip(this);" target="content-body" class="normals ' + classname +'">' + item.child[i].title + '</a>';
                display = module_dis;
            }
            display = 0;
            menu_str += '</ul>';   
        }) 
        window.location.hash = moduleid + "/" +actionid;
        $(".left_menu").append(menu_str);//alert(modulename + "/" + actionidname);
        $("iframe[name='content-body']").attr("src",modulename + "/" + actionidname);
    })
}
var skip = function(_this)
{
    var url = $(_this).attr("url");
    var moduleid = $(_this).attr("moduleid");
    var actionid = $(_this).attr("actionid");
    $(".left_menu").find(".normals").removeClass("fontb");
    $(_this).addClass("fontb");
    src = $("iframe[name='content-body']").attr("src",url);
    $(_this).attr("href",url);location.hash = moduleid + "/" +actionid;
    resize();
}
</script>
</head>
<body style="overflow-y: scroll; min-width: 1460px;">
<div class="header w100 head_blue">
    <div class="header_center">
        <div class="left_logo c_left">
            <h1 class="font_f">借吧后台管理系统</h1>
        </div>
        <div class="right_nav c_right">
            <a><img src="_STATIC_/2015/index/image/ico_man.png">您好！<?php echo ($_SESSION['user']['names']); ?></a>
            <a id="logOut"><img src="_STATIC_/2015/index/image/ico_esc.png">退出</a>
        </div>
    </div>
</div>
<div class="table_body">
    <div class="left_menu">
        <div class="btn_shrink">
            <font id="hide">隐藏</font>
        </div>
    </div>
    <div class="right_con" style="height: 1000px;">
    <iframe src="" style="margin: 20px 0 20px 10px;" frameborder="0" name="content-body" id="content-iframe" ></iframe>
   </div>
    <div style="clear:both;"></div>
    <input type="hidden" name="moduleid" value="">
    <input type="hidden" name="actionid" value="">
</div>
<script type="text/javascript">
	var resize = function()
	{
		var all_h = $(window).height();
		var head_h = $(".header").height();
		var left_height = $(".table_body").height();
		var all_w = $(window).width();
		var head_w = $(".header").width();
		var left_w = $(".left_menu").width();
		$("iframe[name='content-body']").attr("height","100%").attr("width","99%");	
	}
var refreshTime = function() 
{
	$.post("/Public/refreshTime.html",{},function(F){
	},'josn')
}
</script>