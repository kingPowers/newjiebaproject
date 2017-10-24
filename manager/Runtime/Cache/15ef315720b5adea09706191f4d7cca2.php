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
</style>
</head>
<body>
<form class="form-inline definewidth m20" action="index.html" method="get">    
    用户名称：
    <input type="text" name="username" id="username"class="abc input-default" placeholder="" value="<?php echo ($username); ?>">&nbsp;&nbsp;
    <span>用户类型：</span>
    <select name="groupid" class="short">
        <?php if(is_array($group_list)): foreach($group_list as $key=>$vg): ?><option value="<?php echo ($vg['id']); ?>" <?php if(($vg['id']) == $_REQUEST['groupid']): ?>selected=""<?php endif; ?>><?php echo ($vg['title']); ?>用户</option><?php endforeach; endif; ?>
    </select>
    <button type="submit" class="btn btn-primary">查询</button>&nbsp;&nbsp; 
</form>
<?php if(is_array($group_list)): foreach($group_list as $key=>$g): ?><a style="margin-left: 10px;" href="/User/add/groupid/<?php echo ($g['id']); ?>" class="btn btn-success" >新增<?php echo ($g['title']); ?>用户</a><?php endforeach; endif; ?>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
        <tr>  
            <th>登录账号</th>
            <th>用户姓名</th>
			 <th>用户角色</th>
            <th>手机号码</th>
            <th>状态</th>
            <th>创建人</th>
            <th>最后登录时间</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>  
            <td><?php echo ($vo['username']); ?></td>
            <td><?php echo ($vo['names']); ?></td>
			<td><?php echo ($vo['title']); ?></td>
            <td><?php echo ($vo['mobile']); ?></td>
            <td>
            <?php if($vo['status'] == 1): ?>正常
                <?php else: ?>
                禁用<?php endif; ?></td>
            <td><?php echo ($vo['addusername']); ?></td>
            <td><?php echo ($vo['lasttime']); ?></td>
            <td><?php echo ($vo['timeadd']); ?></td>
            <td><a href="<?php echo U('User/edit',array('id'=>$vo['id']));?>">编辑</a>&nbsp;&nbsp;<a href="<?php echo U('User/record',array('id'=>$vo['id']));?>">查看记录</a></td>
        </tr><?php endforeach; endif; ?>
     </tbody>
</table>
<div><?php echo ($page); ?></div>
<script language="javascript">
$(function(){
	$('#addnew').click(function(){
		window.location.href="<?php echo U('User/add');?>";
	});
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