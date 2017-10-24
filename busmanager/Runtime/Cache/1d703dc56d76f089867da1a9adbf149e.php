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
})
</script>
<div class="w98 bgf">
    <form id="search-form" method="get" action="/Order/index">
    <input type="hidden" name="orderStatus" value="<?php echo ($_GET['orderStatus']); ?>">
	<div class="selectdiv">
		<font>姓名:</font>
		<input type="text" name="names" style="width: 100px;" value="<?php echo ($_GET['names']); ?>" class="input_text box_round w170">	
		<font>手机号:</font>
		<input type="text" name="mobile" style="width: 150px;" value="<?php echo ($_GET['mobile']); ?>" class="input_text box_round w170">			
		<font>时间:</font>
		<input type="text" name="starttime" value="<?php echo ($_GET['starttime']); ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="input_text box_round w170">
		<input type="text" name="endtime" value="<?php echo ($_GET['endtime']); ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="input_text box_round w170">
		<font>状态:</font>
		<span class="input_text box_round w190" style="width: 100px;">
		<select name="status" style="width: 100px;">
			<?php if(is_array($status)): foreach($status as $key=>$os): ?><option value="<?php echo ($key); ?>" <?php if(($key) == $_GET['status']): ?>selected=""<?php endif; ?>><?php echo ($os); ?></option><?php endforeach; endif; ?> 
		</select>
		</span>
		<input type="submit" name="sub" value="查询" class="btn_css box_round head_blue">		
	</div>
	</form>
	<div class="nav">
		<ul>
			<?php if(is_array($status)): foreach($status as $key=>$st): ?><li class="alink all_st" value="<?php echo ($key); ?>" <?php if(($key) == $_GET['status']): ?>id="bg_blue"<?php endif; ?>><a><?php echo ($st); ?>贷款</a></li><?php endforeach; endif; ?>
			<!--<li class="alink order_st" value="4" <?php if($_GET['orderStatus'] == 3): ?>id="bg_blue"<?php endif; ?>><a>已结清贷款</a></li>-->
		</ul>
	</div>
	<div class="tablediv" style="margin-top: 5px;">
		<table class="member_table">
			<tr>
				<th>贷款编号</th>
				<th>用户ID</th>
				<th>产品</th>
				<th>姓名</th>
				<th>贷款金额</th>
				<th>费用</th>
				<th>到账金额</th>
				<th>贷款时间</th>
				<th>贷款期限</th>
				<th>还款时间</th>
				<th>贷款状态</th>
				<th>操作</th>
			</tr>
			<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
				<td><?php echo ($vo['tender_number']); ?></td>
				<td><?php echo ($vo['memberid']); ?></td>
				<td><?php echo ($vo['bus_l_name']); ?></td>
				<td><?php echo ($vo['names']); ?></td>
				<td><?php echo ($vo['money']); ?></td>
				<td><?php echo ($vo['total_fee']); ?></td>
				<td><?php echo ($vo['pay_money']); ?></td>
				<td><?php echo ($vo['timeadd']); ?></td>
				<td><?php echo ($vo['lp_name']); ?></td>
				<td><?php echo ($vo['back_real_time']); ?></td>
				<td><?php echo ($vo['order_status_name']); ?></td>
				<td>
					<?php if($vo['order_status_name'] == '待审核'): ?><a href="/Order/checkOrder/id/<?php echo ($vo['id']); ?>">审核</a>
					<?php elseif($vo['order_status_name'] == '待签约'): ?>
						<a onclick="refuseOrder(<?php echo ($vo['id']); ?>);">拒单</a>
					<?php elseif($vo['order_status_name'] == '待打款'): ?>
						<a onclick="refuseOrder(<?php echo ($vo['id']); ?>);">拒单</a>&nbsp;&nbsp;
					    <a onclick="payMoney(<?php echo ($vo['id']); ?>);">打款</a>
					<?php else: ?>
						<a href="/Order/orderInfo/id/<?php echo ($vo['id']); ?>/mid/<?php echo ($vo['memberid']); ?>">查看</a><?php endif; ?>
				</td>				
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
<script type="text/javascript">
	$(function () {            
     	$(".alink").click(function(){  
	        $(this).attr("id","bg_blue");  
	        $(this).siblings().removeAttr("id"); 	      
	  	});  
    });
var refuseOrder = function(id)
{
	if (!id)return top.jdbox.alert(0,"订单ＩＤ错误");
	var data = {};data.order_id = id;
	conAlert("是否确定拒单",refuse,data)
	
} 
var refuse = function(data)
{
	$.DialogByZ.Close();
	top.jdbox.alert(2);
	$.post("/Order/refuseOrder",data,function(F){
		//console.log(F);
		top.jdbox.alert(F.status,F.info);
		if (F.status) {
			window.location.reload();  
		}
	},'json')
}
var payMoney = function(id) 
{
	if (!id)return top.jdbox.alert(0,"订单ＩＤ错误");
	var data = {};data.order_id = id;
	conAlert("是否确定放款",pay,data)
}
var pay = function(data)
{
	$.DialogByZ.Close();
	top.jdbox.alert(2);
	$.post("/Order/payMoney",data,function(F){
		//console.log(F);
		top.jdbox.alert(F.status,F.info);
		if (F.status) {
			window.location.reload();  
		}
	},'json')
}
</script>