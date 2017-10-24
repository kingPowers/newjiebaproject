<include file="Public:header"/>
<style type="text/css">
   /* WebKit browsers */
    ::-webkit-input-placeholder {
        color: #fff;  
    }         
</style>
    <header style="background: transparent;">
        <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>
        </a>        
    </header>
    <section class="bg_login">
        <img src="_STATIC_/2015/member/image/login/bg_login.png">
    </section>
    <section class="login_div con_div">
        <img src="_STATIC_/2015/member/image/login/ico_logo.png" class="logo_div">
        <section class="username">
            <img src="_STATIC_/2015/member/image/login/ico_phone.png"> 
            <input type="text" name="mobile" placeholder="请输入手机号">    
        </section> 
        <section class="username">
            <img src="_STATIC_/2015/member/image/login/ico_pass.png"> 
            <input type="password" name="password" placeholder="请输入密码">    
        </section>   
    </section>
    <input type="hidden" name="_login_" value="{$_login_}">
    <input type="hidden" name="redirect_url" value="{$redirect_url}">
    <input type="submit" name="log" value="登录" class="btn bgb btn_login">
    <input type="submit" name="reg" onclick="javascript:window.location.href='/Member/register';" value="注册" class="btn bgf btn_reg">
    <a class="for_div" href="/Member/recoverpwd">忘记密码?</a>
</body>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/login.js"></script>
<script type="text/javascript">
// $(".btn").click(function(){
//     remind(1);
// })
</script>
</html>