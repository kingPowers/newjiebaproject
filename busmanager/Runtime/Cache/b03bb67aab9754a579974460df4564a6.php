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

<script src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>  
<link rel="stylesheet" href="_STATIC_/manager/css/index.css">
<link rel="stylesheet" href="_STATIC_/2015/css/public.css">
<style>
	#content{width:1000px; padding:10px 0; margin: 0 auto;}
	body,ul{margin: 0; padding: 0;}
	ul{list-style: none;}
	input{width: 20px; height: 20px; display: inline-block;}
	.selectdiv ul li{width: 49%; float: left; margin-bottom: 30px;}
	.btn_save{width: 240px; height: 38px; line-height: 38px; text-align: center; background: #40a7ff; color: #fff; font-size: 16px; border-radius: 10px; display: block; margin:40px auto 10px;}
	.option_text{ height: 38px; line-height: 38px; background: none;  display: inline-block;}
	.option_text:hover {box-shadow: 0px 0px 5px 0px #40a7ff; border: 1px #40a7ff solid;}
	.option_text select{border: 0;width: 180px; font-size: 16px; cursor: pointer; text-align: center; margin: 0 auto; height: 30px; display: block; background: transparent;}
	.useropen{border-left: 4px solid #40a7ff; height: 33px; line-height: 33px;}
	.useropen span{padding: 0 10px; display: inline-block;}
	.opendiv{height: auto; overflow: hidden; padding: 18px 0 3px; font-size: 16px; padding-bottom: 220px;}
	.opendiv p{font-size: 16px; display: inline-block; width: 49%; float: left;}
	.opendiv input{display: inline-block; position: absolute;}
	.opendiv span{position: relative; width: 80px; display: inline-block;}
</style>
<div><span>新增用户信息</span></div>
<div id="content">
	<div class="selectdiv">
		<ul>
			<li>
			    <input type="hidden" name="userid" value="<?php echo ($user['id']); ?>">
				<font>登录帐号:</font>
				<input type="text" name="username" value="<?php echo ($user['username']); ?>" class="input_text box_round w238">	
			</li>
			<li>
				<font>用户姓名:</font>
				<input type="text" name="names" value="<?php echo ($user['names']); ?>" class="input_text box_round w238">	
			</li>
			<li>
				<font>手机号码:</font>
				<input type="text" name="mobile" value="<?php echo ($user['mobile']); ?>" class="input_text box_round w238">
			</li>
			<li>
				<font>授权角色:</font>
				<span class="option_text box_round w238">
					<select name="typeid">
						<option value="0">请选择角色类型</option>
						<?php if(is_array($type_list)): foreach($type_list as $key=>$tl): ?><option value="<?php echo ($tl['id']); ?>" <?php if(($tl['id']) == $user['typeid']): ?>selected=""<?php endif; ?>><?php echo ($tl['title']); ?></option><?php endforeach; endif; ?>
					</select>
				</span>	
			</li>
		</ul>
	</div>
	<div class="useropen"><span>用户启用</span></div>
	<div class="opendiv">
		<p>
			是否启用:
			<?php if(is_array($user_status)): foreach($user_status as $key=>$us): ?><span><input type="radio" name="status" value="<?php echo ($key); ?>" <?php if(($key) == $user['status']): ?>checked=""<?php endif; ?>><font style="margin-left: 30px"><?php echo ($us); ?></font></span><?php endforeach; endif; ?>
		</p>
		<!-- <p>
			<font style="position: absolute;">启用日期:</font>
			<input onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="option_text box_round w238" style="position: absolute; margin-left: 75px;"/>
		</p> -->
	</div>	
	<a class="btn_save">保存</a>
</div>
<script type="text/javascript">
$(".btn_save").click(function(){
	var data = {};
	data.username = $("input[name='username']").val();
	data.names = $("input[name='names']").val();
	data.mobile = $("input[name='mobile']").val();
	//data.typeid = $("selected[name='typeid']").find("option:selected").attr("value");
	data.typeid = $("select[name='typeid']").val();
	data.status = $("input[name='status']:checked").val();
	data.userid = $("input[name='userid']").val();
	data.is_save = 1;
	$.post("/Auth/userEdit.html",data,function(F){
		//console.log(F);
		if (F.status == 1) {
			return top.jdbox.alert(F.status,F.info);
		} else {
			return top.jdbox.alert(F.status,F.info,'',1);
		}
	},'json')

})
</script>