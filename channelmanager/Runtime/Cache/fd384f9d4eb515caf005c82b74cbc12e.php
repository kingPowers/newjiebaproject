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
<link rel="stylesheet" href="_STATIC_/2015/index/css/index.css">
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
<form id="search-form" method="get" accept="/Deal/index" >
<input type="hidden" name="orderStatus" value="<?php echo ($_GET['orderStatus']); ?>">
<div class="w98 bgf">
	<div class="selectdiv">
		<font>姓名:</font>
		<input type="text" name="names" value="<?php echo ($_GET['names']); ?>" class="input_text box_round w190">
		<font>手机号码:</font>
		<input type="text" name="mobile" value="<?php echo ($_GET['mobile']); ?>" class="input_text box_round w190">
		<font>时间:</font>
		<input type="text" name="starttime" id="starttime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($_GET['starttime']); ?>" class="input_text box_round w170">
		<input type="text" name="endtime" id="endtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($_GET['endtime']); ?>" class="input_text box_round w170">
		<font>状态:</font>
		<span class="input_text box_round w190">
		<select name="status">
			<?php if(is_array($status)): foreach($status as $key=>$s): ?><option value="<?php echo ($key); ?>" <?php if(($key) == $_GET['status']): ?>selected=""<?php endif; ?>><?php echo ($s); ?></option><?php endforeach; endif; ?>
		</select>
		</span>
		<input type="submit" name="btn" value="查询" class="btn_css box_round head_blue">
	</div>

	<div class="nav">
		<ul>
		<?php if(is_array($status)): foreach($status as $key=>$st): ?><li class="alink all_st" value="<?php echo ($key); ?>" <?php if(($key) == $_GET['status']): ?>id="bg_blue"<?php endif; ?>><a><?php echo ($st); ?>贷款</a></li><?php endforeach; endif; ?>
		<li class="alink order_st" value="3" <?php if($_GET['orderStatus'] == 3): ?>id="bg_blue"<?php endif; ?>><a>已结清贷款</a></li>
		</ul>
	</div>
</form>
	<div class="tablediv">
		<table class="member_table">
			<tr>
				<th>贷款编号</th>
				<th>用户ＩＤ</th>
				<th>产品</th>
				<th>姓名</th>
				<th>贷款金额</th>
				<th>费用</th>
				<th>到账金额</th>
				<th>贷款时间</th>
				<th>贷款期限</th>
				<th>还款时间</th>
				<th>贷款状态</th>
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
				<td><?php echo ($vo['endtime']); ?></td>
				<td><?php echo ($status[$vo['status']]); ?></td>				
			</tr><?php endforeach; endif; ?>			
		</table>
	</div>
	<?php echo ($page); ?>
</div>

<script type="text/javascript">
	$(function () {            
     	$(".alink").click(function(){  
	        $(this).attr("id","bg_blue");  
	        $(this).siblings().removeAttr("id"); 	      
	  	});  
    }); 
</script>