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
<style type="text/css">
.tabletop{font-size: 16px;text-align: center;}
</style>
<table class="table table-bordered table-hover definewidth m10">
    <tr><td class="tableleft tabletop" colspan="11" style="text-align: center;" >贷款明细</td></tr>
    <tr>
        <th>贷款编号</th>    
        <th>用户ＩＤ</th>
        <th>涉及产品</th>
        <th>申请时间</th>
        <th>利率</th>
        <th>逾期利率</th>
        <th>贷款金额</th>
        <th>费用</th>
        <th>罚息金额</th>
        <th>已还贷款</th>
        <th>贷款状态</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo ($info['tender_number']); ?></td>
            <td><?php echo ($info['memberid']); ?></td>
            <td><?php echo ($info['bus_l_name']); ?></td>
            <td><?php echo ($info['timeadd']); ?></td>
            <td><?php echo ($info['periode_rate']); ?></td>
            <td><?php echo ($info['late_periode_rate']); ?></td>
            <td><?php echo ($info['money']); ?></td>
            <td><?php echo ($info['total_fee']); ?></td>
            <td><?php echo ($info['late_fee']); ?></td>
            <td><?php echo ($info['back_real_money']); ?></td>
            <td><?php echo ($info['order_status_name']); ?></td>
        </tr>
    </tbody>
</table>
<table class="table table-bordered table-hover definewidth m10">
    <tr><td class="tableleft tabletop" colspan="14" style="text-align: center;" >还款计划</td></tr>
    <tr>
        <th>贷款ＩＤ</th>    
        <th>期限</th>
        <th>起始时间</th>
        <th>应还时间</th>
        <th>状态</th>
        <th>还款时间</th>
        <th>期初余额</th>
        <th>期末余额</th>
        <th>应还本金</th>
        <th>应还利息</th>
        <th>应还罚息</th>
        <th>已还本金</th>
        <th>已还利息</th>
        <th>已还罚息</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo ($plan['id']); ?></td>
            <td><?php echo ($plan['lp_name']); ?></td>
            <td><?php echo ($plan['start_time']); ?></td>
            <td><?php echo ($plan['end_time']); ?></td>
            <td><?php echo ($plan['order_status_name']); ?></td>
            <td><?php echo ($plan['back_real_time']); ?></td>
            <td><?php echo (float)($plan['repayment_money']+$plan['late_fee']); ?></td>
            <td><?php echo (float)($plan['repayment_money']+$plan['late_fee']-$plan['back_real_money']); ?></td>
            <td><?php echo ($plan['money']); ?></td>
            <td><?php echo ($plan['total_fee']); ?></td>
            <td><?php echo ($plan['late_fee']); ?></td>
            <td><?php echo ($plan['back_real_money']); ?></td>
            <td>
            <?php if(!empty($plan['back_real_money'])): echo ($plan['total_fee']); endif; ?>
            </td>
            <td>
            <?php if(!empty($plan['back_real_money'])): echo ($plan['late_fee']); endif; ?>
            </td>
        </tr>
    </tbody>
</table>
<table class="table table-bordered table-hover definewidth m10">
    <tr><td class="tableleft tabletop" colspan="10" style="text-align: center;" >还款历史</td></tr>
    <tr>
        <th>贷款ID</th>    
        <th>还款单号</th>
        <th>还款金额</th>
        <th>还款时间</th>
        <th>还款类型</th>
        <th>还款状态</th>
        <th>期初余额</th>
        <th>期末余额</th>
        <th>偿还利息</th>
        <th>偿还罚息</th>
    </tr>
    </thead>
    <tbody>
    <?php if(is_array($history)): foreach($history as $key=>$vo): ?><tr>
            <td><?php echo ($vo['id']); ?></td>
            <td><?php echo ($vo['tender_number']); ?></td>
            <td><?php echo ($vo['money']); ?></td>
            <td><?php echo ($vo['timeadd']); ?></td>
            <td><?php echo ($vo['repayment_name']); ?></td>
            <td><?php echo ($vo['order_status_name']); ?></td>
             <td><?php echo (float)($vo['repayment_money']+$vo['late_fee']); ?></td>
            <td><?php echo (float)($vo['repayment_money']+$vo['late_fee']-$vo['back_real_money']); ?></td>
            <td><?php echo ($vo['total_fee']); ?></td>
            <td><?php echo ($vo['late_fee']); ?></td>         
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