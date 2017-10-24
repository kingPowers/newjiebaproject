<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script type="text/javascript">var WWW = "_WWW_", STATIC = "_STATIC_", APP = "_APP_";</script>    
    <link rel="stylesheet" href="_STATIC_/2015/css/public.css">
    <link rel="stylesheet" href="_STATIC_/2015/css/reCSS.css">
    <link rel="stylesheet" href="_STATIC_/manager/box/wbox.css">
    <script type="text/javascript" src="_STATIC_/2015/js/wdate/WdatePicker.js"></script>
    <script src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>    
    <script src="_STATIC_/2015/js/public.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/manager/js/jquery.box.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/manager/js/wbox.js" type="text/javascript" charset="utf-8"></script>   
    <link rel="stylesheet" href="_STATIC_/2015/member/css/zdialog.css" />
    <link rel="stylesheet" href="_STATIC_/2015/member/css/reset.css" />
    <script type="text/javascript" src="_STATIC_/2015/member/js/tk.js"></script>
    <style type="text/css">
    </style>
<script type="text/javascript">
var conAlert = function(content,ensure,ldata,cancle = alerts)
{
    $.DialogByZ.Confirm({Title: "提示", Content: content,FunL:ensure,Ldata:ldata,FunR:cancle})
}
function alerts(){
      $.DialogByZ.Close();
}
</script>

<link rel="stylesheet" href="_STATIC_/manager/css/index.css">
<div class="w98 bgf">
	<div class="selectdiv">
		<form id="member_form" method="get" action="/Member/index">
		<font>姓名:</font>
		<input type="text" name="names" value="<?php echo ($_GET['names']); ?>" class="input_text box_round w170">	
		<font>手机号:</font>
		<input type="text" name="mobile" value="<?php echo ($_GET['mobile']); ?>" class="input_text box_round w170">		
		<font>状态:</font>
		<span class="input_text box_round w190">
		<select name="status">
			<option value="0">全部</option>
			<?php if(is_array($status)): foreach($status as $key=>$v): ?><option value="<?php echo ($key); ?>" <?php if(($key) == $_GET['status']): ?>selected=""<?php endif; ?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
		</select>
		</span>
		<input type="submit" name="sub" value="查询" class="btn_css box_round head_blue">
		<a class="file"><input type="file" name="member_import">导入用户</a>
		</form>
	</div>	
	<div class="tablediv">
		<table class="member_table">
			<tr>
				<th>用户ID</th>
				<th>姓名</th>
				<th>身份证号</th>
				<th>银行卡号</th>
				<th>手机号</th>	
				<th>状态</th>
				<th>预授信额度</th>
				<th>创建时间</th>
				<th>状态</th>
				<th>操作</th>				
			</tr>
			<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
				<td><?php echo ($vo['id']); ?></td>
				<td><?php echo ($vo['names']); ?></td>
				<td><?php echo ($vo['certiNumber']); ?></td>
				<td><?php echo ($vo['acc_no']); ?></td>
				<td><?php echo ($vo['mobile']); ?></td>
				<td><?php echo $status[$vo['status']]; ?></td>
				<td><?php echo ($vo['promise_money']); ?></td>
				<td><?php echo ($vo['timeadd']); ?></td>
				<td>
					<?php if($vo['status'] == 1): ?>正常
					<?php elseif($vo['status'] == 9): ?>
					冻结<?php endif; ?>
				</td>	
				<td>
					<a href="/Member/detail/id/<?php echo ($vo['id']); ?>">明细</a>
					<a onclick="javascript:top.jdbox.iframe('/Member/operate/id/<?php echo ($vo["id"]); ?>/status/<?php echo ($vo["status"]); ?>')">
						<?php if($vo['status'] == 1): ?>冻结
						<?php elseif($vo['status'] == 9): ?>
						解冻<?php endif; ?>
					</a>
					<?php if($vo['member_import_id'] != 0): ?><a onclick="javascript:top.jdbox.iframe('/Member/adjustMoney/id/<?php echo ($vo["member_import_id"]); ?>)/old/<?php echo ($vo["promise_money"]); ?>')">调额</a><?php endif; ?>
				</td>						
			</tr><?php endforeach; endif; ?>		
		</table>
		<?php echo ($page); ?>
	</div>
</div>

<script type="text/javascript">
$(function(){
	$("input[name='member_import']").change(function(){
		var formData = new FormData($( "#member_form" )[0]);
                //console.log(formData);   
                $.ajax({  
                  url: '/Member/importMember.html' ,  
                  type: 'POST',  
                  data: formData,  
                  async: false,  
                  cache: false,  
                  contentType: false,  
                  processData: false,  
                  success: function (json) { 
                  console.log(json);  	
                    var json = eval("(" + json + ")"); 
                    console.log(json);
                    alert(json.info);
                  }  
                }); 
	})
})
var operate = function (id,status) {
	if (!id) return top.jdbox.alert(0,"用户ＩＤ错误");
	if (!confirm('是否确认冻结该用户')) return false;
	top.jdbox.alert(2);
	var data = {};
	data.mid = id;
	data.mstatus = status;
	data.is_free = 1;
	$.post("/Member/operate.html",data,function (F) {
		top.jdbox.alert(F.status,F.info);
		if (F.status) {
			window.location.reload();
		}
	})
}
</script>