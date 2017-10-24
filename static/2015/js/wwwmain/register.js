$(function(){
	var is_send = 1;
	$(".btn_yzm").click(function(){
		if (!is_send)return false;
		var _this = this;
		var data = {};
		data.mobile = $("input[name='mobile']").val();
		var S = checkData(data,'mobile');
		if (!S)return remind("请输入正确的手机号码");
		wait();
		$.post("/Member/registerVerify",data,function(F){
			//console.log(F);
			remind(F.info);
			if (F.status) {
				is_send = 0;
				sendCode(_this);
			}
		},'json')
	})
	$(".btn_login").click(function(){ 
		var data = {};
		data.mobile = $("input[name='mobile']").val();
		data.verify = $("input[name='verify']").val();
		data.password = $("input[name='password']").val();
		data.repassword = $("input[name='repassword']").val();
		data._register_ = $("input[name='_register_']").val();
		data.is_agree = $("input[name='agreement']:checked").val();
		data.is_register = 1;
		//console.log(data);
		var S = checkData(data);
		if (!S) return false;
		wait();
		$.post("/Member/register",data,function(F){
			console.log(F);
			remind(F.info);
			if (F.status) {
				window.location.href = "/Member/account";
			}
		},"json")
	})
})
var checkData = function(data,element='')
{
	if (element) {
		var val = $(data).attr(element);
		if (element == 'mobile')return (val && /^1[3|5|6|8]\d{9}$/.test(val));
	}//alert(data.mobile)
	if (!/^1[3|5|6|8]\d{9}$/.test(data.mobile)) {
		remind("请输入正确的手机号码");
		return false;
	}
	if (!/^\d{6}$/.test(data.verify)) {
		remind("验证码为6位数字");
		return false;
	}
	if (!/^[0-9a-zA-Z]{6,12}$/.test(data.password)) {
		remind("密码格式不正确");
		return false;
	}
	if (data.password != data.repassword) {
		remind("两次密码不一致");
		return false;
	}
	if (!data.is_agree) {
		remind("请先同意借吧用户协议");
		return false;
	}
	return true;		
}