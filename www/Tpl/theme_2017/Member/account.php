<include file="Public:header"/>
<body style="background: #fff;"> 
    <section class="c100_div">
    	<img src="_STATIC_/2015/member/image/account/bg_account.jpg">
    </section>
    <section class="bg_info">
        <img src="_STATIC_/2015/member/image/account/bg_info.png">
        <section class="name_div">
            <img src="_STATIC_/2015/member/image/account/heads.png">
            <p>您好！
                <eq name="member_info['nameStatus']" value="1">
                {$member_info['names']}<br/>
                已实名认证
                <else/>
                {$member_info['username']}<br/>
                未实名认证
                </eq>
            </p>
            <section class="a_money">
                <section class="ney">
                    <h2>{$member_info['loan_money']}</h2>
                    <font>已借款金额（元）</font>
                </section>
                <section class="ney">
                    <h2>{$member_info['repayment_money']}</h2>
                    <font>待还金额（元）</font>
                </section>
            </section>
        </section>
    </section>
    <section class="con_div mui-con-view" style="margin: 16% auto;">
        <ul class="account-ul">
            <li onclick="javascript:window.location.href='/Loan/loan_history'">
                <font><img src="_STATIC_/2015/member/image/account/ico_01.png" class="a_img"></font>
                <span>借款记录</span>             
            </li >
            <li onclick="isBind({$member_info['bankStatus']});">
                <font><img src="_STATIC_/2015/member/image/account/ico_02.png" class="a_img"></font>
                <span>银行卡</span>             
            </li>
            <li onclick="javascript:window.location.href='/Member/resetpwd'">
                <font><img src="_STATIC_/2015/member/image/account/ico_03.png" class="a_img"></font>
                <span>修改密码</span>             
            </li>
            <li onclick="javascript:window.location.href='/Index/service_list'">
                <font><img src="_STATIC_/2015/member/image/account/ico_04.png" class="a_img"></font>
                <span>在线客服</span>             
            </li>
            <li onclick="javascript:window.location.href='/Member/appset'">
                <font><img src="_STATIC_/2015/member/image/account/ico_05.png" class="a_img"></font>
                <span>设置</span>             
            </li>
        </ul>
        <section class="tel">客服热线：<a href="tel:4006639066">{$member_info['company_mobile']}</a></section>
    </section>
    <footer>
    	<a href="/Index/index"><span id="sy" class="ico_footer"></span></a>
        <a href="/Member/account"><span id="zh" class="ico_footer"></span></a>
    </footer>
</body>
<script type="text/javascript">
var isBind = function(status)
{
    window.location.href = (status == 1)?"/Member/bank_card":"/Index/realname";
}
</script>
</html>


