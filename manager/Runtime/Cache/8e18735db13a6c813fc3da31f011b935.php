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
</head>
<body>
<form class="form-inline definewidth m20" action="/Member/releaseMember/" method="get">
    <select name="k" class="short" id="k">
        <?php if(is_array($keys)): foreach($keys as $key=>$v): ?><option value='<?php echo ($key); ?>' <?php if($_GET['k']!='' && $_GET['k'] == $key): echo 'selected="selected"'; endif;?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
    </select>&nbsp;
    <input type="text" name="v" id="v" class="abc input-default" placeholder="" value="<?php echo ($_GET['v']); ?>">&nbsp;&nbsp;
    <span>开始时间：</span>
    <input type="text" name="starttime" id="starttime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($_GET['starttime']); ?>">&nbsp;&nbsp;
    <span>结束时间：</span>
    <input type="text" name="endtime" id="endtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($_GET['endtime']); ?>">&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
    <a id="download_data" class="btn btn-primary" onclick="export_data(this);">导出发单用户数据</a>
</form>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>用户ＩＤ</th>
        <th>用户名</th>
        <th>手机号码</th>
        <th>订单成单次数</th>
        <th>状态</th>
        <th>注册时间</th>
        <th>上次登录时间</th>
        <th>操作</th>
        <!--<th>详细信息</th>-->
    </tr>
    </thead>
    <tbody>
    <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
            <td><?php echo ($vo['id']); ?></td>
            <td><?php echo ($vo['username']); ?></td>
            <td><?php echo ($vo['mobile']); ?> （<?php echo ($vo['mobile_location']); ?>）</td>
            <td><?php echo ($vo['coNum']); ?></td>
            <td>
              <?php if($vo['status'] == 1): ?>正常
              <?php else: ?>
                禁止发单<?php endif; ?>
            </td>
            <td><?php echo ($vo['timeadd']); ?></td>
			<td><?php echo ($vo['lasttime']); ?></td>
            <td>
               <?php if($vo['status'] == 1): ?><a href="javascript:void(0)" onclick="ban_release(<?php echo ($vo['id']); ?>)">禁止发单</a>
               <?php else: ?>
               <a href="javascript:void(0)" onclick="allow_release(<?php echo ($vo['id']); ?>)">允许发单</a><?php endif; ?>
            </td>
        </tr><?php endforeach; endif; ?>
    </tbody>
</table>
<div><?php echo ($page); ?></div>
<script type="text/javascript">
function export_data (_this)
{
	 $(_this).attr('href',"/Member/export_release_list.html?k="+$('#k').val()+"&v="+$('#v').val()+"&starttime="+$('#starttime').val()+"&endtime="+$('#endtime').val());
}
function ban_release($id)
{
	if(false == confirm("是否禁止该用户发单")) return false;
	if(!$id)
	{
		return top.jdbox.alert(0,'用户ID错误');
	}
	$.post("<?php echo U('/Member/ban_release');?>",{"memberid":$id,"is_ajax":1},function(F){
        console.log(F);
        top.jdbox.alert(F.status,F.info);
        if(F.status == 1)
        {
        	window.location.reload();
        }
	},'json')
}
function allow_release($id)
{
	if(false == confirm("是否允许该用户发单")) return false;
	if(!$id)
	{
		return top.jdbox.alert(0,'用户ID错误');
	}
	$.post("<?php echo U('/Member/allow_release');?>",{"memberid":$id,"is_ajax":1},function(F){
        console.log(F);
        top.jdbox.alert(F.status,F.info);
        if(F.status == 1)
        {
        	window.location.reload();
        }
	},'json')
}
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