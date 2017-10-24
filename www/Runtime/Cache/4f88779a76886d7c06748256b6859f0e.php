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

<link rel="stylesheet" href="_STATIC_/2015/member/css/loan.css" />
<script type="text/javascript">
    $(function () {
        $("#agree").attr('checked',false);
        $("#agree").change(function(){
            $(".middle_div").toggle();
        })
    })
</script> 
<body style="background: #efefef;">
<header>
	<a href="/Loan/loan_history" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
    </a> 
    <h1>签署合约</h1>    
</header>
<section class="mui-con-view mt40">
	<section class="mui-con-li" style="text-align: center; margin: 0;">
		<a style="color: #5495e6; ">个人借款协议</a>
	</section>
	<section class="mui-con-li" style="text-align: center; margin: 0;">
		<a style="color: #5495e6; ">居间服务协议</a>
	</section> 
</section>
<section class="agree">
	<input type="checkbox" name="agree" id="agree" value="1" />同意贷款相关合同（勾选之后才可签约）
</section>

<section class="middle_div" style="display:none;">
	<p>我们将发送<font>验证码</font>到您的手机</p>
	<p><?php echo ($mobile); ?></p>	
	<section class="btn_div">
        <input type="hidden" name="loan_id" value="<?php echo ($_REQUEST['id']); ?>">
		<input type="text" id="text_yzm" name="verify"  maxlength='6' placeholder="请输入短信验证码" class="input_yzmb fl">
		<input type="button" name="sub"  value="获取验证码" class="btn_yzmb fl" id="send_ems">
	</section>
</section>
<input type="hidden" name="_loansign_" value="<?php echo ($_loansign_); ?>">
 <input type="submit" name="" value="签约" class="btn bgb sign_sub" id="sub">  
</body>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/sign.js"></script>
</html>