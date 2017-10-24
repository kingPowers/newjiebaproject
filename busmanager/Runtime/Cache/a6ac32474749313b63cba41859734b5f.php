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
	<div class="nav">
		<ul>
			<li class="alink" id="bg_blue"><a>贷款明细</a></li>
			<li class="alink"><a href="/Order/checkResult/id/<?php echo ($_GET['id']); ?>/mid/<?php echo ($_GET['mid']); ?>">贷款审核</a></li>
			<li class="alink"><a href="/Order/repayPlan/id/<?php echo ($_GET['id']); ?>/mid/<?php echo ($_GET['mid']); ?>">还款计划</a></li>
			<li class="alink"><a href="/Order/repayHistory/id/<?php echo ($_GET['id']); ?>/mid/<?php echo ($_GET['mid']); ?>">还款历史</a></li>		
		</ul>
	</div>
	<div class="tablediv">
		<table class="member_table">
			<tr>
				<th>贷款编号</th>
				<th>用户ID</th>
				<th>产品</th>
				<th>申请时间</th>
				<th>利率</th>
				<th>逾期利率</th>				
				<th>贷款金额</th>
				<th>费用</th>
				<th>到账金额</th>
				<th>姓名</th>
				<th>手机号</th>
				<th>关联历史贷款信息</th>			
			</tr>
			<tr>
				<td><?php echo ($info['tender_number']); ?></td>
				<td><?php echo ($info['memberid']); ?></td>
				<td><?php echo ($info['bus_l_name']); ?></td>
				<td><?php echo ($info['timeadd']); ?></td>
				<td><?php echo ($info['periode_rate']); ?></td>
				<td><?php echo ($info['late_periode_rate']); ?></td>
				<td><?php echo ($info['money']); ?></td>
				<td><?php echo ($info['total_fee']); ?></td>	
				<td><?php echo ($info['pay_money']); ?></td>	
				<td><?php echo ($info['names']); ?></td>
				<td><?php echo ($info['mobile']); ?></td>	
				<td><a href="/Member/detail/id/<?php echo ($info['memberid']); ?>">查看</a></td>	
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
		$("iframe[name='content-body']").attr("height",all_h-head_h).attr("width",all_w-left_w);	
	}
var refreshTime = function() 
{
	$.post("/Public/refreshTime.html",{},function(F){
	},'josn')
}
</script>