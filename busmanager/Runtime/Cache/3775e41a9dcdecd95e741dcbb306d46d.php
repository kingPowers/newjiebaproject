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
    #wBox_overlay{width: 400px;}
	#content{width:500px; padding:10px 0; margin: 0 auto;}
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
<div><span>用户
	<?php if($_GET['status'] == 1): ?>冻结
	<?php elseif($_GET['status'] == 9): ?>
	解冻<?php endif; ?></span></div>
<div id="content">
	<div class="selectdiv">
		<ul>
			<li style="width: 300px;">
			    <input type="hidden" name="memberid" value="<?php echo ($_GET['id']); ?>">
				<font>是否<?php if($_GET['status'] == 1): ?>冻结<?php elseif($_GET['status'] == 9): ?>解冻<?php endif; ?>：</font>
				<label><input style="width:20px;margin: 10px 10px 10px 50px;position: relative;top:4px;" type="radio" name="is_operate" value="1" class="box_round w238">是</label>
				<label><input style="width:20px;margin: 10px 10px 10px 50px;position: relative;top:4px;" type="radio" name="is_operate" value="0" checked="" class="box_round w238">否</label>	
			</li>
		</ul>
	</div>
	<a class="btn_save">保存</a>
</div>
<script type="text/javascript">
$(".btn_save").click(function(){
	var result = $("input[name='is_operate']:checked").val();
	if (result == 0) {
		return top.jdbox.close();
	}
	var data = {};
	data.mid = $("input[name='memberid']").val();
	data.is_save = 1;
	$.post("/Member/operate.html",data,function(F){
		//console.log(F);
		if (F.status == 1) {
			return top.jdbox.alert(F.status,F.info);
		} else {
			alert(F.info);
		}
	},'json')

})
</script>