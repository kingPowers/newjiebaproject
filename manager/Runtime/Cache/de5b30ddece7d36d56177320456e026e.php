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
</head>
<body>
<style>
	#content{width:500px;padding:10px 0;padding-right:20px;font-size:12px;color:#000;}
	table.table td{color:#666;height: 100%; margin: 20%,auto;}
	table.table td.value{color:#0081A1;}
</style>
<div><span>新增预存款</span></div>
<div id="content">
    <input type="hidden" name="company_id" value="<?php echo ($_REQUEST['company_id']); ?>">
	<table class="table table-bordered table-hover definewidth m10">
	    <thead>
	    	<th colspan="2" style="text-align: center;"><?php echo ($_REQUEST['companyname']); ?>新增预存款</th>
	    </thead>
		<tr>
			<td class="tableleft">预存金额</td>
			<td><input type="text" name="money" class="short" style="text-align: center;">元</td>
		</tr>
		<tr>
			<td class="tableleft"></td>
			<td><a class="btn btn-primary">提交</a></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
$(function(){
	$(".btn-primary").click(function(){
		var company_id = $("input[name='company_id']").val();
		var money = $("input[name='money']").val();
		$.post("/BusinessCompany/addDeposit",{'company_id':company_id,"money":money,"is_add":1},function(F){
			console.log(F);	
			if (F.status) {
				//window.location.href = "/BusinessCompany/businessPreDeposit";
				parent.window.reload();
				top.jdbox.alert(F.status,F.info);
			} else {
				alert(F.info);
			}
		},'json')
	})
})
</script>
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