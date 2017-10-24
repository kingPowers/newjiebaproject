<include file="Public:header"/>
<style type="text/css">
    body{background: #fff;}
    .c_title{text-align: left; margin:0 auto; font-size: 18px; color: #000; padding-top: 10px;}
    .c_time{ font-size: 12px; color: #999; line-height: 30px;}
    .c_cont{font-size: 12px; color: #333;}
</style>
<body> 
    <header>
        <a href="/Index/message" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>消息内容</h1>       
    </header> 
    <section class="mt40 mui-con-view" style="padding: 0 2%;">
    	<h2 class="c_title">{$info['title']}</h2>
        <p class="c_time">{$info['timeadd']}</p>
        <section class="c_cont">            
        {$info['content']}
        </section>
    </section>  
</body>
</html>


