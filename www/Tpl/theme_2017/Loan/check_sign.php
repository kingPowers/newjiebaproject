<include file="Public:header"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/loan.css" />
<script type="text/javascript">
    $(function () {
        $("#agree").attr('checked',false);
        $("#agree").change(function(){
            $(".middle_div").toggle();
        })
    })
</script> 
<body style="background: #efefef;">
<header>
	<a href="/Loan/loan_history" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
    </a> 
    <h1>签署合约</h1>    
</header>
<section class="mui-con-view mt40">
	<section class="mui-con-li" style="text-align: center; margin: 0;">
		<a style="color: #5495e6; ">个人借款协议</a>
	</section>
	<section class="mui-con-li" style="text-align: center; margin: 0;">
		<a style="color: #5495e6; ">居间服务协议</a>
	</section> 
</section>
<section class="agree">
	<input type="checkbox" name="agree" id="agree" value="1" />同意贷款相关合同（勾选之后才可签约）
</section>

<section class="middle_div" style="display:none;">
	<p>我们将发送<font>验证码</font>到您的手机</p>
	<p>{$mobile}</p>	
	<section class="btn_div">
        <input type="hidden" name="loan_id" value="{$_REQUEST['id']}">
		<input type="text" id="text_yzm" name="verify"  maxlength='6' placeholder="请输入短信验证码" class="input_yzmb fl">
		<input type="button" name="sub"  value="获取验证码" class="btn_yzmb fl" id="send_ems">
	</section>
</section>
<input type="hidden" name="_loansign_" value="{$_loansign_}">
 <input type="submit" name="" value="签约" class="btn bgb sign_sub" id="sub">  
</body>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/sign.js"></script>
</html>

