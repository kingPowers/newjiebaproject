<include file="Public:header"/>
<body> 
    <header>
        <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>实名认证</h1>       
    </header>
    <section class="mt40 mui-con-view">
    	<section class="mui-con-li">
    		<font>用户姓名</font>
    		<input type="text" name="names" placeholder="请输入您的姓名">
    	</section>
    	<section class="mui-con-li">
    		<font>身份证号</font>
    		<input type="text" name="certiNumber" placeholder="请输入您的身份证号码">
    	</section>
        <section class="mui-con-li bank-list">
            <font>开户银行</font>
            <input type="text" name="bank_name" disabled="" style="background: #fff" placeholder="请选择您的开户银行" id="bank">
        </section>
        <ul class="order-gray-li">
            <foreach name="bank_list" item="vo">
            <li bank_key="{$key}" class="bank_li">{$vo}</li>
            </foreach>
        </ul>
    	<section class="mui-con-li">
    		<font>银行卡号</font>
    		<input type="text" name="bank_acc" placeholder="请输入您的银行卡号">
    	</section> 
    	<section class="mui-con-li">
    		<font>手机号码</font>
    		<input type="text" name="mobile" maxlength="11" placeholder="请输入银行预留手机号">
    	</section>    
    	<section class="mui-con-li">
    		<font>验证号码</font>
    		<input type="text" name="verify" maxlength="6" placeholder="请输入验证码" id="input_yzm">
    		<a class="btn_yzm">获取验证码</a>
    	</section>    	   
    </section>
    <section class="mui-con-li fg" style="border: 0">
    	温馨提示：请填写真实有效的身份信息，以便通过借款审核，一经认证，信息无法修改。
    </section>  
    <input type="hidden" name="_bindcard_" value="{$_bindcard_}">
    <input type="submit" name="sub" value="提交" class="btn bgb bind_sub" id="sub">
</body>
</html>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/bind.js"></script>
<script type="text/javascript">
    $(function(){
        $(".bank-list").click(function(){
            $(".order-gray-li").slideToggle();
        })
    })
</script>

