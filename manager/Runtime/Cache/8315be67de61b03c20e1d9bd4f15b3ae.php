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
<script type="text/javascript">
$(function(){
    $(".add-deposit").click(function(){
         return top.jdbox.iframe("/Fund/addBusinessDeposit");
    })
})
</script>
<form class="form-inline definewidth m20" action="/Fund/businessFund" method="get">
    <span>资金方名称：</span>
    <input type="text" name="channelname" value="<?php echo ($_GET['channelname']); ?>">&nbsp;&nbsp;
    <span>公司名称：</span>
    <input type="text" name="businessname" value="<?php echo ($_GET['businessname']); ?>">&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
</form>
<a class="btn btn-primary add-deposit" style="margin-left: 20px;">新增保证金信息</a>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>资金方编号</th>
        <th>资金方名称</th>
        <th>公司名称</th>
        <th>保证金比例</th>
		<th>授信额度</th>
		<th>实际额度</th>
        <th>贷款余额</th>
        <th>实际可用额度</th>
        <th>保证金余额</th>
        <th>保证金头寸</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    
    <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
    	<td><?php echo ($vo['capital_number']); ?></td>
    	<td><?php echo ($vo['cap_companyname']); ?></td>
    	<td><?php echo ($vo['bus_companyname']); ?></td>
    	<td><?php echo ($vo['deposit_percent']); ?>%</td>
    	<td><?php echo ($vo['first_credit_money']); ?></td>
    	<td><?php echo ($vo['real_quota_money']); ?></td>
    	<td><?php echo ($vo['noRepayment']); ?></td>
    	<td><?php echo ($vo['realAvailQuotaMoney']); ?></td>
    	<td><?php echo ($vo['total_promise_money']); ?></td>
    	<td><?php echo ($vo['promiseMoneyPos']); ?></td>
    	<td><a href="/Fund/businessDepositInfo/bid/<?php echo ($vo['business_company_id']); ?>/cid/<?php echo ($vo['capital_company_id']); ?>">查看</a></td>
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