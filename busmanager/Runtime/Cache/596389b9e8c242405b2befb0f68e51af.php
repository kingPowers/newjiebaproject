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
	<form id="search-form" method="get" action="/Overdue/index">
	<div class="selectdiv">
		<font>姓名:</font>
		<input type="text" name="names" value="<?php echo ($_GET['names']); ?>" class="input_text box_round w170">	
		<font>手机号:</font>
		<input type="text" name="mobile" value="<?php echo ($_GET['mobile']); ?>" class="input_text box_round w170">		
		<font>状态:</font>
		<span class="input_text box_round w190">
		<select name="status">
			<?php if(is_array($operate_status)): foreach($operate_status as $key=>$os): ?><option value="<?php echo ($key); ?>" <?php if(($key) == $_GET['status']): ?>selected=""<?php endif; ?>><?php echo ($os); ?></option><?php endforeach; endif; ?>
		</select>
		</span>
		<input type="submit" name="sub" value="查询" class="btn_css box_round head_blue">		
	</div>	
	</form>
	<div class="tablediv">
		<table class="member_table">
			<tr>
				<th>编号</th>
				<th>贷款编号</th>
				<th>贷款金额</th>
				<th>到期时间</th>
				<th>逾期金额</th>	
				<th>逾期天数</th>
				<th>姓名</th>
				<th>手机号</th>
				<th>处理结果</th>
				<th>操作</th>				
			</tr> 
			<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
				<td><?php echo (int)($key+1); ?></td>
				<td><?php echo ($vo['tender_number']); ?></td>
				<td><?php echo ($vo['money']); ?></td>
				<td><?php echo ($vo['end_time']); ?></td>
				<td><?php echo ($vo['late_fee']); ?></td>
				<td><?php echo ($vo['late_days']); ?></td>
				<td><?php echo ($vo['names']); ?></td>
				<td><?php echo ($vo['mobile']); ?></td>
				<td><?php echo ($operate_status[$vo['urgeStatus']]); ?></td>		
				<td>
					<?php if($vo['urgeStatus'] == 1): ?><a href="/Overdue/operate/id/<?php echo ($vo['id']); ?>/is_sub/1">处理</a>
					<?php elseif($vo['urgeStatus'] == 2): ?>
						<a href="/Overdue/operate/id/<?php echo ($vo['id']); ?>/is_sub/0">查看</a>
					<?php elseif($vo['urgeStatus'] == 3): ?>
						<a href="/Overdue/operate/id/<?php echo ($vo['id']); ?>/is_sub/1">处理</a>&nbsp;&nbsp;
						<a href="/Overdue/operate/id/<?php echo ($vo['id']); ?>/is_sub/0">查看</a><?php endif; ?>
				</td>						
			</tr><?php endforeach; endif; ?>
		</table>
		<?php echo ($page); ?>
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