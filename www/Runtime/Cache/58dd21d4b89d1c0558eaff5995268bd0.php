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

    <link rel="stylesheet" href="_STATIC_/2015/member/css/loan.css" />
    <header>
        <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>借款</h1>       
    </header> 
    <section class="mt40 mui-con-view">
    	<section class="mui-con-li li_money">
    		<font>借多少</font>
            <input type="text" name="loanmoney" max="<?php echo ($allow_money['maxMoney']); ?>" min="<?php echo ($allow_money['minMoney']); ?>" placeholder="单笔可借<?php echo ($allow_money['minMoney']); ?>-<?php echo ($allow_money['maxMoney']); ?>元" class="text_money" style="padding-right: 10%;">
    	</section>
    	<section class="mui-con-li bg_jt">
    		<font>借多久</font>
            <span class="jtime" pro_id="<?php echo ($loan_list[0]['id']); ?>"><?php echo ($loan_list[0]['periodeName']); ?></span>
    	</section>
    	<section class="mui-con-li bg_jt">
    	    <font>怎么还</font> 
            <span class="loan_type"><?php echo ($loan_list[0]['repaymentName']); ?></span>           
    	</section>
    	<section class="mui-con-li">
    		<font>手续费用</font>
            <span><b class="total_fee"></b><a class="ico_wh"><img src="_STATIC_/2015/member/image/loan/ico_detail.png" ></a></span>            
    	</section>   
        <section class="mui-con-li">
            <font>到账金额</font>
            <span class="pay_money"></span>              
        </section>   
    </section>  
    <input type="submit" name="sub" value="申请借款" class="btn bgb" id="sub">
    <section class="tkbtm_bg" style="display: none;">
    <?php if(is_array($loan_list)): foreach($loan_list as $key=>$vo): ?><a class="jday" pro_id="<?php echo ($vo['id']); ?>" pro_min="<?php echo ($vo['min_loanmoney']); ?>" pro_max="<?php echo ($vo['max_loanmoney']); ?>" pro_ret="<?php echo ($vo['repaymentName']); ?>"><?php echo ($vo['periodeName']); ?></a><?php endforeach; endif; ?>
        <a style="margin-top: 5px;" class="cancel">取消</a>
    </section>
    <section class="tkjin_bg bgf" style="display: none;">
        <h1>费用详情</h1>
        <ul>
            <li>借款费用<font class="periode_fee">0元</font></li> 
            <li>手续费<font class="procedure_fee">0元</font></li> 
            <li>平台管理费<font class="plat_fee">0元</font></li>
            <li>费用总和<font class="total_fee">0元</font></li>
        </ul>
        <input type="hidden" name="_loanapply_" value="<?php echo ($_loanapply_); ?>">           
        <section class="btn_ture" id="tl_close">确定</section> 
    </section>
    <section class="tkgrayBg" style="height: 100%; display: none;"></section>
</body>
</html>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/loan_apply.js"></script>
<script type="text/javascript">
    $(function(){
        $(".jtime").click(function(){
            $(".tkgrayBg").show();
            $(".tkbtm_bg").show();
            $(".cancel").click(function(){
                $(".tkgrayBg,.tkbtm_bg").hide();
            })
        })
        $(".ico_wh").click(function(){
            $(".tkgrayBg").show();
            $(".tkjin_bg").show();
            $("#tl_close").click(function(){
                $(".tkgrayBg,.tkjin_bg").hide();
            })
        })
    })
</script>