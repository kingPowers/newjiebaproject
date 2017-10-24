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
			<span>贷款明细</span>
		</div>
	</div>
	<input type="hidden" name="order_id" value="<?php echo ($info['id']); ?>">
	<div class="tablediv" style="margin: 0 0 10px;">
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
	<div class="tit_box">
		<div class="t_box">			
			<span>贷款审核</span>
		</div>
		<div class="selectdiv">			
			<div class="pass">
				<font>审核结果:</font>
				<div class="pass_li"><input type="radio" name="pass" class="rad_pass" value="1"><h3>通过</h3></div>
				<div class="pass_li"><input type="radio" name="pass" class="rad_pass" value="0"><h3>拒单</h3></div>
			</div>
		</div>
		<div class="selectdiv">
			<font>审核意见:</font>
			<textarea class="remarks box_round input_text" style="resize: none;">				
			</textarea>	
		</div>
		<input type="submit" name="" value="提交" class="btn_submit box_round head_blue">
	</div>
	<!-- <div class="tit_box">
		<div class="t_box">			
			<span>征信查询<font> (可勾选以下服务查询申请人征信信息)</font></span>
			<input type="button" name="" value="查询" class="btn_css box_round head_blue" style="float: right;">	
		</div>
	</div>
	<div class="creditdiv">
		<ul>
			<li>
				<div class="cr_con">
					<input type="checkbox" name="" class="creditbox">
					<div class="cr_txt">
						
					</div>
				</div>				
			</li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
		</ul>
	</div> -->
</div>
<script type="text/javascript">
$(function(){
	$(".btn_submit").click(function(){
		var data = {};
		data.order_id = $("input[name='order_id']").val();
		data.check_result = $("input[name='pass']:checked").val();
		data.remark = $(".remarks").val();
		data.is_check = 1;
		//console.log(data);
		top.jdbox.alert(2);
		$.post("/Order/checkOrder",data,function(F){
			top.jdbox.alert(F.status,F.info);
			if (F.status) {
				window.location.href = "/Order/index";
			}
		},'json')
	})
})
</script>
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