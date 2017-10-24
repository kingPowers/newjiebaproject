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
	.search-div{border:1px solid #bbb;width: 207px;margin-top: 0px;position: absolute;top:32px;left: 8px;z-index: 100;background: #fff;border-radius: 2px;display:none;}
	.search-div p{cursor: pointer;margin: 0 auto;padding: 5px 10px;}
	.search-div p:hover{background: #ccc}
	.close{display: inline-block;float: right;font-size: 5px;color: #666;padding-right: 10px;}
</style>
<script type="text/javascript">
$(function(){
	$(".search").keyup(function(F){
	     $keywords = $(this).val();
	     if($keywords == '')return false;
	     $(this).attr('choseid','');
	     var searchNode = $(this).next(".search-div");
	     var url = '';
	     var name = $(this).attr("name");
	     if (name == "channelid"){
	     	url = "<?php echo U('/Public/getCapitalList');?>";
	     } else if (name == "businessid"){
	     	url = "<?php echo U('/Public/getBusinessList');?>";
	     }
	     $.post(url,{"name":$keywords},function(F){
	        var search = F.data;
	        var searchStr = '';
	        searchNode.html('');
	        if(search != '')searchNode.show();
	        for (var i = 0;i < search.length;i ++)
	        {
	          searchStr += "<p onclick='chose(this)' choseid='" + search[i].id + "'>" + search[i].companyname + "</p>";
	        }
	        if(searchStr != '')searchStr += "<a class='close' onclick='closes(this);'>关闭</a>";
	        searchNode.append(searchStr);
	     },'json')
  	})
  	$(".search").blur(function(){
    	$(this).val('');
    	$(this).attr("choseid",'');
  	})

})
function chose(_this)
{
  var choseid = $(_this).attr('choseid');
  var name = $(_this).html();
  $(_this).parent(".search-div").hide().prev("input").val(name).attr("choseid",choseid);
}
function closes(_this)
{
  $(_this).parent(".search-div").hide()
}
</script>
<div><span>新增平台保证金</span></div>
<div id="content">
	<table class="table table-bordered table-hover definewidth m10">
		<tr>
			<td class="tableleft">资金方：</td>
			<td style="position: relative;">
				<input type="text" class="search" name="channelid" choseid="">
				<div class="search-div"></div>
			</td>
		</tr>
		<tr>
			<td class="tableleft">平台：</td>
			<td style="position: relative;">
				<input type="text" class="search" name="businessid" choseid="">
				<div class="search-div"></div>
			</td>
		</tr>
		<tr>
			<td class="tableleft">授信额度：</td>
			<td><input type="text" name="award_limit"></td>
		</tr>
		<tr>
			<td class="tableleft">实际额度：</td>
			<td><input type="text" name="actual_limit"></td>
		</tr>
		<tr>
			<td class="tableleft">新增保证金金额：</td>
			<td><input type="text" name="deposit"></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;"><a id="sub" class="btn btn-primary">提交</a></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
$(function(){
	$("#sub").click(function(){
		var data = {};
		data.channelid = $("input[name='channelid']").attr("choseid");
		data.businessid = $("input[name='businessid']").attr("choseid");
		data.award_limit = $("input[name='award_limit']").val();
		data.actual_limit = $("input[name='actual_limit']").val();
		data.deposit = $("input[name='deposit']").val();
		top.jdbox.alert(2);
		$.post("{:U('/Fund/addBusinessDeposit')}",data,function(F){
			top.jdbox.alert(F.status,F.info);
			if(F.status)
			{
				window.location.href = "/Fund/businessFund";
			}
		})
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