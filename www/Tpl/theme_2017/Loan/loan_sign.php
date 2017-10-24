<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">       
        <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>{$pageseo.title}</title>
        <script type="text/javascript" src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/style.css" /> 
        <link rel="stylesheet" href="_STATIC_/2015/member/css/loan.css" /> 
    </head>
<body> 
    <header>
        <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>借款确认</h1>       
    </header> 
    <section class="mt40 mui-con-view">
    	<section class="mui-con-li">
    		<font>借款人</font>
            <span id="f_gray">张三</span>   
    	</section>
    	<section class="mui-con-li">
    		<font>借款金额</font>
            <span id="f_gray">1000元</span>
    	</section>
    	<section class="mui-con-li">
    	    <font>借款期限</font> 
            <span id="f_gray">7天</span>           
    	</section>
    	<section class="mui-con-li">
    		<font>借款费用</font>
            <span style="color: #ababab;">100元<a class="ico_wh"><img src="_STATIC_/2015/member/image/loan/ico_detail.png" ></a></span>            
    	</section>   
        <section class="mui-con-li">
            <font>到账金额</font>
            <span id="f_gray">900.00元</span>              
        </section>
        <section class="mui-con-li">
            <font>收款银行</font>
            <span id="f_gray">招商银行(尾号1235)</span>              
        </section> 
        <section class="mui-con-li">
            <font>还款方式</font>
            <span id="f_gray">一次性还款1000元</span>              
        </section>  
        <section class="mui-con-li">
            <font>应还日期</font>
            <span id="f_gray">2017-05-22</span>              
        </section>    
    </section> 
    <section class="agree">
        <input type="checkbox" name="" class="fl"><font> 同意<a> 《借吧借款协议》</a></font>
    </section> 
    <input type="submit" name="" value="确认借款" class="btn bgb" id="sub">  
    <section class="tkjin_bg bgf" style="display: none;">
        <h1>费用详情</h1>
        <ul>
            <li>xx费用<font>100.00元</font></li> 
            <li>xx费用<font>100.00元</font></li> 
            <li>xx费用<font>100.00元</font></li> 
        </ul>           
        <section class="btn_ture" id="tl_close">确定</section> 
    </section>
    <section class="tkgrayBg" style="height: 100%; display: none;"></section>
</body>
</html>
<script type="text/javascript">
    $(function(){
        $(".jtime").click(function(){
            $(".tkgrayBg").show();
            $(".tkbtm_bg").show();
            $(".cancel").click(function(){
                $(".tkgrayBg,.tkbtm_bg").hide();
            })
            $(".jday").click(function(){
                 $(".jtime").text(this.text);
                 $(".tkgrayBg,.tkbtm_bg").hide();
            })
        })
        $(".ico_wh").click(function(){
            $(".tkgrayBg").show();
            $(".tkjin_bg").show();
            $("#tl_close").click(function(){
                $(".tkgrayBg,.tkjin_bg").hide();
            })
        })
    })
</script>

