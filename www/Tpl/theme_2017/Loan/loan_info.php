<include file="Public:header"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/loan.css" /> 
<body style="background: #efefef;">
<header>
    <a href="javascript:history.back();" class="btn_back">
    <img src="_STATIC_/2015/member/image/login/return.png">
    <font class="fl">返回</font>       
    </a> 
    <h1>借款详情</h1>     
</header>
<section class="mt40 c100_div bgf" >
    <if condition="$_GET['s'] eq 1">
        <section class="forder_div">
            <b>应还金额（元）</b>
            <h2><php> echo (float)($info['repayment_money']+$info['late_fee'])</php></h2>   
            <span>{$info['end_time']}待还款</span>     
        </section>
        <section class="con-li">
            <font>费  用</font>
            <span><b>{$info['total_fee']} </b>元</span>
        </section>
        <a class="btn_lj" onclick="repayment({$info['id']});">立即还款</a>
    <else/>
        <section class="forder_div">
            <b>已结清金额（元）</b>
            <h2>{$info['back_real_money']}</h2>      
        </section>
        <section class="con-li">
            <font>费  用</font>
            <span><b>{$info['total_fee']} </b>元</span>
        </section>
    </if>  
</section>
<section class="forder_details">
    <section class="forder-view-cell">
        <span>借款编号</span>
        <font>{$info['tender_number']}</font>
    </section>
    <section class="forder-view-cell">
        <span>申请金额</span>
        <font>{$info['money']}元</font>
    </section>
    <section class="forder-view-cell">
        <span>申请期限</span>
        <font>{$info['lp_name']}</font>
    </section>
    <section class="forder-view-cell">
        <span>到账金额</span>
        <font>{$info['pay_money']}元</font>
    </section>
    <section class="forder-view-cell">
        <span>申请时间</span>
        <font>{$info['timeadd']}</font>
    </section>
    <section class="forder-view-cell">
        <span>还款时间</span>
        <font>{$info['back_real_time']}</font>
    </section>
    <section class="forder-view-cell">
        <span>还款方式</span>
        <font>一次性还款付息</font>
    </section>
    <section class="forder-view-cell">
        <span>借款状态</span>
        <font>{$info['order_status_name']}</font>
    </section>
    <section class="forder-view-cell">
        <span>收款银行卡</span>
        <font>{$member_info['bank_name']}(尾号{$member_info['acc_no']|substr=-4,4})</font>
    </section>
    <section class="forder-view-cell">
        <span>借款合同</span>
        <a onclick="tk();">查看</a>
    </section>
</section>
<script type="text/javascript">
var repayment = function(id) 
{
    if (!id) return remind("订单ＩＤ错误");
    var data = {};data.id = id;
    conAlert("是否确认还款",repay,data);
}
var repay = function(data)
{
    $.DialogByZ.Close();
    wait();
    $.post("/Loan/repayment",data,function(F){
        remind(F.info);
        if (F.status) {
            window.location.href = "/Loan/loan_history";
        }
    },'json')
}
</script>