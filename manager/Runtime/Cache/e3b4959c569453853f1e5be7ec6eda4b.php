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

<form class="form-inline definewidth m20" action="/Member/index" method="get">
    <span>渠道：</span>
    <input type="text" class="short" name="channel_name" value="<?php echo ($_GET['channel_name']); ?>">&nbsp;&nbsp;
    <span>合作公司：</span>
    <input type="text" class="short" name="businessname" value="<?php echo ($_GET['businessname']); ?>">&nbsp;&nbsp;
    <span>姓名：</span>
    <input type="text" class="short" name="names" value="<?php echo ($_GET['names']); ?>">&nbsp;&nbsp;
    <span>手机号：</span>
    <input type="text" class="short" name="mobile" value="<?php echo ($_GET['mobile']); ?>">&nbsp;&nbsp;
    <span>状态：</span>
    <select name="status" class="short">
        <option value="0">全部</option>
        <?php if(is_array($status)): foreach($status as $key=>$v): ?><option value="<?php echo ($key); ?>" <?php if(($key) == $_GET['status']): ?>selected=""<?php endif; ?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
    </select>
    <button type="submit" class="btn btn-primary">查询</button>
</form>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>用户ID</th>
        <th>所属渠道</th>
        <th>合作公司</th>
        <th>姓名</th>
		<th>身份证号</th>
		<th>银行卡号</th>
        <th>手机号</th>
        <th>预授信额度</th>
        <th>实名状态</th>
        <th>用户状态</th>
        <th>创建时间</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <?php if(is_array($list)): foreach($list as $key=>$vo): ?><td><?php echo ($vo['id']); ?></td>
    	<td><?php echo ($vo['channel_companyname']); ?></td>
    	<td><?php echo ($vo['business_companyname']); ?></td>
    	<td><?php echo ($vo['names']); ?></td>
    	<td><?php echo ($vo['certiNumber']); ?></td>
    	<td><?php echo ($vo['acc_no']); ?></td>
    	<td><?php echo ($vo['mobile']); ?></td>
    	<td><?php echo ($vo['promise_money']); ?></td>
    	<td>
            <?php if($vo['nameStatus' == 0]): ?>未认证
             <?php elseif($vo['nameStatus'] == 1): ?>
             成功
             <?php elseif($vo['nameStatus'] == 2): ?>
             失败<?php endif; ?>
        </td>
        <td>
            <?php if($vo['status'] == 1): ?>新注册
            <?php elseif($vo['status'] == 9): ?>
            冻结<?php endif; ?>
        </td>
    	<td><?php echo ($vo['timeadd']); ?></td>      
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