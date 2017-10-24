<include file="Public:header" />
<style type="text/css">
    .mui-con-li font{width: 20%;}
</style>
    <header>
        <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>注册</h1>       
    </header>   
    <section class="mt50 mui-con-view">
    	<section class="mui-con-li">
    		<font>手 机 号</font>
    		<input type="text" name="mobile" placeholder="请输入手机号">
    	</section>
    	<section class="mui-con-li">
    		<font>验 证 码</font>
    		<input type="text" name="verify" placeholder="请输入验证码" id="input_yzm">
    		<a class="btn_yzm">获取验证码</a>
    	</section>
    	<section class="mui-con-li">
    		<font>输入密码</font>
    		<input type="password" name="password" placeholder="6-12位字母、数字结合" id="input_pwd">
    		<a class="btn_pwd">
    			<img src="_STATIC_/2015/member/image/login/eye.png" class="eye">
    			<img src="_STATIC_/2015/member/image/login/eyesb.png" style="display: none;" class="eyesb">
    		</a>
    	</section>
    	<section class="mui-con-li">
    		<font>确认密码</font>
    		<input type="password" name="repassword" placeholder="请再次输入密码">
    	</section>
    </section>
    <section class="agree">
    	<input type="checkbox" name="agreement" class="fl" value="1"><font> 同意<a> 《借吧用户协议》</a></font>
    </section>
    <input type="hidden" name="_register_" value="{$_register_}">
    <input type="submit" name="" value="注册" class="btn bgb btn_login" style="margin-top: 8%;">
</body>
</html>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/register.js"></script>
<script type="text/javascript">
	$(function(){
		$(".eye").click(function(){	
			$(".btn_pwd > img").toggle();		
			$("#input_pwd").attr("type","text");
		})
		$(".eyesb").click(function(){	
			$(".btn_pwd > img").toggle();		
			$("#input_pwd").attr("type","password");
		})
	})
</script>