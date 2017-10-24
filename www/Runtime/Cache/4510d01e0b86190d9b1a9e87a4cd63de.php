<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title><?php echo ($pageseo["title"]); ?></title>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/style.css" />

<link rel="stylesheet" type="text/css" href="_STATIC_/2015/js/wwwmain/public/box/wbox.css">
<link rel="stylesheet" href="_STATIC_/2015/member/css/zdialog.css" /> 
<link rel="stylesheet" href="_STATIC_/2015/member/css/reset.css" />
<link rel="stylesheet" href="_STATIC_/2015/member/css/index.css" /><script type="text/javascript">var STATIC="_STATIC_";</script>
<script type="text/javascript" src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="_STATIC_/2015/member/js/tk.js"></script>
<script type="text/javascript">
function alerts(){
      $.DialogByZ.Close();
   }
var remind = function(content='')
{
	$.DialogByZ.Alert({Title: "提示", Content: content,BtnL:"确定",FunL:alerts})
}
var conAlert = function(content,ensure,ldata,cancle = alerts)
{
    $.DialogByZ.Confirm({Title: "提示", Content: content,FunL:ensure,Ldata:ldata,FunR:cancle})
}
var wait = function(content='')
{
	$.DialogByZ.Loading({Title: "提示", Content: content,BtnL:"确定",FunL:alerts})
}
var alertClose = function()
{
    $.DialogByZ.Close();
}
function sendCode(thisBtn)
{	
    var nums = 60;
    var btn = $(thisBtn);
    btn.html("重新发送(" + nums + ")");
    btn.css({"background":"#cccccc","color":"#999"});
    var clock = setInterval(function(){
    	nums--;
        if (nums > 0) {
        	btn.html("重新发送(" + nums + ")");
        } else {
            clearInterval(clock);
            btn.removeAttr("disabled");
            btn.html('获取验证码');
            nums = 60;isSend = 1;
            btn.css({"background":"transparent","color":"#63ABE8"});
        }
    }, 1000);
}
</script>
     </head>
<body>

<style type="text/css">
    .mui-con-li font{width: 20%;}
</style>
    <header>
        <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>找回密码</h1>       
    </header>  
    <section class="mt50 mui-con-view">
    	<section class="mui-con-li">
    		<font>手 机 号</font>
    		<input type="text" name="mobile" placeholder="请输入手机号">
    	</section>
    	<section class="mui-con-li">
    		<font>验 证 码</font>
    		<input type="text" name="verify" placeholder="请输入验证码" id="input_yzm">
    		<a class="btn_yzm">获取验证码</a>
    	</section>
    	<section class="mui-con-li">
    		<font>新 密 码</font>
    		<input type="password" name="newpassword" placeholder="6-12位字母、数字结合" id="input_pwd">    		
    	</section>    
    </section>    
    <input type="hidden" name="_recoverpwd_" value="<?php echo ($_recoverpwd_); ?>">
    <input type="submit" name="sub" value="提交" class="btn bgb" id="sub">
</body>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/recoverpwd.js"></script>
</html>