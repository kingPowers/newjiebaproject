<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script type="text/javascript">var WWW = "_WWW_", STATIC = "_STATIC_", APP = "_APP_";</script>    
    <link rel="stylesheet" href="_STATIC_/2015/css/public.css">
    <link rel="stylesheet" href="_STATIC_/2015/css/reCSS.css">
    <link rel="stylesheet" href="_STATIC_/manager/box/wbox.css">
    <!-- <link href="__PUBLIC__/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/assets/css/main-min.css" rel="stylesheet" type="text/css" /> --><script src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>    
    <!-- <script type="text/javascript" src="__PUBLIC__/assets/js/bui.js"></script> 
    <script type="text/javascript" src="__PUBLIC__/assets/js/common/main.js"></script> 
    <script type="text/javascript" src="__PUBLIC__/assets/js/config.js"></script >--> 
    
    <script src="_STATIC_/2015/js/public.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/manager/js/jquery.box.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/manager/js/wbox.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

$(function(){
    // $(".up").click(function(){
    //         $(this).next(".child_list").slideToggle("slow");
    //     })
    $("#logOut").click(function(){
        jdbox.alert(2,'退出中');
        $.post("<?php echo U('Public/logout');?>",'',function(){
            window.location.href = '/login.html';
        })
    })
    getMenu();
})
function up(_this)
{
    $(_this).next(".child_list").slideToggle("slow");
}
function getMenu()
{
    $.getJSON("<?php echo U('/Public/getJsMenu');?>",{"is_menu":1},function(data){
        console.log(data);
        var menu_str = '<ul>';
        $.each(data,function(i,item){
            menu_str += '<li class="father_list up" onclick="up(this);"><a><img src="_STATIC_/2015/index/image/ico_auth.png"><font>' + item.title +'</font><img src="_STATIC_/2015/index/image/ico_left.png"></a></li>';
            menu_str += '<ul class="child_list">';
            for (var i = 0;i<item.child.length;i++){
                menu_str += '<li><a href="/' + item.child[i].href + '" class="fontb">' + item.child[i].title + '</a>';
            }
             menu_str += '</ul>';   
        })
        $(".left_menu").append(menu_str);
        // var config = [], topmenu = '',B = true;
        // for(var i in data ){
        //     var M={},N={},O=[],I=[];
        //     for(var j in data[i]['child']){
        //         var T={};
        //         $(T).attr('id',data[i]['child'][j]['id']);
        //         $(T).attr('text',data[i]['child'][j]['title']);
        //         $(T).attr('href','/'+data[i]['child'][j]['name'].replace('-','/')+'.html');
        //         I.push(T);
        //     }
        // }
        // $(N).attr('text',data[i]['title']);
        // $(N).attr('items',I);
        // O.push(N);
        // $(M).attr('menu',O);
        // $(M).attr('id',data[i]['id']);
        // if(I.length > 0){
        //     $(M).attr('homePage',I[0]['id']);
        // }
        // config.push(M);
    })
}
</script>
</head>
<body>
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
        <!--<ul>
             <li class="father_list"><a ><img src="_STATIC_/2015/index/image/ico_index.png"><font>主页</font><img src="_STATIC_/2015/index/image/ico_left.png"></a></li>
            <li class="father_list up"><a ><img src="_STATIC_/2015/index/image/ico_auth.png"><font>权限管理</font><img src="_STATIC_/2015/index/image/ico_left.png"></a></li>
            <ul class="child_list">
                <li><a class="fontb">角色管理</a></li>
                <li><a>用户管理</a></li>
                <li><a>工作流配置</a></li>
            </ul>
            <li class="father_list"><a ><img src="_STATIC_/2015/index/image/ico_user.png"><font>用户管理</font><img src="_STATIC_/2015/index/image/ico_left.png"></a></li>
            <li class="father_list"><a href=""><img src="_STATIC_/2015/index/image/ico_pro.png"><font>产品管理</font><img src="_STATIC_/2015/index/image/ico_left.png"></a></li>
            <li class="father_list"><a href=""><img src="_STATIC_/2015/index/image/ico_pay.png"><font>贷款管理</font><img src="_STATIC_/2015/index/image/ico_left.png"></a></li>
            <li class="father_list"><a href=""><img src="_STATIC_/2015/index/image/ico_time.png"><font>逾期管理</font><img src="_STATIC_/2015/index/image/ico_left.png"></a></li>
            <li class="father_list"><a href=""><img src="_STATIC_/2015/index/image/ico_news.png"><font>结算管理</font><img src="_STATIC_/2015/index/image/ico_left.png"></a></li>             
        </ul>-->
        <div class="btn_shrink">
            <font id="hide">隐藏</font>
        </div>
    </div>
    <div class="right_con">