<include file="Public:header"/>
<body>
    <link rel="stylesheet" href="_STATIC_/2015/member/css/loan.css" />
    <header>
        <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>设置</h1>       
    </header> 
    <section class="mt50 mui-con-view" onclick="javascript:window.location.href='/Member/aboutapp'">    
    	<section class="mui-con-li bg_jt">
    		<a style="color: #000;" href="/Member/aboutapp">关于借吧</a>            
    	</section>    	
    </section>  
    <input type="submit" name="sub" value="退出当前帐号" class="btn bgb" id="sub"> 
</body>
<script type="text/javascript">
$(function(){
    $("#sub").click(function () {
        wait();
        $.post("/Member/logOut",{},function (F) {
            remind(F.info);
            if (F.status) {
                window.location.href = "/Member/login";
            }
        },'json')
    })
})
</script>
</html>


