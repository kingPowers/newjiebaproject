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
    <section class="tkjin_bg bgf" style="display: none;">        
        <h3 class="bgl">
            车友贷
        </h3>
        <section class="c100_div ta_center con_c">
            <span>申请金额(元)</span>
            <font class="fb" id="sq">0.00</font>
            <input type="range" name = "range"  id = "range" min = "1" max = "100" class="range" pro_id=''>
            <span>借款天数(天)</span>
            <section class="periode_content">
                <a id="bgl">7天</a>
                <a>15天</a>
                <a>30天</a> 
            </section>           
        </section>
        <section class="bgl tk_bottom">
            <span>预计到账金额(元)</span>
            <h1>(请选择借款金额)</h1>
        </section>
    </section> 
    <section class="tkgrayBg" style="height: 100%;display: none;"></section>   
    <section class="bgl">
        <a href="/Index/message">
            <span id="info" class="top_info">
                <?php if(!empty($count)): ?><img src="_STATIC_/2015/member/image/index/d.png" class="red_info">
                    <font color="#fff"><?php echo ($count); ?></font><?php endif; ?>
            </span>
        </a>
        <section class="con_top">
            <p>最高可借金额（元）</p>
            <h2><?php echo ($data['quota']['maxMoney']); ?></h2>
        </section>
        <section class="num_top">
            <section class="h_num">
                <p>最长可借天数</p>
                <font><?php echo ($data['maxPeriode']); ?></font>
            </section>
            <section class="h_num">
                <p>最低起借金额</p>
                <font><?php echo ($data['minLoanMoney']); ?></font>
            </section>
        </section>
    </section>
    <section class="p_center">
        <span>借款成功:<font class="fb"><?php echo ($data['tenderSuccess']); ?>人</font></span>
        <a class="calculator" onclick="calculator_display();"><img src="_STATIC_/2015/member/image/index/ico_c.png"></a>
    </section>
    <p class="fb f12 c100_div mt15 ta_center">发起借款申请，最快20分钟放款</p>
    <a href="/Loan/index" class="btn bgb" style="margin-top: 10px;">申请贷款</a>
    <footer>
    	<a href="/Index/index"><span id="in" class="ico_footer"></span></a>
        <a href="/Member/account"><span id="ac" class="ico_footer"></span></a>
    </footer>
</body>
</html>
<script type="text/javascript">
$(function(){
    $(".range").change(function(){
        getPayMoney();
    })
    $(".tkgrayBg").click(function(){
        $(".tkgrayBg").hide();
        $(".tkjin_bg").hide();
    })
})
var calculator_display = function()
{
    wait();
    $.post("/Index/getProductList",{},function(F){
        //console.log(F);
        if (F.status) {
            alertClose();
           if (false == productList(F.data)) return remind("本商户尚未添加产品，请稍后再试");
            $(".tkgrayBg").show();
            $(".tkjin_bg").show();
        } else {
            remind(F.info);
        }
    },'json')
    
}
var getPayMoney = function()
{
    var money = $(".range").val();
    $("#sq").text(money+"00.00");
    var data = {};
    data.pro_id = $(".range").attr("pro_id");
    data.money = money*100;
    $.post("/Index/getPayMoney",data,function(F){
        //console.log(F);
        $(".tk_bottom h1").html(F.data.pay_money);
    },"json")
}
var productList = function(data)
{
    var pro_str = '',first_pro;
    $(".periode_content").html('');
    if (jQuery.isEmptyObject(data)) return false;
    $.each(data,function(i,item){
        if (i == 0) {
            first_pro = item;
            pro_str += "<a onclick='changePro(this);' id='bgl' pro_id='" + item.id +"' pro_min='"+item.min_loanmoney+"' pro_max='"+item.max_loanmoney+"'>" + item.periodeName + "</a>";
        } else {
             pro_str += "<a onclick='changePro(this);' pro_id='" + item.id +"' pro_min='"+item.min_loanmoney+"' pro_max='"+item.max_loanmoney+"'>" + item.periodeName + "</a>";
        }     
    })
    $("#sq").text(first_pro.min_loanmoney);
    $("input[name='range']").attr("min",parseInt(first_pro.min_loanmoney/100)).attr("max",parseInt(first_pro.max_loanmoney/100)).attr("pro_id",first_pro.id).val(parseInt(first_pro.min_loanmoney/100));
    $(".periode_content").append(pro_str);
}
var changePro = function(_this)
{
    $(".periode_content").find("a").removeAttr("id");
    $(_this).attr("id","bgl");
    $("#sq").html($(_this).attr("pro_min"));
    $(".range").attr("min",parseInt($(_this).attr("pro_min")/100)).attr("max",parseInt($(_this).attr("pro_max")/100)).attr("pro_id",$(_this).attr("pro_id")).val(parseInt($(_this).attr("pro_min")/100));
    getPayMoney();
}
</script>