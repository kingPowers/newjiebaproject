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

    <header>
        <a href="/Member/account" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>修改密码</h1>       
    </header>  
    <section class="mt50 mui-con-view">
    	<section class="mui-con-li">
    		<font style="width: 30%;">原 密 码</font>
    		<input type="text" name="password" placeholder="请输入原密码">
    	</section>
    	<section class="mui-con-li">
    		<font style="width: 30%;">新 密 码</font>
    		<input type="text" name="newpassword" placeholder="请输入新密码">
    	</section>
    	<section class="mui-con-li">
    		<font style="width: 30%;">确认密码</font>
    		<input type="password" name="renewpassword" placeholder="请再次输入新密码" >    		
    	</section>    
    </section>    
    <input type="hidden" name="_resetpwd_" value="<?php echo ($_resetpwd_); ?>">
    <input type="submit" name="sub" value="提交" class="btn bgb reset_sub" id="sub">
</body>
<script type="text/javascript">
$(function(){
    $('.reset_sub').click(function(){
        var data = {};
        data.password = $("input[name='password']").val();
        data.newpassword = $("input[name='newpassword']").val(); 
        data.renewpassword = $("input[name='renewpassword']").val();
        data.is_reset = 1;
        data._resetpwd_ = $("input[name='_resetpwd_']").val();
        if (!/^[0-9a-zA-Z]{6,12}$/.test(data.newpassword)) {
            return remind("密码格式为6-12位数字或字母");
        }
        if (data.newpassword != data.renewpassword) {
            return remind("两次密码不一致");
        }
        wait();
        $.post("/Member/resetpwd",data,function(F){
            remind(F.info);
            if (F.status) {
                window.location.href = "/Member/account";
            }
        },'json')
    })
})
</script>
</html>