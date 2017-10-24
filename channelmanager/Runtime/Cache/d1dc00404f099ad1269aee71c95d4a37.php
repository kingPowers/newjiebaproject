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

<div class="w98 bgf">
	<div class="selectdiv">	
		<form method="get" action="/Profit/index">	
		<font>时间:</font>
		<input type="text" name="starttime" id="starttime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($_GET['starttime']); ?>" class="input_text box_round w170">
		<input type="text" name="endtime" id="endtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($_GET['endtime']); ?>" class="input_text box_round w170">		
		<font>公司名称:</font>
		<input type="text" name="businessname" value="<?php echo ($_GET['businessname']); ?>" class="input_text box_round w190">
		<input type="submit" name="btn" value="查询" class="btn_css box_round head_blue">
		</form>
		<div class="w100" style="margin-top: 30px;">
			<div class="blue_box head_blue">
				<p>当前交易总笔数</p>
				<h1><?php echo ($data['count']); ?></h1>
			</div>
			<div class="blue_box head_blue">
				<p>当前交易总金额</p>
				<h1><?php echo ($data['totalMoney']); ?></h1>
			</div>
			<div class="blue_box head_blue">
				<p>分润总额</p>
				<h1><?php echo ($data['totalProfitMoney']); ?></h1>
			</div>
		</div>
	</div>
	<div class="tablediv">
		<table class="member_table">
			<tr>
				<th>交易时间</th>
				<th>合作公司编号</th>
				<th>合作公司名称</th>
				<th>贷款编号</th>
				<th>贷款金额</th>
				<th>费用</th>
				<th>到账金额</th>
				<th>分润金额</th>
			</tr>
			<?php if(is_array($data['list'])): foreach($data['list'] as $key=>$vo): ?><tr>
				<td><?php echo ($vo['timeadd']); ?></td>
				<td><?php echo ($vo['business_number']); ?></td>
				<td><?php echo ($vo['business_companyname']); ?></td>	
				<td><?php echo ($vo['tender_number']); ?></td>
				<td><?php echo ($vo['money']); ?></td>
				<td><?php echo ($vo['total_fee']); ?></td>
				<td><?php echo ($vo['pay_money']); ?></td>	
				<td><?php echo ($vo['cost_money']); ?></td>				
			</tr><?php endforeach; endif; ?>			
		</table>
	</div>
</div>