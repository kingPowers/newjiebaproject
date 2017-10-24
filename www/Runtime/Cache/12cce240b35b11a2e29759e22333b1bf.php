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

<body> 
    <header>
        <a href="/Index/index" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>消息中心</h1>       
    </header> 
    <section class="mt40 mui-con-view">
    <?php if(is_array($list)): foreach($list as $key=>$vo): ?><section class="mui-con-li" onclick="javascript:window.location.href='/Index/msgcontent/id/<?php echo ($vo["id"]); ?>'">
    		<i <?php if(!empty($vo['is_read'])): ?>style="visibility:hidden;"<?php endif; ?>></i><font><?php echo ($vo['title']); ?></font><time><?php echo ($vo['timeadd']); ?></time>
            <p><?php echo ($vo['summary']); ?></p>
    	</section><?php endforeach; endif; ?>
    </section>  
</body>
<script type="text/javascript">
$(function(){
    page = 2; is_loading = 1;
    $(window).scroll(function(){
        if($(document).height()==($(this).height()+$(this).scrollTop()) && is_loading==1){
            var data= {};
            $(data).attr("is_ajax",1);
            $(data).attr("p",page++);
            wait();
            $.post("/Index/message",data,function(F){
                console.log(F);
                alertClose();
                if ((F.status == 1) && F.data) {
                    var list_str = '';
                    $.each (F.data,function(i,item) {
                        list_str += '<section class="mui-con-li" onclick="jump(' + item.id + ');">';
                        list_str += (item.is_read)?'<i></i>':'<i style="visibility:hidden;"></i>';
                        list_str += '<font>' + item.title +'</font><time>' + item.timeadd +'</time>';
                        list_str += '<p>' + item.summary +'</p></section>'
                    })
                    $(".mui-con-view").append(list_str);
                } else {
                    remind(F.info);
                    is_loading=0;
                }
            },'json')
        }
    });             
})
var jump = function(id) 
{
    if (id=='')return remind("新闻ID错误");
    window.location.href = "/Index/msgcontent/id/" + id;
}
</script>
</html>