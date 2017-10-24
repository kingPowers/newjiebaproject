<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
<div class="w100">
	<ul class="index_list">
		<li>
			<div class="in_bg in_bg01">
				<div class="w480 font_f">
					<h3>今日新增用户数量</h3>
					<h1><?php echo ($info['todayMemberCount']); ?></h1>
					<p>当前用户数量：<?php echo ($info['totalMemberCount']); ?></p>
				</div>
			</div>
		</li>
		<li style="margin-left: 22px;">
			<div class="in_bg in_bg02">
				<div class="w480 font_f">
					<h3>今日新增贷款笔数</h3>
					<h1><?php echo ($info['todayOrderCount']); ?></h1>
					<p>当前贷款笔数：<?php echo ($info['totalOrderCount']); ?></p>
				</div>
			</div>
		</li>
		<li style="margin-left: 22px;">
			<div class="in_bg in_bg03">
				<div class="w480 font_f">
					<h3>今日增增贷款金额</h3>
					<h1><?php echo ($info['todayOrderMoney']); ?></h1>
					<p>当前贷款金额：<?php echo ($info['totalOrderMoney']); ?></p>
				</div>
			</div>
		</li>		
	</ul>	
</div>
<div class="w_index bgf box_round5">
	<div class="tit_box">
		<div class="t_box">			
			<span>我的待办</span>
		</div>
	</div>
	<div class="tablediv" style="margin:0;">
		<table class="member_table">
			<tr>
				<th>待办标题</th>
				<th>待办内容</th>
				<th>创建时间</th>
				<th>操作</th>				
			</tr>
		</table>
	</div>
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