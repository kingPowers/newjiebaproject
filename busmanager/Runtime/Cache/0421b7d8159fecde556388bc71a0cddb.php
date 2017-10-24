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
			<ul>
			<li class="alink"><a href="/Order/orderInfo/id/<?php echo ($_GET['id']); ?>/mid/<?php echo ($_GET['mid']); ?>">贷款明细</a></li>
			<li class="alink"><a href="/Order/checkResult/id/<?php echo ($_GET['id']); ?>/mid/<?php echo ($_GET['mid']); ?>">贷款审核</a></li>
			<li class="alink" id="bg_blue"><a>还款计划</a></li>
			<li class="alink"><a href="/Order/repayHistory/id/<?php echo ($_GET['id']); ?>/mid/<?php echo ($_GET['mid']); ?>">还款历史</a></li>		
		</ul>	
		</ul>
	</div>
	<div class="tablediv">
		<table class="member_table">
			<tr>
				<th>还款ID</th>
				<th>期限</th>
				<th>起始时间</th>
				<th>应还时间</th>
				<th>状态</th>
				<th>还款时间</th>
				<th>期初余额</th>
				<th>期末余额</th>
				<th>应还本金</th>
				<th>应还利息</th>
				<th>应还罚息</th>
				<th>已还本金</th>
				<th>已还利息</th>
				<th>已还罚息</th>						
			</tr>
			<?php if(!empty($info)): ?><tr>
				<td><?php echo ($info['id']); ?></td>
				<td><?php echo ($info['lp_name']); ?></td>
				<td><?php echo ($info['start_time']); ?></td>
				<td><?php echo ($info['end_time']); ?></td>
				<td><?php echo ($info['order_status_name']); ?></td>
				<td><?php echo ($info['back_real_time']); ?></td>
				<td><?php echo (float)($info['repayment_money']+$info['late_fee']); ?></td>
				<td><?php echo (float)($info['repayment_money']+$info['late_fee']-$info['back_real_money']); ?></td>
				<td><?php echo ($info['money']); ?></td>
				<td><?php echo ($info['total_fee']); ?></td>
				<td><?php echo ($info['late_fee']); ?></td>
				<td><?php echo ($info['back_real_money']); ?></td>
				<td>
				<?php if(!empty($info['back_real_money'])): echo ($info['total_fee']); endif; ?>
				</td>
				<td>
				<?php if(!empty($info['back_real_money'])): echo ($info['late_fee']); endif; ?>
				</td>			
			</tr><?php endif; ?>
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