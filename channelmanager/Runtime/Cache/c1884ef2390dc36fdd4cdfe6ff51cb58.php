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
	<form method="get" action="/Member/index">
		<font>合作公司:</font>
		<input type="text" name="businessname" value="<?php echo ($_GET['businessname']); ?>" class="input_text box_round w238">
		<input type="submit" name="" value="查询" class="btn_css box_round head_blue">
	</form>
	</div>
	<div class="tablediv">
		<table class="member_table">
			<tr>
				<th>合作公司编号</th>
				<th>合作公司名称</th>
				<th>负责人姓名</th>
				<th>手机号</th>
				<th>状态</th>
				<th>合作公司地址</th>
				<th>操作</th>
			</tr>
			<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
				<td><?php echo ($vo['business_number']); ?></td>
				<td><?php echo ($vo['companyname']); ?></td>
				<td><?php echo ($vo['legal_name']); ?></td>
				<td><?php echo ($vo['legal_mobile']); ?></td>
				<td>
					<?php if($vo['starus'] == 1): ?>启用
					<?php else: ?>
					禁用<?php endif; ?>
				</td>
				<td><?php echo ($vo['company_address']); ?></td>
				<td><a>查看明细</a></td>				
			</tr><?php endforeach; endif; ?>
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
</script>