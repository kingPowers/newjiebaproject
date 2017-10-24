<include file="Public:header"/>
    <link rel="stylesheet" href="_STATIC_/2015/member/css/loan.css" />
    <header>
        <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>借款</h1>       
    </header> 
    <section class="mt40 mui-con-view">
    	<section class="mui-con-li li_money">
    		<font>借多少</font>
            <input type="text" name="loanmoney" max="{$allow_money['maxMoney']}" min="{$allow_money['minMoney']}" placeholder="单笔可借{$allow_money['minMoney']}-{$allow_money['maxMoney']}元" class="text_money" style="padding-right: 10%;">
    	</section>
    	<section class="mui-con-li bg_jt">
    		<font>借多久</font>
            <span class="jtime" pro_id="{$loan_list[0]['id']}">{$loan_list[0]['periodeName']}</span>
    	</section>
    	<section class="mui-con-li bg_jt">
    	    <font>怎么还</font> 
            <span class="loan_type">{$loan_list[0]['repaymentName']}</span>           
    	</section>
    	<section class="mui-con-li">
    		<font>手续费用</font>
            <span><b class="total_fee"></b><a class="ico_wh"><img src="_STATIC_/2015/member/image/loan/ico_detail.png" ></a></span>            
    	</section>   
        <section class="mui-con-li">
            <font>到账金额</font>
            <span class="pay_money"></span>              
        </section>   
    </section>  
    <input type="submit" name="sub" value="申请借款" class="btn bgb" id="sub">
    <section class="tkbtm_bg" style="display: none;">
    <foreach name="loan_list" item="vo">
        <a class="jday" pro_id="{$vo['id']}" pro_min="{$vo['min_loanmoney']}" pro_max="{$vo['max_loanmoney']}" pro_ret="{$vo['repaymentName']}">{$vo['periodeName']}</a>
    </foreach>
        <a style="margin-top: 5px;" class="cancel">取消</a>
    </section>
    <section class="tkjin_bg bgf" style="display: none;">
        <h1>费用详情</h1>
        <ul>
            <li>借款费用<font class="periode_fee">0元</font></li> 
            <li>手续费<font class="procedure_fee">0元</font></li> 
            <li>平台管理费<font class="plat_fee">0元</font></li>
            <li>费用总和<font class="total_fee">0元</font></li>
        </ul>
        <input type="hidden" name="_loanapply_" value="{$_loanapply_}">           
        <section class="btn_ture" id="tl_close">确定</section> 
    </section>
    <section class="tkgrayBg" style="height: 100%; display: none;"></section>
</body>
</html>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/loan_apply.js"></script>
<script type="text/javascript">
    $(function(){
        $(".jtime").click(function(){
            $(".tkgrayBg").show();
            $(".tkbtm_bg").show();
            $(".cancel").click(function(){
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

