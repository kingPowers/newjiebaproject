<include file="Public:header"/>
<style type="text/css">
    .mui-con-li font{width: 20%;}
</style>
    <header>
        <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>找回密码</h1>       
    </header>  
    <section class="mt50 mui-con-view">
    	<section class="mui-con-li">
    		<font style="width: 30%">手 机 号</font>
    		<input type="text" name="mobile" placeholder="请输入手机号">
    	</section>
    	<section class="mui-con-li">
    		<font>验 证 码</font>
    		<input type="text" name="verify" placeholder="请输入验证码" id="input_yzm">
    		<a class="btn_yzm">获取验证码</a>
    	</section>
    	<section class="mui-con-li">
    		<font>新 密 码</font>
    		<input type="password" name="newpassword" placeholder="6-12位字母、数字结合" id="input_pwd">    		
    	</section>    
    </section>    
    <input type="hidden" name="_recoverpwd_" value="{$_recoverpwd_}">
    <input type="submit" name="sub" value="提交" class="btn bgb" id="sub">
</body>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/recoverpwd.js"></script>
</html>

