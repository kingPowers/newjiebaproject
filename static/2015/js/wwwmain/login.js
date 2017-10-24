$(function(){
  	$(".btn_login").click(function(){
  		var data = {};
  		data.mobile = $("input[name='mobile']").val();
  		data.password = $("input[name='password']").val();
  		data._login_ = $("input[name='_login_']").val();
  		data.redirect_url = $("input[name='redirect_url']").val();
  		data.is_login = 1;
      wait();
  		$.post("/Member/login",data,function(F){
  			//console.log(F);
  			remind(F.info);
        //alert(F.info);
  			if (F.status) {
  				window.location.href = F.data.jump_url?F.data.jump_url:"/Member/account";
  			}
  		},'json')
  	})
})