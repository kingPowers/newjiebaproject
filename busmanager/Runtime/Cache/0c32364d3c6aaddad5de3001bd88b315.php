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
<script type="text/javascript">
$(function(){
	$(".sub").click(function(){
		var data = {};
		data.result = $("select[name='result']").val();
		data.remark = $(".remarks").val();
		data.order_id = $("input[name='order_id']").val();
		data.allot_id = $("input[name='loan_allot_id']").val();
		data.is_urgent = 1;//console.log(data);
		top.jdbox.alert(2,"正在提交。。。");
		$.post("{:U('/Overdue/operate')}",data,function(F){
			console.log(F);
			top.jdbox.alert(F.status,F.info);
			if (F.status) {
				window.location.href = "/Overdue/index";
			}
		},'json')
	})
})
</script>
<div class="w98 bgf">
	<div class="tit_box">
		<div class="t_box">			
			<span>基本信息</span>
		</div>
		<div class="tablediv" style="margin-top: 0;">
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
					<th>到期时间</th>									
				</tr>
				<tr>
					<td><?php echo ($order_info['tender_number']); ?></td>
					<td><?php echo ($order_info['memberid']); ?></td>
					<td><?php echo ($order_info['bus_l_name']); ?></td>
					<td><?php echo ($order_info['timeadd']); ?></td>
					<td><?php echo ($order_info['periode_rate']); ?>%</td>
					<td><?php echo ($order_info['late_periode_rate']); ?>%</td>
					<td><?php echo ($order_info['money']); ?></td>
					<td><?php echo ($order_info['total_fee']); ?></td>
					<td><?php echo ($order_info['pay_money']); ?></td>
					<td><?php echo ($order_info['names']); ?></td>
					<td><?php echo ($order_info['mobile']); ?></td>
					<td><?php echo ($order_info['end_time']); ?></td>
				</tr>
			</table>
		</div>			
	</div>
	<div class="tit_box">
		<div class="t_box">			
			<span>预售信额度</span>
		</div>
		<div class="tablediv" style="margin-top: 0;">
			<table class="member_table">
				<tr>
					<th>逾期本金</th>
					<th>逾期天数</th>
					<th>逾期费用</th>													
				</tr>
				<tr>
					<td><?php echo ($order_info['money']); ?></td>
					<td><?php echo ($order_info['late_days']); ?></td>
					<td><?php echo ($order_info['late_fee']); ?></td>					
				</tr>
			</table>
		</div>	
	</div>
	<div class="tit_box">
		<div class="t_box">			
			<span>催收历史</span>
		</div>
		<div class="tablediv" style="margin-top: 0;">
			<table class="member_table">
				<tr>
					<th>催收时间</th>
					<th>催收人</th>
					<th>催收结果</th>
					<th>催收备注</th>													
				</tr>
				<?php if(is_array($urgent_his)): foreach($urgent_his as $key=>$uh): ?><tr>
					<td><?php echo ($uh['timeadd']); ?></td>
					<td><?php echo ($uh['author']); ?></td>
					<td><?php echo ($status[$uh['urge_type']]); ?></td>
					<td><?php echo ($uh['content']); ?></td>					
				</tr><?php endforeach; endif; ?>
			</table>
		</div>	
	</div>
	<div class="tit_box">
		<?php if(!empty($_GET['is_sub'])): ?><div class="t_box">			
			<span>催收处理</span>
		</div>
		<div class="selectdiv">
			<font>催收结果:</font>
				<span class="input_text box_round w190">
				<select name="result">
				    <?php if(is_array($status)): foreach($status as $key=>$s): ?><option value="<?php echo ($key); ?>" <?php if($_GET['is_sub'] == 0): if(($key) == $order_info['urgeStatus']): ?>selected=""<?php endif; endif; ?>><?php echo ($s); ?></option><?php endforeach; endif; ?>
				</select>
			</span>		
		</div>
		<div class="selectdiv">
			<font>催收备注:</font>
			<textarea class="remarks box_round input_text" style="resize: none;">
							
			</textarea>	
		</div>
		<input type="hidden" name="order_id" value="<?php echo ($_GET['id']); ?>">
		<input type="hidden" name="loan_allot_id" value="<?php echo ($order_info['loan_allot_id']); ?>">
		
			<input type="submit" name="" value="提交" class="btn_submit box_round head_blue sub">
		<?php else: ?>
			<a href="/Overdue/index" class="btn_submit box_round head_blue">返回</a><?php endif; ?>
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