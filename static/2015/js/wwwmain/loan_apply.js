$(function(){
	$(".jday").click(function(){
		 var pro_id = $(this).attr("pro_id");
         $(".jtime").text(this.text).attr("pro_id",pro_id);
         // var placeholder = "单笔可借"+$(this).attr("pro_min")+"-"+$(this).attr("pro_max")+"元";
          $("input[name='loanmoney']").val('');
          displayData();
         $(".loan_type").html($(this).attr("pro_ret"));
         $(".tkgrayBg,.tkbtm_bg").hide();
    })
    $("input[name='loanmoney']").blur(function(){
    	if (!$(this).val())return false;
        var max = $(this).attr("max");
        var min = $(this).attr("min");
        if (parseInt($(this).val()) > parseInt(max)) {
            $(this).val(max);
        }
        if (parseInt($(this).val()) < parseInt(min)) {
            $(this).val(min);
        }
    	var data = {};
    	data.pro_id = $(".jtime").attr("pro_id");
    	data.money = $(this).val();
    	$.post("/Index/getPayMoney",data,function(F){
    		//console.log(F);
            displayData(F.data);
    		// $(".pay_money").html(F.data.pay_money+"元");
    		// $(".total_fee").html(F.data.total+"元");
    		// $(".plat_fee").html(F.data.plat_free+"元");
    		// $(".periode_fee").html(F.data.periode_fee+"元");
    		// $(".procedure_fee").html(F.data.procedure_free+"元");
    	},'json')
    })
    $("#sub").click(function(){
    	var data = {};
    	data.pro_id = $(".jtime").attr("pro_id");
    	data.loanmoney = $("input[name='loanmoney']").val();
    	data._loanapply_ = $("input[name='_loanapply_']").val();
    	if (!data.pro_id) return remind("请选择贷款产品");
    	if (!data.loanmoney)return remind("请填写借款金额");
    	wait();
    	$.post("/Loan/apply",data,function(F){
    		console.log(F);
    		remind(F.info);
    		if (F.status) {
    			window.location.href = "/Loan/loan_history";
    		}
    	},"json")
    })
})
var displayData = function(fee_data = {})
{
    $(".pay_money").html(fee_data.pay_money?fee_data.pay_money:'0'+"元");
    $(".total_fee").html(fee_data.total?fee_data.total:'0'+"元");
    $(".plat_fee").html(fee_data.plat_free?fee_data.plat_free:'0'+"元");
    $(".periode_fee").html(fee_data.periode_fee?fee_data.periode_fee:'0'+"元");
    $(".procedure_fee").html(fee_data.procedure_free?fee_data.procedure_free:'0'+"元");
}