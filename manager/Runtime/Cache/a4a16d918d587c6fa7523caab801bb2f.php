<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>login</title>
	<script type="text/javascript">var MANAGER="_MANAGER_", STATIC="_STATIC_",verifyUrl="<?php echo U('/Public/verifyCode');?>",loginUrl="<?php echo U('/Public/doLogin');?>",BUSINESS = "_BUSINESS_";</script>
	<script type="text/javascript" src="_STATIC_/manager/js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="_STATIC_/manager/js/wbox.js"></script>
	<link rel="stylesheet" type="text/css" href="_STATIC_/manager/box/wbox.css">
	<script type="text/javascript" src="_STATIC_/manager/js/jquery.box.js"></script>
	<link rel="stylesheet" href="_STATIC_/manager/css/style.css">
	<link rel="stylesheet" href="_STATIC_/manager/css/login.css">
	<script type="text/javascript">
		 $(function () {
		    // var err = "<?php echo ($err); ?>";
		    // if (err)top.jdbox.alert(0,err);         
	        var ary = $(".blue").click(function () {
	            $(this).parent().find("div.box_y").addClass("box_round5").removeClass("box_y");   
	            $(this).addClass("box_y").removeClass("box_round5");   
	            $(".blue").addClass("box_round5").removeClass("box_y"); 
	            $(".blue:eq(" + $.inArray(this, ary) + ")").addClass(function () {  
	                return "box_y";   
	            }).removeClass("box_round5");  
	        }).toArray();
	    });
	</script>
</head>
<body>
<form id="login_form" method="post" action="_CHANNEL_/Public/autoLogin">
	<div class="bg_login">
		<div class="tit_login pm_center">
			<img src="_STATIC_/manager/image/login/tit_login.png">
		</div>
		<div class="login_box pm_center">
			<h2>登录账户</h2>
			<div class="login_sbox">
				<!-- <p>
					<span><input type="radio" name="auth_group" checked="" value="1"> 管理员</span>
					<span><input type="radio" name="auth_group"  value="2" class="check_channel"> 渠道</span>
				</p> -->
				<div class="login_input box_round5 blue" style="margin-top: 27px;">
					<input type="text" name="username" class="sbox_inp" placeholder="用户名">
					<img src="_STATIC_/manager/image/login/ico_user.png">
				</div>
				<div class="login_input box_round5 blue">
					<input type="password" name="password" class="sbox_inp" placeholder="密码">
					<img src="_STATIC_/manager/image/login/ico_pwd.png">
				</div>
				<div class="login_input" style="margin-bottom: 15px;">
					<input type="text" name="verify" placeholder="验证码" class="yzm_input box_round5 blue">
					<span ><a><img class="img-verify" style="height: 50px;cursor: pointer;" src="/Public/verifyCode"></a></span>
					<input type="hidden" name="verifyCode" value="">
				</div>
				<!-- <a class="btn_forget font_b">忘记密码？</a> -->

				<a class="btn_login box_round5 bg_b">登录</a>
			</div>			
		</div>
	</div>
</form>
</body>
<script type="text/javascript" src="_STATIC_/manager/js/login.js"></script>
</html>