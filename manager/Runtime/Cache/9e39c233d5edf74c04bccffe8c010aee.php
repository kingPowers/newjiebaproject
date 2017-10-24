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
var auto_load = "<?php echo ($_GET['auto_load']); ?>";
var businessid = "<?php echo ($_GET['id']); ?>";
if (businessid && auto_load) {
    window.location.href = "/BusinessCompany/editRule/id/" + businessid;
}
</script>
<form class="form-inline definewidth m20" action="<?php echo U('/BusinessCompany/profitRuleList');?>" method="get">
	<span>合作公司名称：</span>
    <input type="text" name="businessname"   value="<?php echo ($_GET['businessname']); ?>"/>&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>&nbsp;&nbsp;&nbsp; 
</form>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
        <tr>
            <th>合作公司编号</th>
            <th>所属渠道</th>
            <th>合作公司名称</th>
            <th>创建人</th>
            <th>规则创建时间</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): foreach($list as $k=>$vo): ?><tr>
            <td><?php echo ($vo['business_number']); ?></td>
            <td><?php echo ($vo['channel_companyname']); ?></td>
            <td><?php echo ($vo['companyname']); ?></td>
            <td><?php echo ($vo['author']); ?></td>
            <td><?php echo ($vo['timeadd']); ?></td> 
            <td>
				<a href="<?php echo U('editRule',array('id'=>$vo['id'],'is_sub'=>'0'));?>"> 查看 </a>
                <a href="<?php echo U('editRule',array('id'=>$vo['id'],'is_sub'=>'1'));?>"> 编辑 </a>
				<a href="<?php echo U('editRule',array('id'=>$vo['id'],'is_sub'=>'1'));?>"> 新增 </a>
            </td>
        </tr><?php endforeach; endif; ?>
     </tbody>
</table>
<?php echo ($page); ?>
<script type="text/javascript">
$(function(){
    $('#add').click(function(){
        $(this).attr('href',"/Member/editStaff.html");
    })
    $('a.delete').live('click',function(){
    	$id = $(this).attr('name');
        var data={};
        $(data).attr('mid',$id);
        $.post('/Member/deleteStaff.html',data,function(F){
            top.jdbox.alert(F.status,F.info);
              if(F.status){
                window.location.reload();
              }
            },'json');

    })
    $('#statoexcel').click(function(){
        $(this).attr('href',"/Member/export_staffInfo.html?k="+$('#k').val()+"&v="+$('#v').val());
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