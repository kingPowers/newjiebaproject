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
<div class="w98 bgf">
	<form method="get" action="/Loan/index">
	<div class="selectdiv">
		<font>产品名称:</font>
		<input type="text" name="loan_name" value="<?php echo ($_GET['loan_name']); ?>" class="input_text box_round w238">		
		<font>状态:</font>
		<span class="input_text box_round w190">
		<select name="status">
			<?php if(is_array($status)): foreach($status as $key=>$vs): ?><option value="<?php echo ($key); ?>" <?php if(($key) == $_GET['status']): ?>selected=""<?php endif; ?>><?php echo ($vs); ?></option><?php endforeach; endif; ?>
		</select>
		</span>
		<input type="submit" name="sub" value="查询" class="btn_css box_round head_blue">
	</div>	
	</form>
	<div class="tablediv">
		<table class="member_table">
			<tr>
				<th>产品编号</th>
				<th>产品名称</th>
				<th>启用状态</th>
				<th>启用时间</th>
				<th>操作</th>				
			</tr>
			<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
				<td><?php echo ($vo['business_loan_number']); ?></td>
				<td><?php echo ($vo['name']); ?></td>
				<td><?php echo $status[$vo['status']] ?></td>
				<td><?php echo ($vo['passtime']); ?></td>
				<td><a href="/Loan/info/id/<?php echo ($vo['id']); ?>">查看</a></td>							
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
		$("iframe[name='content-body']").attr("height","100%").attr("width","99%");	
	}
var refreshTime = function() 
{
	$.post("/Public/refreshTime.html",{},function(F){
	},'josn')
}
</script>