$(function(){
	var is_send = 1;
	$(".btn_yzm").click(function(){
		if (!is_send)return false;
		var _this = this;
		var data = {};
		data.mobile = $("input[name='mobile']").val();
		if ((data.mobile == '') || (!/^1[3|5|6|8]\d{9}$/.test(data.mobile))) {
			remind("请输入正确的手机号码");
			return false;
		}
		wait();
		$.post("/Member/recoverVerify",data,function(F){
			console.log(F);
			remind(F.info);
			if (F.status) {
				is_send = 0;
				sendCode(_this);
			}
		},'json')
	})
	$("#sub").click(function(){
		var data = {};
		data.mobile = $("input[name='mobile']").val();
		data.verify = $("input[name='verify']").val();
		data.newpassword = $("input[name='newpassword']").val();
		data._recoverpwd_ = $("input[name='_recoverpwd_']").val();
		data.is_recover = 1;
		if ((data.mobile == '') || (!/^1[3|5|6|8]\d{9}$/.test(data.mobile))) {
			remind("请输入正确的手机号码");
			return false;
		}
		if (!/^\d{6}$/.test(data.verify)) {
			remind("验证码为6位数字");
			return false;
		}
		if (!/^[0-9a-zA-Z]{6,12}$/.test(data.newpassword)) {
			remind("密码格式不正确");
			return false;
		}
		console.log(data);
		wait();
		$.post("/Member/recoverpwd",data,function(F){
			console.log(F);
			remind(F.info);
			if (F.status) {
				window.location.href = "/Member/login";
			}
		},'json')
	})
})