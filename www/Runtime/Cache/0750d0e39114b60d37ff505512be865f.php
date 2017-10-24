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

<link rel="stylesheet" href="_STATIC_/2015/member/css/loan.css" /> 
<body style="background: #efefef;">
<header>
    <a href="javascript:history.back();" class="btn_back">
    <img src="_STATIC_/2015/member/image/login/return.png">
    <font class="fl">返回</font>       
    </a> 
    <h1>借款详情</h1>     
</header>
<section class="mt40 c100_div bgf" >
    <?php if($_GET['s'] == 1): ?><section class="forder_div">
            <b>应还金额（元）</b>
            <h2><?php echo (int)($info['repayment_money']+$info['late_fee']) ?></h2>   
            <span>2017-06-07待还款</span>     
        </section>
        <section class="con-li">
            <font>费  用</font>
            <span><b><?php echo ($info['total_fee']); ?> </b>元</span>
        </section>
        <a class="btn_lj" onclick="repayment(<?php echo ($info['id']); ?>);">立即还款</a>
    <?php else: ?>
        <section class="forder_div">
            <b>已结清金额（元）</b>
            <h2><?php echo ($info['back_real_money']); ?></h2>      
        </section>
        <section class="con-li">
            <font>费  用</font>
            <span><b><?php echo ($info['total_fee']); ?> </b>元</span>
        </section><?php endif; ?>  
</section>
<section class="forder_details">
    <section class="forder-view-cell">
        <span>借款编号</span>
        <font><?php echo ($info['tender_number']); ?></font>
    </section>
    <section class="forder-view-cell">
        <span>申请金额</span>
        <font><?php echo ($info['money']); ?>元</font>
    </section>
    <section class="forder-view-cell">
        <span>申请期限</span>
        <font><?php echo ($info['lp_name']); ?></font>
    </section>
    <section class="forder-view-cell">
        <span>到账金额</span>
        <font><?php echo ($info['pay_money']); ?>元</font>
    </section>
    <section class="forder-view-cell">
        <span>申请时间</span>
        <font><?php echo ($info['timeadd']); ?></font>
    </section>
    <section class="forder-view-cell">
        <span>还款时间</span>
        <font><?php echo ($info['back_real_time']); ?></font>
    </section>
    <section class="forder-view-cell">
        <span>还款方式</span>
        <font>一次性还款付息</font>
    </section>
    <section class="forder-view-cell">
        <span>借款状态</span>
        <font><?php echo ($info['order_status_name']); ?></font>
    </section>
    <section class="forder-view-cell">
        <span>收款银行卡</span>
        <font><?php echo ($member_info['bank_name']); ?>(尾号<?php echo (substr($member_info['acc_no'],-4,4)); ?>)</font>
    </section>
    <section class="forder-view-cell">
        <span>借款合同</span>
        <a onclick="tk();">查看</a>
    </section>
</section>
<script type="text/javascript">
var repayment = function(id) 
{
    if (!id) return remind("订单ＩＤ错误");
    var data = {};data.id = id;
    conAlert("是否确认还款",repay,data);
}
var repay = function(data)
{
    $.DialogByZ.Close();
    wait();
    $.post("/Loan/repayment",data,function(F){
        remind(F.info);
        if (F.status) {
            window.location.href = "/Loan/loan_history";
        }
    },'json')
}
</script>