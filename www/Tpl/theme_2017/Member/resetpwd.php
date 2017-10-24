<include file="Public:header"/>
    <header>
        <a href="/Member/account" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>修改密码</h1>       
    </header>  
    <section class="mt50 mui-con-view">
    	<section class="mui-con-li">
    		<font style="width: 30%;">原 密 码</font>
    		<input type="text" name="password" placeholder="请输入原密码">
    	</section>
    	<section class="mui-con-li">
    		<font style="width: 30%;">新 密 码</font>
    		<input type="text" name="newpassword" placeholder="请输入新密码">
    	</section>
    	<section class="mui-con-li">
    		<font style="width: 30%;">确认密码</font>
    		<input type="password" name="renewpassword" placeholder="请再次输入新密码" >    		
    	</section>    
    </section>    
    <input type="hidden" name="_resetpwd_" value="{$_resetpwd_}">
    <input type="submit" name="sub" value="提交" class="btn bgb reset_sub" id="sub">
</body>
<script type="text/javascript">
$(function(){
    $('.reset_sub').click(function(){
        var data = {};
        data.password = $("input[name='password']").val();
        data.newpassword = $("input[name='newpassword']").val(); 
        data.renewpassword = $("input[name='renewpassword']").val();
        data.is_reset = 1;
        data._resetpwd_ = $("input[name='_resetpwd_']").val();
        if (!/^[0-9a-zA-Z]{6,12}$/.test(data.newpassword)) {
            return remind("密码格式为6-12位数字或字母");
        }
        if (data.newpassword != data.renewpassword) {
            return remind("两次密码不一致");
        }
        wait();
        $.post("/Member/resetpwd",data,function(F){
            remind(F.info);
            if (F.status) {
                window.location.href = "/Member/account";
            }
        },'json')
    })
})
</script>
</html>

