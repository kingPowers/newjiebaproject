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
<link rel="stylesheet" href="_STATIC_/2015/member/css/index.css" />
<script type="text/javascript" src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="_STATIC_/2015/member/js/tk.js"></script>
<script type="text/javascript">var STATIC="_STATIC_";</script>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/public/wbox.js"></script>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/public/jquery.box.js"></script>
<script type="text/javascript">
function alerts(){
      $.DialogByZ.Close();
   }
var remind = function(content='')
{
	$.DialogByZ.Alert({Title: "提示", Content: content,BtnL:"确定",FunL:alerts})
}
var conAlert = function(content,ensure,cancle = alerts)
{
    $.DialogByZ.Confirm({Title: "提示", Content: content,FunL:ensure,FunR:cancle})
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

<body> 
    <header>
        <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>实名认证</h1>       
    </header>
    <section class="mt40 mui-con-view">
    	<section class="mui-con-li">
    		<font>用户姓名</font>
    		<input type="text" name="names" placeholder="请输入您的姓名">
    	</section>
    	<section class="mui-con-li">
    		<font>身份证号</font>
    		<input type="text" name="certiNumber" placeholder="请输入您的身份证号码">
    	</section>
        <section class="mui-con-li bank-list">
            <font>开户银行</font>
            <input type="text" name="bank_name" disabled="" style="background: #fff" placeholder="请选择您的开户银行" id="bank">
        </section>
        <ul class="order-gray-li">
            <?php if(is_array($bank_list)): foreach($bank_list as $key=>$vo): ?><li bank_key="<?php echo ($key); ?>" class="bank_li"><?php echo ($vo); ?></li><?php endforeach; endif; ?>
        </ul>
    	<section class="mui-con-li">
    		<font>银行卡号</font>
    		<input type="text" name="bank_acc" placeholder="请输入您的银行卡号">
    	</section> 
    	<section class="mui-con-li">
    		<font>手机号码</font>
    		<input type="text" name="mobile" maxlength="11" placeholder="请输入银行预留手机号">
    	</section>    
    	<section class="mui-con-li">
    		<font>验证号码</font>
    		<input type="text" name="verify" maxlength="6" placeholder="请输入验证码" id="input_yzm">
    		<a class="btn_yzm">获取验证码</a>
    	</section>    	   
    </section>
    <section class="mui-con-li fg" style="border: 0">
    	温馨提示：请填写真实有效的身份信息，以便通过借款审核，一经认证，信息无法修改。
    </section>  
    <input type="hidden" name="_bindcard_" value="<?php echo ($_bindcard_); ?>">
    <input type="submit" name="sub" value="提交" class="btn bgb bind_sub" id="sub">
</body>
</html>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/bind.js"></script>
<script type="text/javascript">
    $(function(){
        $(".bank-list").click(function(){
            $(".order-gray-li").slideToggle();
        })
    })
</script>