<?php if (!defined('THINK_PATH')) exit();?><style>
    .leftd {
        clear: both;
        float: left;
        width:100%;
        color:red
    }
    .rightd {
        clear: both;
        float: right;
        width:100%;
    }
    .btn_send{
    	border: 0;
	    width: 120px;
	    height: 38px;
	    line-height: 38px;
	    text-align: center;
	    background: #40a7ff;
	    float: left;
	    color: #fff;
	    font-size: 16px;
	    border-radius: 10px;
	    display: block;
	   
    }
    .takecon{
    	width:88%;height:350px; 
    	position:relative; 
    	margin:0 auto; border: 1px #ddd solid; 
    	border-radius: 5px; 
    	background: #fcfcfc;
    	padding: 1%;
    	overflow-y: scroll;
    }
    .guest{
    	color:#40a7ff; font-size: 14px; 
    }
    .custom{
    	color:#333; font-size: 14px; 
    }
    .guest span font{    	
    	font-size: 14px; 
    	display: block;
    	width: 100%;
    	line-height: 25px;
    }
    .custom span,font{    	
    	font-size: 14px; 
    	display: block;
    	width: 100%;
    	line-height: 25px;
    }
</style>
<input type="hidden" value="<?php echo ($_GET['id']); ?>" id="hiddenid">
<div><span>聊天记录</span></div>
<div style="width: 100%;text-align: center;"><span id="historycont" is_click='1' style="cursor: pointer; color: #40A7FF;">查看历史记录</span></div> 
<div id="content" style="overflow:hidden;height:400px;width: 1200px; " > 
    
    <div class="takecon" id="table_form" > 
        <?php if(is_array($new_msg)): foreach($new_msg as $key=>$vo): ?><div class="guest dialogue" mid="<?php echo ($vo['id']); ?>">
        		<span><?php if(empty($vo['names'])): ?>客户<?php else: echo ($vo['names']); endif; ?>(<?php echo ($vo['mobile']); ?>)<?php echo ($vo['timeadd']); ?></span>
        		<font><?php echo ($vo['msg']); ?></font>
        	</div><?php endforeach; endif; ?>
    </div>
</div>
<div>
    <textarea   value="" id="anser" style="margin:0 2% 0 5%; float:left; width:77%; height:35px; border: 1px #ddd solid; border-radius: 5px; background: #fcfcfc;"></textarea>
    <input id="commit" type="button" value="发送" class="btn_send">
</div>

<script type="text/javascript" src="_STATIC_/2015/js/jquery.min.js"></script>
<script type="text/javascript">
    $('#commit').click(function(){
        var str='';
        cont = $('#anser').val();
        if (cont=='') {
            return remind('请输入内容');
        }
        //var mytime=mydate.toLocaleTimeString(); //获取当前时间]      
        //str = "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td style='color: #ff0000'>"+cont+"</td><td>"+mytime+"</td></tr>";
        
        var data = {};
        $(data).attr('content',cont);
        $(data).attr('id',$('#hiddenid').val());
        $.post('/MemberMsg/writeMessage.html',data,function(F){
            //console.log(F);
            if(F.status==0){
                return top.jdbox.alert(F.status,F.info);
            }else{
                var mydate = new Date();
                var mytime = formatDate(mydate)
                str += "<div class='custom dialogue' mid='" + F.data.id + "'><span>客服"+mytime+"</span><font>"+cont+"</font></div>";
                $('#table_form').append(str);
                $('#anser').val("");
                getBottom();
            }
        },'json');
        
    });
    function getData(){
        var data = {};
        $(data).attr('id',$('#hiddenid').val());
        $(data).attr('is_getjson',1);
        $.post('/MemberMsg/getMessage.html',data,function(F){
            //console.log(F);
            if (jQuery.isEmptyObject(F.data))return false;
            if (F.status) {
                str = '';
                var new_msg = F.data;
                for (var k in new_msg) {
                    var names = new_msg[k].names?new_msg[k].names:"客户";
                    str += str += "<div class='guest dialogue' mid='" + new_msg[k].id + "'><span>" + names + '' + new_msg[k].timeadd + "</span><font>"+new_msg[k].msg+"</font></div>";
                }
                $('#table_form').append(str);
                getBottom();
            }
        },'json');
    }
    $(function(){
        window.setInterval(getData,5000);
    });
    function getBottom()
    {
        $('.takecon').scrollTop($('.takecon')[0].scrollHeight );
    }
    $('#historycont').click(function(){
        if ($(this).attr("is_click") == 0) return false;
        var last_id = $('#table_form').find('.dialogue:first').attr("mid");
        var data = {};
        $(data).attr('id',$('#hiddenid').val());
        $(data).attr('last_id',last_id);
        //console.log(data);
        $.post('/MemberMsg/historyMessage.html',data,function(F){
            console.log(F);
            if (F.status) {
                var str = '';
                var history = F.data;
                var id = $('#hiddenid').val();
                if (jQuery.isEmptyObject(history)) return $('#historycont').html("没有更多历史消息了").attr('is_click',0);
                for(var k in history){
                    var names = history[k].names?history[k].names:"客户";
                    if(history[k].from == id){
                        str += "<div class='guest dialogue' mid='" + history[k].id + "'><span>" + names + '' + history[k].timeadd + "</span><font>"+history[k].msg+"</font></div>";
                    }else{
                        str += "<div class='custom dialogue' mid='" + history[k].id + "'><span>客服"+history[k].timeadd+"</span><font>"+history[k].msg+"</font></div>";
                    }
                }
                $('#table_form').prepend(str);
            }
        },'json');
    });
    var formatDate = function (date) {
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        m = m < 10 ? ('0' + m) : m;
        var d = date.getDate();
        d = d < 10 ? ('0' + d) : d;
        var h = date.getHours();
        var minute = date.getMinutes();
        minute = minute < 10 ? ('0' + minute) : minute;
        var secod = date.getSeconds();
        return y + '-' + m + '-' + d+' '+h+':'+minute+':'+secod;
    }
</script>