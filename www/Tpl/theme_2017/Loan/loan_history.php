<include file="Public:header"/> 
        <link rel="stylesheet" href="_STATIC_/2015/member/css/loan.css" /> 
    <script type="text/javascript">
      $(function () {            
            var ary = $(".listyle").click(function () {
                $(this).parent().find("a.show").addClass("hide").removeClass("show blue_bg");   
                $(this).addClass("show blue_bg").removeClass("hide");   
                $("#con_div > section.hShow").addClass("hHide").removeClass("hShow"); 
                $("#con_div > section:eq(" + $.inArray(this, ary) + ")").addClass(function () {  
                    return "hShow";   
                }).removeClass("hHide");  
            }).toArray();
        }); 
    </script>
<body> 
    <header>
        <a href="/Member/account" class="btn_back">
        <img src="_STATIC_/2015/member/image/login/return.png">
        <font class="fl">返回</font>       
        </a> 
        <h1>借款记录</h1>       
    </header> 
    <nav>
        <input type="hidden" name="status" value="{$_REQUEST['s']}">
        <a href="/Loan/loan_history" <if condition="$_REQUEST['s'] eq 0">class="listyle blue_bg show"<else/>class="listyle hide"</if>>全部</a>
        <a href="/Loan/loan_history/s/1" <if condition="$_REQUEST['s'] eq 1">class="listyle blue_bg show"<else/>class="listyle hide"</if>>还款中</a>
        <a href="/Loan/loan_history/s/2" <if condition="$_REQUEST['s'] eq 2">class="listyle blue_bg show"<else/>class="listyle hide"</if>>已还款</a>
    </nav>
    <section id="con_div">
        <section class="c100_div hShow">
        <foreach name="list" item="vo">
            <section onclick="jump('{$vo.jumpurl}','{$vo.id}')" class="history_one c100_div bgf">   
                <section class="con_div p_center">
                    <span>编号:{$vo['tender_number']}</span>
                    <font>{$vo['order_status_name']}</font>
                </section>            
                <ul>
                    <li>
                        <b>金额(元)</b>
                        <p>{$vo['money']}</p>
                    </li>
                    <li>
                        <b>期限(天)</b>
                        <p>{$vo['lp_name']}</p>
                    </li>
                    <li>
                        <b>借款日期</b>
                        <p>{$vo['timeadd']}</p>
                    </li>
                </ul>
            </section>
        </foreach> 
    </section>
<script type="text/javascript" src="_STATIC_/2015/js/wwwmain/loan_history.js"></script>  
<script type="text/javascript">
    //$(window).scroll(function(){alert(1);})
</script>  
</body>
</html>


