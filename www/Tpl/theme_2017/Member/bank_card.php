<include file="Public:header"/>
<body style="background: #efefef;"> 
    <header>
        <a href="/Member/account" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>银行卡</h1>       
    </header>
   <section class="mt40 bgf">
        <section class="con-li">
            <font>姓名</font>
            <span>{$member_info['names']}</span>
        </section>
        <section class="con-li">
            <font>身份证</font>
            <span>{$member_info['certiNumber']}</span>
        </section>
        <section class="con-li">
            <font>银行卡号</font> 
            <span>{$member_info['acc_no']}</span>           
        </section>
        <section class="con-li">
            <font>开户行</font>
            <span>{$member_info['bank_name']}</span>            
        </section>   
        <section class="con-li">
            <font>银行预留手机号</font>
            <span>{$member_info['bank_mobile']}</span>              
        </section>   
    </section>
</body>
</html>


