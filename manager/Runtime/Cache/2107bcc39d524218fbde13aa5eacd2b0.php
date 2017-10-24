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
<link rel="stylesheet" href="_STATIC_/2015/system/css/statistics.css" type="text/css" charset="utf-8" />
<style>
.plot_div{width:480px;height:200px;float:left;margin:0 30px;}
.plot{width:480px;height:200px;float:left;}
.highlight{color:#CC3300}
.select_time{width:480px;height:100px;float:left;margin:30px 0 0 0px}
.select_time div{float:left;width:100px;height:50px;font-size:16px;color:#333333}
.username{width:100px}
.sub{width:100px;height:30px;float:left;margin:30px 0 0 30px}
.greybg{background:#E5E5E5}
td{ white-space: nowrap;}
.blue{color:#0081CD}
div.select_time ul,div.select_time li{list-style-type:none;margin:0;padding:0;}
div.select_time ul{clear:both;overflow:hidden;}
div.select_time li{float:left;height:27px;padding-right:5px;}
div.select_time input[type=text]{width:135px;font-family:Arial;padding:0;margin-top:1px;}
div.select_time input[type=button]{height:24px;margin:0;cursor:pointer;}
table{margin-top:10px;}
table,table th,table td{padding:0;}
table th,table td{height:30px;line-height:30px;}
table th div.table-title{font-size:15px;font-weight:bold;height:30px;line-height:30px;padding-left:20px;text-align:left;}
div.scroll-wp{float:right;width:1062px;height:180px;overflow-y:scroll;}
div.scroll-wp ul,div.scroll-wp li{list-style-type:none;margin:0;padding:0;overflow:hidden;}
div.scroll-wp ul{clear:both;width:100%;height:auto;border-bottom:1px solid #ccc;}
div.scroll-wp li{float:left;height:30px;line-height:30px;border-right:1px solid #ccc;}
</style>
<div style="width:1150px;height:auto;overflow:hidden;margin:10px auto;">
 <div class="table_div" style="margin-bottom:10px;">
    <div class="tr_div">
      <div class="reg_left bg"></div>
      <div class="reg_mid bg">
        <div class="reg_today"> 今日新增用户<span style="font-size:26px;color:#2299EC"> 
        	<span class="t_num" id="today_login">
          		<input type="hidden" id="cur_num" value="">
          	</span><?php echo ($data["todayMemberCount"]); ?></span>人
         </div> 
        <div class="reg_other" style="width:517px;border-left:1px solid #CCCCCC;padding-left:0">
          <div class="reg_other" style="float:left;width:1px;border-left:1px solid #FFFFFF;"></div>
          	当前用户<span class="small_int" style="font-size:16px;"><?php echo ($data["totalMemberCount"]); ?></span>人
        </div>
      </div>
      
      <div class="reg_right bg"></div>
    </div> 
    <div class="tr_div">
      <div class="reg_left bg"></div>
      <div class="reg_mid bg">
        <div class="reg_today"> 今日新增贷款<span style="font-size:26px;color:#2299EC"> 
        	<span class="t_num" id="today_login">
          		<input type="hidden" id="cur_num" value="">
          	</span><?php echo ($data["todayOrderCount"]); ?></span>笔
         </div> 
        <div class="reg_other" style="width:517px;border-left:1px solid #CCCCCC;padding-left:0">
          <div class="reg_other" style="float:left;width:1px;border-left:1px solid #FFFFFF;"></div>
          	当前总贷款<span class="small_int" style="font-size:16px;"><?php echo ($data["totalOrderCount"]); ?></span>笔
        </div>
      </div>
      
      <div class="reg_right bg"></div>
    </div> 
    <div class="tr_div">
      <div class="reg_left bg"></div>
      <div class="reg_mid bg">
        <div class="reg_today"> 今日新增贷款金额<span style="font-size:26px;color:#2299EC"> 
        	<span class="t_num" id="today_login">
          		<input type="hidden" id="cur_num" value="">
          	</span><?php echo ($data["todayOrderMoney"]); ?></span>元
         </div> 
        <div class="reg_other" style="width:517px;border-left:1px solid #CCCCCC;padding-left:0">
          <div class="reg_other" style="float:left;width:1px;border-left:1px solid #FFFFFF;"></div>
          	当前贷款总金额<span class="small_int" style="font-size:16px;"><?php echo ($data["totalOrderMoney"]); ?></span>元
        </div>
      </div>
      <div class="reg_right bg"></div>
    </div>     
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr><th colspan="4" style="text-align: center;">我的代办</th></tr>
    <tr>
        <th>代办标题</th>
        <th>代办内容</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody> 
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    </tbody>
</table>
</div>
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