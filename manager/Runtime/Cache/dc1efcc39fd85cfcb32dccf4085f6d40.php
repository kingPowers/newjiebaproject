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
<form class="form-inline definewidth m20" action="/Fundpart/index" method="get">
    资金方名称：
    <input type="text" name="v" id="v" class="abc input-default" placeholder="" value="<?php echo ($_GET['v']); ?>">&nbsp;&nbsp;
    状态：<select class="short" name="status">
        <option value="0">全部</option>
        <?php if(is_array($status)): foreach($status as $k=>$vo): ?><option value="<?php echo ($k); ?>" <?php if(($k) == $_GET['status']): ?>selected=""<?php endif; ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
    </select>&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
    <a id="download_data" class="btn btn-primary" onclick="export_data(this);">导出已分发订单数据</a>
</form>
<a class="btn btn-primary" href="/Fundpart/add" style="margin-left: 40px;">添加资金方</a>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>资金方编号</th>
        <th>资金方名称</th>    
        <th>法人姓名</th>
        <th>资金方类型</th>
        <th>启用状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
            <td><?php echo ($vo['capital_number']); ?></td>
            <td><?php echo ($vo['companyname']); ?></td>
            <td><?php echo ($vo['legal_name']); ?></td>
            <td><?php echo ($vo['typename']); ?></td>
            <td>
                <?php if(($vo['status']) == "1"): ?>已启用
                <?php else: ?>
                已停用<?php endif; ?>
            </td>
            <td><?php echo ($vo['timeadd']); ?></td>
            <td><a href="/Fundpart/edit/id/<?php echo ($vo['id']); ?>">查看</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="/Fundpart/edit/id/<?php echo ($vo['id']); ?>/is_sub/1">编辑</a></td>
        </tr><?php endforeach; endif; ?>
    </tbody>
</table>
<div><?php echo ($page); ?></div>
<script type="text/javascript">
function relevance(channelid)
{
    if(!channelid)return top.jdbox.alert(1,"渠道id错误");
    var url = "/Channel/relevance/id/";
    return top.jdbox.iframe(url+channelid);
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