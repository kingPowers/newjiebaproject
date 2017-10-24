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
<div class="w98 bgf">
	<div class="tit_box">
		<div class="t_box">			
			<span>选择合作公司</span>
		</div>
		<ul class="info_list">
			<li>合作公司:<?php echo ($info['bus_companyname']); ?></li>
			<li>所属渠道:<?php echo ($info['cha_companyname']); ?></li>
		</ul>		
	</div>
	<div class="tit_box">
		<div class="t_box">			
			<span>关联资金方</span>
		</div>
		<ul class="info_list">
			<li>资金方:<?php echo ($info['cap_companyname']); ?></li>
			<li>资金方产品:<?php echo ($info['cap_l_name']); ?><a onclick="javascript:return top.jdbox.iframe('/Loan/detail/id/<?php echo ($info["cap_l_id"]); ?>');">产品明细</a></li>
		</ul>		
	</div>
	<div class="tit_box">
		<div class="t_box">			
			<span>产品配置</span>
		</div>
		<ul class="info_list">
			<li>产品名称:<?php echo ($info['name']); ?></li>
			<li>借款金额:<?php echo ($info['min_loanmoney']); ?>元-<?php echo ($info['max_loanmoney']); ?>元</li>
			<li>借款期限:<?php echo ($periode[$info['loan_periode_id']]['periode']); echo ($periode[$info['loan_periode_id']]['unit']); ?></li>
			<li>借款利率:<?php echo ($info['periode_rate']); ?>%(日利率)</li>
			<li>借款逾期利率:<?php echo ($info['late_periode_rate']); ?>%(日利率)</li>
			<li>手续费:<?php echo ($info['procedure_free']); ?>元</li>
			<li>平台管理费率:<?php echo ($info['plat_free_rate']); ?>%</li>
			<li>是否自动审核:<?php if(($info['is_auto_pass']) == "1"): ?>是<?php else: ?>否<?php endif; ?></li>
		</ul>		
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
		$("iframe[name='content-body']").attr("height",all_h-head_h).attr("width",all_w-left_w);	
	}
var refreshTime = function() 
{
	$.post("/Public/refreshTime.html",{},function(F){
	},'josn')
}
</script>