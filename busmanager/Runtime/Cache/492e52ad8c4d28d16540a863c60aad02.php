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
	<div class="tit_box">
		<div class="t_box">			
			<span>基本信息</span>
		</div>
		<ul class="info_list">
			<li>姓名:<?php echo ($info['names']); ?></li>
			<li>身份证号:<?php echo ($info['certiNumber']); ?></li>
			<li>手机号:<?php echo ($info['mobile']); ?></li>
			<li>银行卡号:<?php echo ($info['acc_no']); ?></li>
		</ul>				
	</div>
	<div class="tit_box">
		<div class="t_box">			
			<span>预售信额度</span>
		</div>
		<ul class="info_list">
			<li>预售信额度:<?php echo ($info['promise_money']); ?></li>	
		</ul>		
	</div>
	<div class="tit_box">
		<div class="t_box">			
			<span>贷款信息</span>
		</div>
		<div class="tablediv" style="margin-top: 0;">
			<table class="member_table">
				<tr>
					<th>编号</th>
					<th>贷款金额</th>
					<th>贷款利息</th>
					<th>贷款时间</th>
					<th>已还本金</th>	
					<th>已还利息</th>
					<th>逾期次数</th>
					<th>逾期金额</th>								
				</tr>
				<?php if(is_array($loan_info)): foreach($loan_info as $key=>$vo): ?><tr>
					<td><?php echo ($vo['tender_number']); ?></td>
					<td><?php echo ($vo['money']); ?></td>
					<td><?php echo ($vo['total_fee']); ?></td>
					<td><?php echo ($vo['timeadd']); ?></td>
					<td><?php echo ($vo['money']); ?></td>
					<td>1000</td>
					<td><?php echo ($vo['lateCount']); ?>次</td>
					<td><?php echo ($vo['late_fee']); ?></td>			
				</tr><?php endforeach; endif; ?>	
			</table>
			<?php echo ($page); ?>
		</div>
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