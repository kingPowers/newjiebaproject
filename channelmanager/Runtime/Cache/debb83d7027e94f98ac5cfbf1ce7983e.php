<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script type="text/javascript">var WWW = "_WWW_", STATIC = "_STATIC_", APP = "_APP_";</script>    
    <link rel="stylesheet" href="_STATIC_/2015/css/public.css">
    <link rel="stylesheet" href="_STATIC_/2015/css/reCSS.css">
    <link rel="stylesheet" href="_STATIC_/manager/box/wbox.css">
    <!-- <link href="__PUBLIC__/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/assets/css/main-min.css" rel="stylesheet" type="text/css" /> --><script src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>    
    <!-- <script type="text/javascript" src="__PUBLIC__/assets/js/bui.js"></script> 
    <script type="text/javascript" src="__PUBLIC__/assets/js/common/main.js"></script> 
    <script type="text/javascript" src="__PUBLIC__/assets/js/config.js"></script >--> 
    
    <script src="_STATIC_/2015/js/public.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/manager/js/jquery.box.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/manager/js/wbox.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="_STATIC_/2015/index/css/index.css">
<div class="w100">
	<ul class="index_list">
		<li>
			<div class="in_bg in_bg01">
				<div class="w480 font_f">
					<h3>今日新增用户数量</h3>
					<h1>100</h1>
					<p>当前用户数量：10000</p>
				</div>
			</div>
		</li>
		<li style="margin-left: 1%;">
			<div class="in_bg in_bg02">
				<div class="w480 font_f">
					<h3>今日新增用户数量</h3>
					<h1>100</h1>
					<p>当前用户数量：10000</p>
				</div>
			</div>
		</li>
		<li style="margin-top: 40px;">
			<div class="in_bg in_bg03">
				<div class="w480 font_f">
					<h3>今日新增用户数量</h3>
					<h1>100</h1>
					<p>当前用户数量：10000</p>
				</div>
			</div>
		</li>
		<li style="margin:40px 0 0 1%;">
			<div class="in_bg in_bg04">
				<div class="w480 font_f">
					<h3>今日新增用户数量</h3>
					<h1>100</h1>
					<p>当前用户数量：10000</p>
				</div>
			</div>
		</li>
	</ul>
</div>
    </div>
    <div style="clear:both;"></div>
</div>
<script type="text/javascript">
	var resize = function()
	{
		var all_h = $(window).height();
		var head_h = $(".header").height();
		var left_height = $(".table_body").height();
		var all_w = $(window).width();
		var head_w = $(".header").width();
		var left_w = $(".left_menu").width();
		$("iframe[name='content-body']").attr("height",all_h-head_h).attr("width",all_w-left_w);
		
	}
</script>