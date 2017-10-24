<include file="Public:header"/>
<body> 
    <header>
        <a href="/Index/index" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>消息中心</h1>       
    </header> 
    <section class="mt40 mui-con-view">
    <foreach name="list" item="vo">
    	<section class="mui-con-li" onclick="javascript:window.location.href='/Index/msgcontent/id/{$vo.id}'">
    		<i <notempty name="vo['is_read']">style="visibility:hidden;"</notempty>></i><font>{$vo['title']}</font><time>{$vo['timeadd']}</time>
            <p>{$vo['summary']}</p>
    	</section>
    </foreach>
    </section>  
</body>
<script type="text/javascript">
$(function(){
    page = 2; is_loading = 1;
    $(window).scroll(function(){
        if($(document).height()==($(this).height()+$(this).scrollTop()) && is_loading==1){
            var data= {};
            $(data).attr("is_ajax",1);
            $(data).attr("p",page++);
            wait();
            $.post("/Index/message",data,function(F){
                console.log(F);
                alertClose();
                if ((F.status == 1) && F.data) {
                    var list_str = '';
                    $.each (F.data,function(i,item) {
                        list_str += '<section class="mui-con-li" onclick="jump(' + item.id + ');">';
                        list_str += (item.is_read)?'<i></i>':'<i style="visibility:hidden;"></i>';
                        list_str += '<font>' + item.title +'</font><time>' + item.timeadd +'</time>';
                        list_str += '<p>' + item.summary +'</p></section>'
                    })
                    $(".mui-con-view").append(list_str);
                } else {
                    remind(F.info);
                    is_loading=0;
                }
            },'json')
        }
    });             
})
var jump = function(id) 
{
    if (id=='')return remind("新闻ID错误");
    window.location.href = "/Index/msgcontent/id/" + id;
}
</script>
</html>


