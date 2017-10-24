<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script type="text/javascript">var WWW = "_WWW_", STATIC = "_STATIC_", APP = "_APP_";</script>    
    <link rel="stylesheet" href="_STATIC_/2015/css/public.css">
    <link rel="stylesheet" href="_STATIC_/2015/css/reCSS.css">
    <link rel="stylesheet" href="_STATIC_/manager/box/wbox.css">
    <script type="text/javascript" src="_STATIC_/2015/js/wdate/WdatePicker.js"></script>
    <script src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>    
    <script src="_STATIC_/2015/js/public.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/manager/js/jquery.box.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/manager/js/wbox.js" type="text/javascript" charset="utf-8"></script>   
    <link rel="stylesheet" href="_STATIC_/2015/member/css/zdialog.css" />
    <link rel="stylesheet" href="_STATIC_/2015/member/css/reset.css" />
    <script type="text/javascript" src="_STATIC_/2015/member/js/tk.js"></script>
    <style type="text/css">
    </style>
<script type="text/javascript">
var conAlert = function(content,ensure,ldata,cancle = alerts)
{
    $.DialogByZ.Confirm({Title: "提示", Content: content,FunL:ensure,Ldata:ldata,FunR:cancle})
}
function alerts(){
      $.DialogByZ.Close();
}
</script>

<link rel="stylesheet" href="_STATIC_/manager/css/index.css">
<script type="text/javascript">
$(function(){
	$(".all_st").click(function(){
		var status = $(this).attr("value");
		$("select[name='status']").find("option").removeAttr("selected");
		$("input[name='orderStatus']").val('');
		$("select[name='status']").find("option[value='" + status +"']").attr("selected",'selected');
		$("#search-form").submit();
	})
	$(".order_st").click(function(){
		var orderStatus = $(this).attr("value");
		$("input[name='orderStatus']").val(orderStatus);
		$("#search-form").submit();
	})
	setInterval(refresh,5000);
})
var refresh = function()
{
	window.location.reload();
}
</script>
<div class="w98 bgf">
    <form id="search-form" method="get" action="/Order/index">
    <input type="hidden" name="orderStatus" value="<?php echo ($_GET['orderStatus']); ?>">
	
	</form>
	<div class="nav">
		<ul>
			<li class="">消息列表</li>
		</ul>
	</div>
	<div class="tablediv">
		<table class="member_table">
			<tr>
				<th>用户ID</th>
				<th>用户名</th>
				<th>用户姓名</th>
				<th>用户手机号</th>
				<th>最后消息时间</th>
			</tr>
			<?php if(is_array($messages)): foreach($messages as $key=>$vo): ?><tr>
				<td><?php echo ($vo['id']); ?></td>
				<td><a onclick="javascript:return top.jdbox.iframe('/MemberMsg/getMessage/id/<?php echo ($vo["id"]); ?>',{title:1111});"><?php echo ($vo['username']); ?>(<?php echo (($vo["num"])?($vo["num"]):0); ?>)</a></td>
				<td><?php if(empty($vo['names'])): ?>客户<?php else: echo ($vo['names']); endif; ?></td>
				<td><?php echo ($vo['mobile']); ?></td>
				<td><?php echo ($vo['max_created']); ?></td>
							
			</tr><?php endforeach; endif; ?>			
		</table>
	</div>
	<?php echo ($page); ?>
</div>
   </div>
    <div style="clear:both;"></div>
    <input type="hidden" name="moduleid" value="">
    <input type="hidden" name="actionid" value="">
</div>
<script type="text/javascript">
	var resize = function()
	{
		var all_h = $(window).height();
		var head_h = $(".header").height();
		var left_height = $(".table_body").height();
		var all_w = $(window).width();
		var head_w = $(".header").width();
		var left_w = $(".left_menu").width();
		$("iframe[name='content-body']").attr("height","100%").attr("width","99%");	
	}
var refreshTime = function() 
{
	$.post("/Public/refreshTime.html",{},function(F){
	},'josn')
}
</script>