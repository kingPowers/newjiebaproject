$(function(){
	var is_send = 1;
	$("#send_ems").click(function(){
		if (!is_send)return false;
		var _this = this;
		var data = {};
		var is_agree = $("input[name='agree']:checked").val();
		if (is_agree != 1) return remind("请先同意借款协议");
		data.loan_id = $("input[name='loan_id']").val();
		if (!data.loan_id)return remind("订单ＩＤ错误，请刷新重试");
		wait();
		$.post("/Loan/signverify",data,function(F){
			//console.log(F);
			remind(F.info);
			if (F.status) {
				is_send = 0;
				sendCode(_this);
			}
		},'json')
	})
	$('.sign_sub').click(function(){
		var data = {};
		data.loan_id = $("input[name='loan_id']").val();
		data.verify = $("input[name='verify']").val();
		data._loansign_ = $("input[name='_loansign_']").val();
		data.is_sign = 1;
		var is_agree = $("input[name='agree']:checked").val();
		if (is_agree != 1) return remind("请先同意借款协议");
		if (!data.loan_id)return remind("订单ＩＤ错误，请刷新重试");
		if (!/^\d{6}$/.test(data.verify))return remind("验证码为6位数字");
		wait();
		$.post("/Loan/check_sign",data,function(F){
			remind(F.info);
			if (F.status) {
				window.location.href = "/Loan/loan_history";
			}
		},'json')
		 
	})
})