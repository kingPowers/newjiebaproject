$(function(){
	$(".btn_login").click(function(){
        //$auth_group = $("input[name='auth_group']:checked").val();
        $username = $("input[name='username']").val();
        $password = $("input[name='password']").val();
        $verify = $("input[name='verify']").val();
        if (($username == '') || ($password == '') || ($verify == ''))
        {
        	jdbox.alert(0,"请填写完整信息");
        }
        var data = {};
        //data.auth_group = $auth_group;
        data.username = $username;
        data.password = $password;
        data.verify = $verify;
        jdbox.alert(2,"正在登录请稍后");
        // if (data.auth_group == '2') {
        //     return channel_auto_login();
        // } 
        $.post(loginUrl,data,function(F){
        	console.log(F);
        	jdbox.alert(F.status,F.info);
        	if (F.status == 1)
        	{
                var jumpurl = F.data.jumpurl;
    	        window.location.href = jumpurl;
        	}
        },'json')
	})
    $(".img-verify").click(function(){
        $(this).attr('src',verifyUrl);
    })
})
// var channel_auto_login = function()
// {
//     $.post("/Public/getVerify.html",{"is_get":1},function(F){
//         console.log(F);
//         if (F.status) {
//             $("input[name='verifyCode']").val(F.data);
//             $("#login_form").submit();
//         }
//     },'json')
    
// }