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
    <script type="text/javascript">
      $(function () {            
            var ary = $(".listyle").click(function () {
                $(this).parent().find("a.show").addClass("hide").removeClass("show blue_bg");   
                $(this).addClass("show blue_bg").removeClass("hide");   
                $("#con_div > section.hShow").addClass("hHide").removeClass("hShow"); 
                $("#con_div > section:eq(" + $.inArray(this, ary) + ")").addClass(function () {  
                    return "hShow";   
                }).removeClass("hHide");  
            }).toArray();
        }); 
    </script>
<body> 
    <header>
        <a href="/Member/account" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>借款记录</h1>       
    </header> 
    <nav>
        <input type="hidden" name="status" value="<?php echo ($_REQUEST['s']); ?>">
        <a href="/Loan/loan_history" <?php if($_REQUEST['s'] == 0): ?>class="listyle blue_bg show"<?php else: ?>class="listyle hide"<?php endif; ?>>全部</a>
        <a href="/Loan/loan_history/s/1" <?php if($_REQUEST['s'] == 1): ?>class="listyle blue_bg show"<?php else: ?>class="listyle hide"<?php endif; ?>>还款中</a>
        <a href="/Loan/loan_history/s/2" <?php if($_REQUEST['s'] == 2): ?>class="listyle blue_bg show"<?php else: ?>class="listyle hide"<?php endif; ?>>已还款</a>
    </nav>
    <section id="con_div">
        <section class="c100_div hShow">
        <?php if(is_array($list)): foreach($list as $key=>$vo): ?><section onclick="jump('<?php echo ($vo["jumpurl"]); ?>','<?php echo ($vo["id"]); ?>')" class="history_one c100_div bgf">   
                <section class="con_div p_center">
                    <span>编号:<?php echo ($vo['tender_number']); ?></span>
                    <font><?php echo ($vo['order_status_name']); ?></font>
                </section>            
                <ul>
                    <li>
                        <b>金额(元)</b>
                        <p><?php echo ($vo['money']); ?></p>
                    </li>
                    <li>
                        <b>期限(天)</b>
                        <p><?php echo ($vo['lp_name']); ?></p>
                    </li>
                    <li>
                        <b>借款日期</b>
                        <p><?php echo ($vo['timeadd']); ?></p>
                    </li>
                </ul>
            </section><?php endforeach; endif; ?> 
    </section>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/loan_history.js"></script>  
<script type="text/javascript">
    //$(window).scroll(function(){alert(1);})
</script>  
</body>
</html>