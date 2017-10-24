<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">       
        <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>{$pageseo.title}</title>
        <script type="text/javascript" src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/style.css" /> 
        <link rel="stylesheet" href="_STATIC_/2015/member/css/index.css" /> 
        <style type="text/css">
            .mui-con-li{display: block; overflow: hidden; padding: 10px 10px 10px 0}
            .ser_head{width: 18%; display: inline-block; float: left; margin-right: 5%;}
            .mui-con-li h4{float: left; width: 77%; margin-top: 2%;}
            .mui-con-li b{font-weight: normal; float: left; margin-top: 1%; color: #999;}
        </style>
    </head>
<body> 
    <header>
        <a href="/Member/account" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>在线客服</h1>       
    </header> 
    <section class="mt50 mui-con-view">
    <foreach name="list" item="vo">
    	<a class="mui-con-li" href="/Index/service_online/id/{$vo['user_id']}">
        <eq name="vo['isOnline']" value='1'>
    		<img src="_STATIC_/2015/member/image/index/ke.png" class="ser_head">
        <else/>
            <img src="_STATIC_/2015/member/image/index/black.png" class="ser_head">
        </eq>
            <h4 style="color: #000;">{$vo['names']}</h4>
            <b>您好！</b>
    	</a>
    </foreach>  
    </section>  
</body>
</html>


