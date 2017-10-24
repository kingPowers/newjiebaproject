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
	#content{width:1000px;padding:10px 0;padding-right:20px; height: auto; overflow:hidden; padding-bottom: 10px;}
	body,ul{margin: 0; padding: 0;}
	ul{list-style: none;}
	input{width: 20px; height: 20px; display: inline-block;}
	.selectdiv ul li{width: 49%; float: left; margin-bottom: 30px;}
	.btn_save{width: 240px; height: 38px; line-height: 38px; text-align: center; background: #40a7ff; color: #fff; font-size: 16px; border-radius: 10px; display: block; margin:40px auto 10px;}
	.option_text{ height: 38px; line-height: 38px; background: none;  display: inline-block;}
	.option_text:hover {box-shadow: 0px 0px 5px 0px #40a7ff; border: 1px #40a7ff solid;}
	.option_text select{border: 0;width: 180px; font-size: 16px; cursor: pointer; text-align: center; margin: 4px auto; height: 30px; display: block; background: transparent;}	
	.btn_edit{width: 110px; display: inline-block; height: 38px; line-height: 38px;}
	.btn_edit img{display: inline-block; vertical-align: middle; margin-right: 10px; cursor: pointer;}
	.sh_div{height: 270px;}
</style>
<div><span>新增工作流</span></div>
<div id="content">
	<div class="selectdiv">		
		<font>角色姓名:</font>
		<input type="text" name="" class="input_text box_round w238" placeholder="贷款审核流程">	
	</div>
	<div class="selectdiv" style="height: 100px;">
		<font>流程描述:</font>
		<textarea class="remarks box_round input_text" style="resize: none;">				
		</textarea>	
	</div>
	<div class="useropen"><span>流程配置</span></div>
	<div class="sh_div">
		<div class="selectdiv">
			<font>第一级审核:</font>
			<span class="option_text box_round w238">
				<select>
					<option>请选择角色</option>
					<option>启用</option>
				</select>
			</span>
			<span class="option_text box_round w238">
				<select>
					<option>请选择用户</option>
					<option>启用</option>
				</select>
			</span>
			<span class="btn_edit" id="btn01">
				<img src="_STATIC_/2015/box/ico_plus.png" class="plus1">
			</span>			
		</div>	
		<div class="selectdiv" style="display: none;" id="sh2">
			<font>第二级审核:</font>
			<span class="option_text box_round w238">
				<select>
					<option>请选择角色</option>
					<option>启用</option>
				</select>
			</span>
			<span class="option_text box_round w238">
				<select>
					<option>请选择用户</option>
					<option>启用</option>
				</select>
			</span>
			<span class="btn_edit" id="btn02">
				<img src="_STATIC_/2015/box/ico_plus.png" class="plus2">
				<img src="_STATIC_/2015/box/ico_miss.png" class="miss1">
			</span>			
		</div>	
		<div class="selectdiv" style="display: none; " id="sh3">
			<font>第三级审核:</font>
			<span class="option_text box_round w238">
				<select>
					<option>请选择角色</option>
					<option>启用</option>
				</select>
			</span>
			<span class="option_text box_round w238">
				<select>
					<option>请选择用户</option>
					<option>启用</option>
				</select>
			</span>
			<span class="btn_edit" id="btn03">
				<img src="_STATIC_/2015/box/ico_plus.png" class="plus3">
				<img src="_STATIC_/2015/box/ico_miss.png" class="miss2">
			</span>				
		</div>
		<div class="selectdiv" style="display: none; " id="sh4">
			<font>第四级审核:</font>
			<span class="option_text box_round w238">
				<select>
					<option>请选择角色</option>
					<option>启用</option>
				</select>
			</span>
			<span class="option_text box_round w238">
				<select>
					<option>请选择用户</option>
					<option>启用</option>
				</select>
			</span>
			<span class="btn_edit" id="btn04">				
				<img src="_STATIC_/2015/box/ico_miss.png" class="miss3">
			</span>				
		</div>
	</div>	
	<a class="btn_save">保存</a>
</div>

<script type="text/javascript">
	$(".plus1").click(function(){
		$("#sh2").show();
		$("#btn01").hide();
		$(".miss1").show();
	})
	$(".plus2").click(function(){
		$("#sh3").show();
		$("#btn02").hide();		
		$(".miss2").show();		
	})
	$(".plus3").click(function(){
		$("#sh4").show();
		$("#btn03").hide();		
	})	
	$(".miss1").click(function(){
		$("#sh2").hide();
		$("#btn01").show();
	})
	$(".miss2").click(function(){
		$("#sh3").hide();
		$("#btn02").show();
	})
	$(".miss3").click(function(){
		$("#sh4").hide();
		$("#btn03").show();
	})		
		
</script>