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

<body style="background: #fff;"> 
    <section class="c100_div">
    	<img src="_STATIC_/2015/member/image/account/bg_account.jpg">
    </section>
    <section class="bg_info">
        <img src="_STATIC_/2015/member/image/account/bg_info.png">
        <section class="name_div">
            <img src="_STATIC_/2015/member/image/account/heads.png">
            <p>您好！
                <?php if(($member_info['nameStatus']) == "1"): echo ($member_info['names']); ?><br/>
                已实名认证
                <?php else: ?>
                <?php echo ($member_info['username']); ?><br/>
                未实名认证<?php endif; ?>
            </p>
            <section class="a_money">
                <section class="ney">
                    <h2><?php echo ($member_info['loan_money']); ?></h2>
                    <font>已借款金额（元）</font>
                </section>
                <section class="ney">
                    <h2><?php echo ($member_info['repayment_money']); ?></h2>
                    <font>待还金额（元）</font>
                </section>
            </section>
        </section>
    </section>
    <section class="con_div mui-con-view" style="margin: 16% auto;">
        <ul class="account-ul">
            <li onclick="javascript:window.location.href='/Loan/loan_history'">
                <font><img src="_STATIC_/2015/member/image/account/ico_01.png" class="a_img"></font>
                <span>借款记录</span>             
            </li >
            <li onclick="isBind(<?php echo ($member_info['bankStatus']); ?>);">
                <font><img src="_STATIC_/2015/member/image/account/ico_02.png" class="a_img"></font>
                <span>银行卡</span>             
            </li>
            <li onclick="javascript:window.location.href='/Member/resetpwd'">
                <font><img src="_STATIC_/2015/member/image/account/ico_03.png" class="a_img"></font>
                <span>修改密码</span>             
            </li>
            <li onclick="javascript:window.location.href='/Index/service_list'">
                <font><img src="_STATIC_/2015/member/image/account/ico_04.png" class="a_img"></font>
                <span>在线客服</span>             
            </li>
            <li onclick="javascript:window.location.href='/Member/appset'">
                <font><img src="_STATIC_/2015/member/image/account/ico_05.png" class="a_img"></font>
                <span>设置</span>             
            </li>
        </ul>
        <section class="tel">客服热线：<a href="tel:4006639066"><?php echo ($member_info['company_mobile']); ?></a></section>
    </section>
    <footer>
    	<a href="/Index/index"><span id="sy" class="ico_footer"></span></a>
        <a href="/Member/account"><span id="zh" class="ico_footer"></span></a>
    </footer>
</body>
<script type="text/javascript">
var isBind = function(status)
{
    window.location.href = (status == 1)?"/Member/bank_card":"/Index/realname";
}
</script>
</html>