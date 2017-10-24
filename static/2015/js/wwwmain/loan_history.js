$(function(){
	page = 2; is_loading = 1;
	$(window).scroll(function(){
		if($(document).height()==($(this).height()+$(this).scrollTop()) && is_loading==1){
		    var data= {};
		    $(data).attr("s",$("input[name='status']").val());
		    $(data).attr("is_ajax",1);
		    $(data).attr("p",page++);
		    wait();
			$.post("/Loan/loan_history",data,function(F){
		        console.log(F);
		        alertClose();
		        if ((F.status == 1) && F.data) {
		        	var list_str = '';
		            $.each (F.data,function(i,item) {
		            	list_str += '<section class="history_one c100_div bgf">';
		            	list_str += '<section class="con_div p_center"><span>编号:' + item.tender_number;
		            	list_str += '</span><font>' + item.order_status_name +'</font>';
		            	list_str += '</section><ul><li><b>金额(元)</b><p>' + item.money + '</p>';
		            	list_str += '</li><li><b>期限(天)</b><p>' + item.lp_name + '</p>';
		            	list_str += '</li><li><b>借款日期</b><p>' + item.timeadd + '</p>';;
		            	list_str += '</li></ul></section>';
		            })
		            $(".hShow").append(list_str);
		        } else {
		            is_loading=0;
		        }
			},'json')
		}
	});				
})
var jump = function(url,id)
{
	if (url == '')return false;
	if (id == '')return remind("订单ＩＤ错误");
	window.location.href = url + "/id/" + id;
}