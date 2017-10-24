<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html>
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
<div class="w100">
	<ul class="index_list">
		<li>
			<div class="in_bg in_bg01">
				<div class="w480 font_f">
					<h3>今日新增用户数量</h3>
					<h1><?php echo ($data['todayMemberCount']); ?></h1>
					<p>当前用户数量：<?php echo ($data['totalMemberCount']); ?></p>
				</div>
			</div>
		</li>
		<li style="margin-left: 1%;">
			<div class="in_bg in_bg02">
				<div class="w480 font_f">
					<h3>今日新增贷款笔数</h3>
					<h1><?php echo ($data['todayOrderCount']); ?></h1>
					<p>当前贷款笔数：<?php echo ($data['totalOrderCount']); ?></p>
				</div>
			</div>
		</li>
		<li style="margin-top: 40px;">
			<div class="in_bg in_bg03">
				<div class="w480 font_f">
					<h3>今日新增贷款金额</h3>
					<h1><?php echo ($data['todayOrderMoney']); ?></h1>
					<p>当前贷款金额：<?php echo ($data['totalOrderMoney']); ?></p>
				</div>
			</div>
		</li>
		<li style="margin:40px 0 0 1%;">
			<div class="in_bg in_bg04">
				<div class="w480 font_f">
					<h3>昨日新增分润</h3>
					<h1><?php echo ($data['todayProfitMoney']); ?></h1>
					<p>分润总额：<?php echo ($data['totalProfitMoney']); ?></p>
				</div>
			</div>
		</li>
	</ul>
</div>