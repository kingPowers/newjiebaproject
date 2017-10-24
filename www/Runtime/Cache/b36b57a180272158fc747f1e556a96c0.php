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
   /* WebKit browsers */
    ::-webkit-input-placeholder {
        color: #fff;  
    }         
</style>
    <header style="background: transparent;">
        <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>
        </a>        
    </header>
    <section class="bg_login">
        <img src="_STATIC_/2015/member/image/login/bg_login.png">
    </section>
    <section class="login_div con_div">
        <img src="_STATIC_/2015/member/image/login/ico_logo.png" class="logo_div">
        <section class="username">
            <img src="_STATIC_/2015/member/image/login/ico_phone.png"> 
            <input type="text" name="mobile" placeholder="请输入手机号">    
        </section> 
        <section class="username">
            <img src="_STATIC_/2015/member/image/login/ico_pass.png"> 
            <input type="password" name="password" placeholder="请输入密码">    
        </section>   
    </section>
    <input type="hidden" name="_login_" value="<?php echo ($_login_); ?>">
    <input type="hidden" name="redirect_url" value="<?php echo ($redirect_url); ?>">
    <input type="submit" name="log" value="登录" class="btn bgb btn_login">
    <input type="submit" name="reg" onclick="javascript:window.location.href='/Member/register';" value="注册" class="btn bgf btn_reg">
    <a class="for_div" href="/Member/recoverpwd">忘记密码?</a>
</body>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/login.js"></script>
<script type="text/javascript">
// $(".btn").click(function(){
//     remind(1);
// })
</script>
</html>