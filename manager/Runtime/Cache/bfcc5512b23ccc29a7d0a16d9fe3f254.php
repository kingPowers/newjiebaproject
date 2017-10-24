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
<script type="text/javascript" src="_STATIC_/2015/js/wdate/WdatePicker.js"></script>

<form class="form-inline definewidth m20" action="/Settlement/profit" method="get">
    <span>交易日期：</span>
    <input type="text" name="starttime" id="starttime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($_GET['starttime']); ?>">&nbsp;&nbsp;
    <input type="text" name="endtime" id="endtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($_GET['endtime']); ?>">&nbsp;
    
    <span>渠道：</span>
    <select name="channel_name" class="short">
        <option>全部</option>
        <?php if(is_array($channel_list)): foreach($channel_list as $key=>$vo): ?><option value="<?php echo ($vo['companyname']); ?>" <?php if(($vo['companyname']) == $_GET['channel_name']): ?>selected=""<?php endif; ?>><?php echo ($vo['companyname']); ?></option><?php endforeach; endif; ?>.
    </select>
    <span>合作公司：</span>
    <select name="businessname" class="short">
        <option>全部</option>
        <?php if(is_array($business_list)): foreach($business_list as $key=>$v): ?><option value="<?php echo ($v['companyname']); ?>" <?php if(($v['companyname']) == $_GET['businessname']): ?>selected=""<?php endif; ?>><?php echo ($v['companyname']); ?></option><?php endforeach; endif; ?>.
    </select>
    <button type="submit" class="btn btn-primary">查询</button>
</form>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
        <tr>
            <th style="text-align: center;">当前交易总数</th>
            <th style="text-align: center;">当前交易总额</th>
            <th style="text-align: center;">平台分润总额</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo ($data['count']); ?></td>
            <td><?php echo ($data['totalMoney']); ?></td>
            <td><?php echo ($data['totalProfitMoney']); ?></td>
        </tr>
    </tbody>
</table>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>交易时间</th>
        <th>一级渠道分润</th>
        <th>二级渠道分润</th>
        <th>三级渠道分润</th>
		<th>合作公司分润</th>
		<th>贷款编号</th>
        <th>贷款金额</th>
        <th>费用</th>
        <th>到账金额</th>
        <th>订单状态</th>
    </tr>
    </thead>
    <tbody>
    <?php if(is_array($data['list'])): foreach($data['list'] as $key=>$vo): ?><tr>
            <td><?php echo ($vo['department']); ?></td>
            <td><?php echo ($vo['first_channel_profit']); ?></td>
            <td><?php echo ($vo['second_channel_profit']); ?></td>
            <td><?php echo ($vo['third_channel_profit']); ?></td>
			<td><?php echo ($vo['business_clear_fee']); ?></td>
			<td><?php echo ($vo['tender_number']); ?></td>
            <td><?php echo ($vo['money']); ?></td>
            <td><?php echo ($vo['total_fee']); ?></td>
            <td><?php echo ($vo['pay_money']); ?></td>
            <td>
            <?php if($vo['status'] == 2): ?>拒单
            <?php elseif($vo[status] == 3): ?>
            成单<?php endif; ?>
            </td>
        </tr><?php endforeach; endif; ?>
    </tbody>
</table>
<div><?php echo ($page); ?></div>
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