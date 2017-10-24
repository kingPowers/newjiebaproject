<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>内页</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="__PUBLIC__/Css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">var OS = "_OS_", Public = "__PUBLIC__", STATIC = '_STATIC_/2015/', APP = '__APP__';</script>
<script type="text/javascript" src="_STATIC_/2015/js/wdate/WdatePicker.js"></script>
<script type="text/javascript" src="_STATIC_/2015/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/bootstrap.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/ckform.js"></script>
<link rel="stylesheet" type="text/css" href="_STATIC_/2015/box/wbox.css" />
<script type="text/javascript" src="_STATIC_/2015/js/jquery.box.js"></script>
<style type="text/css">
input[class='dis_input']{background: #fff;box-shadow: 0 0;border: 0;text-align: center;}
input[class='dis_input_short']{background: #fff;box-shadow: 0 0;border: 0;width:70px;position: relative;top:3px;text-align: center;}
select[class='dis_input']{background: #fff;box-shadow: 0 0;border: 0;padding-left: 50px;}
.table th, .table td{text-align: center;}
</style>
</head>
<body>
<style>
	#content{width:500px;padding:10px 0;padding-right:20px;font-size:12px;color:#000;}
	table.table td{color:#666;height: 100%; margin: 20%,auto;}
	table.table td.value{color:#0081A1;}
</style>
<div><span>关联渠道公司</span></div>
<div id="content">
	<div><span>关联合作公司</span></div>
	<table class="table table-bordered table-hover definewidth m10">
		<tr>
			<th>编号</th>
			<th>合作公司编号</th>
			<th>合作公司名称</th>
			<th>合作公司负责人</th>
			<th>分润规则</th>
		</tr>
		<?php if(is_array($businessList)): foreach($businessList as $key=>$vo): ?><tr>
			<td><?php echo ($vo['id']); ?></td>
			<td><?php echo ($vo['business_number']); ?></td>
			<td><?php echo ($vo['companyname']); ?></td>
			<td><?php echo ($vo['legal_name']); ?></td>
			<td><a target="_blank" href="_MANAGER_/#32/66?id=<?php echo ($vo['id']); ?>&auto_load=1">查看</a></td>
		</tr><?php endforeach; endif; ?>
	</table>
</div>
<div id="content">
	<div><span>关联渠道</span></div>
	<table class="table table-bordered table-hover definewidth m10">
		<tr>
			<th>编号</th>
			<th>渠道编号</th>
			<th>渠道名称</th>
			<th>渠道级别</th>
			<th>渠道负责人</th>
		</tr>
		<?php if(is_array($channelList)): foreach($channelList as $key=>$v): ?><tr>
			<td><?php echo ($v['id']); ?></td>
			<td><?php echo ($v['channel_number']); ?></td>
			<td><?php echo ($v['companyname']); ?></td>
			<td><?php echo ($v['channel_level']); ?></td>
			<td><?php echo ($v['legalname']); ?></td>
		</tr><?php endforeach; endif; ?>
	</table>
</div>
<script type="text/javascript">
	$(function(){
		
		 $('input#starttime').focus(function(){
	        	var endtime_val = $('#endtime').val();
	        	if(''!=endtime_val){
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:endtime_val});
	        	}else{
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	        	}
	        });
	        
	        $('input#endtime').focus(function(){
	        	var starttime_val = $('#starttime').val();
	        	if(''!=starttime_val){
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:starttime_val});
	        	}else{
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	        	}
	        });
	        
	        $('input#start_time').focus(function(){
	        	var endtime_val = $('#end_time').val();
	        	if(''!=endtime_val){
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:endtime_val});
	        	}else{
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	        	}
	        });
	        
	        $('input#end_time').focus(function(){
	        	var starttime_val = $('#start_time').val();
	        	if(''!=starttime_val){
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:starttime_val});
	        	}else{
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	        	}
	        });
	        
	});
</script>
<input type="hidden" name="reloadurl" value="__SELF__"/>
<div style="height:20px;width:100%;"></div>
</body>
</html>