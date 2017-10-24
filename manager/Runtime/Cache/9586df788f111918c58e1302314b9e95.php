<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>内页</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="__PUBLIC__/Css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">var OS = "_OS_", Public = "__PUBLIC__", STATIC = '_STATIC_/2015/', APP = '__APP__';</script>
<script type="text/javascript" src="_STATIC_/2015/js/wdate/WdatePicker.js"></script>
<script type="text/javascript" src="_STATIC_/2015/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/bootstrap.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/ckform.js"></script>
<link rel="stylesheet" type="text/css" href="_STATIC_/2015/box/wbox.css" />
<script type="text/javascript" src="_STATIC_/2015/js/jquery.box.js"></script>
<style type="text/css">
input[class='dis_input']{background: #fff;box-shadow: 0 0;border: 0;text-align: center;}
input[class='dis_input_short']{background: #fff;box-shadow: 0 0;border: 0;width:70px;position: relative;top:3px;text-align: center;}
select[class='dis_input']{background: #fff;box-shadow: 0 0;border: 0;padding-left: 50px;}
.table th, .table td{text-align: center;}
</style>
</head>
<body>
<style type="text/css">
.loan_st{display: inline-block;width: 200px;height: 30px;font-size: 18px;color: #3D4985;border-bottom: 1px solid #666;text-align: center;line-height:30px;cursor: pointer;color: #000;}
.loan_st:hover{color: #4FB8ED;}
#chose{color: #4FB8ED;border-bottom: 2px solid #4FB8ED;}
</style>
<script type="text/javascript">
var tender_id = "<?php echo ($_GET['tender_id']); ?>";
var memberid = "<?php echo ($_GET['memberid']); ?>";
if (tender_id && memberid) {
    window.location.href = "/Loan/info/id/"+tender_id+"/mid/"+memberid;
}
$(function(){
    $(".loan_st").click(function(){
        $("select[name='status']").find("option[value='" + $(this).attr("value") +"']").attr("selected","selected");
        $("#loan_form").submit();
    })
})
    
</script>
<form id="loan_form" class="form-inline definewidth m20" action="/Loan/index" method="get">
    渠道：
    <input type="text" name="channel" class="abc input-default"  value="<?php echo ($_GET['channel']); ?>">&nbsp;&nbsp;
    合作公司：
    <input type="text" name="business"  class="abc input-default" placeholder="" value="<?php echo ($_GET['business']); ?>">&nbsp;&nbsp;
    姓名：
    <input type="text" name="names"  class="abc input-default" placeholder="" value="<?php echo ($_GET['names']); ?>">&nbsp;&nbsp;
    手机号：
    <input type="text" name="mobile"  class="abc input-default" placeholder="" value="<?php echo ($_GET['mobile']); ?>">&nbsp;&nbsp;
    状态：
    <select name="status" class="short">
    	<?php if(is_array($status)): foreach($status as $k=>$vo): ?><option value="<?php echo ($k); ?>" <?php if(($k) == $_REQUEST['status']): ?>selected=""<?php endif; ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
    </select>&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
</form> 
<div>
<?php if(is_array($status)): foreach($status as $key=>$vi): ?><a class="loan_st" value="<?php echo ($key); ?>" <?php if(($key) == $_GET['status']): ?>id="chose"<?php endif; ?>><?php echo ($vi); ?>贷款</a><?php endforeach; endif; ?>
</div>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
       
    <tr>
        <th>贷款编号</th>    
        <th>用户ＩＤ</th>
        <th>所属渠道</th>
        <th>合作公司</th>
        <th>涉及产品</th>
        <th>姓名</th>
        <th>贷款金额</th>
        <th>费用</th>
        <th>贷款时间</th>
        <th>贷款期限</th>
        <th>贷款状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
            <td><?php echo ($vo['tender_number']); ?></td>
            <td><?php echo ($vo['memberid']); ?></td>
            <td><?php echo ($vo['cha_companyname']); ?></td>
            <td><?php echo ($vo['bus_companyname']); ?></td>
            <td><?php echo ($vo['bus_l_name']); ?></td>
            <td><?php echo ($vo['names']); ?></td>
            <td><?php echo ($vo['money']); ?></td>
            <td><?php echo ($vo['total_fee']); ?></td>
            <td><?php echo ($vo['timeadd']); ?></td>
            <td><?php echo ($vo['lp_name']); ?></td>
            <td><?php echo $status[$vo['status']] ?></td>
            <td><a href="/Loan/info/id/<?php echo ($vo['id']); ?>/mid/<?php echo ($vo['memberid']); ?>">查看</a></td>
        </tr><?php endforeach; endif; ?>
    </tbody>
</table>
<div><?php echo ($page); ?></div>
<script type="text/javascript">
	$(function(){
		
		 $('input#starttime').focus(function(){
	        	var endtime_val = $('#endtime').val();
	        	if(''!=endtime_val){
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:endtime_val});
	        	}else{
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	        	}
	        });
	        
	        $('input#endtime').focus(function(){
	        	var starttime_val = $('#starttime').val();
	        	if(''!=starttime_val){
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:starttime_val});
	        	}else{
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	        	}
	        });
	        
	        $('input#start_time').focus(function(){
	        	var endtime_val = $('#end_time').val();
	        	if(''!=endtime_val){
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:endtime_val});
	        	}else{
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	        	}
	        });
	        
	        $('input#end_time').focus(function(){
	        	var starttime_val = $('#start_time').val();
	        	if(''!=starttime_val){
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:starttime_val});
	        	}else{
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	        	}
	        });
	        
	});
</script>
<input type="hidden" name="reloadurl" value="__SELF__"/>
<div style="height:20px;width:100%;"></div>
</body>
</html>