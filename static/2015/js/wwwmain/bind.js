$(function(){
	var is_send = 1;
	$(".btn_yzm").click(function(){
		if (!is_send)return false;
		var _this = this;
		var data = {};
		data = getBindData();
		var S = checkData(data,"verify");
		if (!S)return false;
		wait();
		$.post("/Index/bindverify",data,function(F){
			//console.log(F);
			remind(F.info);
			if (F.status) {
				is_send = 0;
				sendCode(_this);
			}
		},'json')
	})
	$(".bank_li").click(function(){
		$("input[name='bank_name']").val($(this).html());
		$(".order-gray-li").slideUp();
	})
	$(".bind_sub").click(function(){
		var data = {};
		data = getBindData();
		data.verify = $("input[name='verify']").val();
		data._bindcard_ = $("input[name='_bindcard_']").val();
		data.is_bind = 1;
	 	if (!/^\d{6}$/.test(data.verify)) {
			remind("验证码为6位数字");
			return false;
		}
		wait();
		$.post("/Index/realname",data,function(F){
			remind(F.info);
			if (F.status) {
				window.location.href = "/Member/account";
			}
		},'json')
	})
})
var getBindData = function()
{
	var data = {};
	data.names = $("input[name='names']").val();
	data.certiNumber = $("input[name='certiNumber']").val();
	data.bank_acc = $("input[name='bank_acc']").val();
	data.mobile = $("input[name='mobile']").val();
	data.bank_name = $("input[name='bank_name']").val();
	return data;
}
var checkData = function(data,type='')
{	
	if (data.names == '') {
		remind("姓名不能为空");
		return false;
	}
	if (!/(^([\d]{15}|[\d]{18}|[\d]{17}x)$)/.test(data.certiNumber)) {
		remind("身份证号不正确");
		return false;
	}
	if (data.bank_name == '') {
		remind("开户银行不能为空");
		return false;
	}
	if (data.bank_acc == '') {
		remind("银行卡号不能为空");
		return false;
	}
	if (!/^1[3|5|6|8]\d{9}$/.test(data.mobile)) {
		remind("请输入正确的手机号码");
		return false;
	}
	return true;		
}