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
</style>
</head>
<body>
<style type="text/css">
.tableright{width: 200px;text-align: center;padding-left:100px; }
.search-div{border:1px solid #bbb;width: 207px;margin-top: 0px;position: absolute;top:32px;z-index: 100;background: #fff;border-radius: 2px;display: none;}
.search-div p{cursor: pointer;margin: 0 auto;padding: 5px 10px;}
.search-div p:hover{background: #ccc}
.close{display: inline-block;float: right;font-size: 5px;color: #666;padding-right: 10px;}
label{display: inline-block;}
</style>
<script type="text/javascript">
</script>
<form class="definewidth m20" id="editForm">
  <table class="table table-bordered table-hover m10">
    <tr>
      <input type="hidden" name="productid" value="<?php echo ($info['id']); ?>">
      <input type="hidden" name="capital_company_id" value="<?php echo $_REQUEST['cid']?$_REQUEST['cid']:$info['capital_company_id']; ?>">
      <td class="tableleft" style="text-align: center;" colspan="4">产品信息</td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">产品名称：</td>
      <td class="tableright"><input <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?> type="text" name="name" value="<?php echo ($info['name']); ?>"></td>
      <td width="10%" class="tableleft">备注：</td>
      <td class="tableright">
        <input type="text" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?> name="remark" value="<?php echo ($info['remark']); ?>">
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">金额：</td>
      <td class="tableright"><input <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> type="text"  name="min_loanmoney" value="<?php echo ($info['min_loanmoney']); ?>">——<input  type="text" name="max_loanmoney" value="<?php echo ($info['max_loanmoney']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?>></td>
      <td width="10%" class="tableleft">还款方式：</td>
      <td class="tableright">
        <select name="loan_repayment" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>>
          <?php if(is_array($pay_type)): foreach($pay_type as $k=>$vo): ?><option value="<?php echo ($k); ?>" <?php if(($k) == $info['loan_repayment']): ?>selected=""<?php endif; ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">期限：</td>
      <td class="tableright"><input type="text"  <?php if(($is_sub) == "1"): ?>class="short"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> name="min_loan_periode" value="<?php echo ($info['min_loan_periode']); ?>">——<input  <?php if(($is_sub) == "1"): ?>class="short"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> type="text" name="max_loan_periode" value="<?php echo ($info['max_loan_periode']); ?>"></td>
      <td width="10%" class="tableleft">利率：</td>
      <td class="tableright">
        <input type="text" name="periode_rate" value="<?php echo ($info['periode_rate']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?>>(日利率)
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">是否需要芝麻信用分：</td>
      <td class="tableright"><label><input type="radio" class="short" name="is_sesame_credit" value="1" <?php if(($info['is_sesame_credit']) == "1"): ?>checked=""<?php endif; ?>>需要</label><label><input class="short" type="radio" name="is_sesame_credit" value="0" <?php if(($info['is_sesame_credit']) == "0"): ?>checked=""<?php endif; ?>>不需要</label></td>
      <td width="10%" class="tableleft">芝麻信用分通过标准：</td>
      <td class="tableright">
        <input type="text" name="sesame_credit_score" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> value="<?php echo ($info['sesame_credit_score']); ?>">分以上
      </td>
    </tr>
    <tr><td colspan="4" style="text-align: center;width: 50px"><?php if(!empty($is_sub)): ?><a class="btn btn-success" id="sub_info">提交</a><?php endif; ?><a class="btn btn-error" href="/Product/capitalProduct" style="margin-left: 20px;">返回</a></td>
      </tr>
  </table>
</form>
<script type="text/javascript">
$(function(){
  $("#sub_info").click(function(){
    var data = {};
    data.capital_company_id = $("input[name='capital_company_id']").val();
    data.productid = $("input[name='productid']").val();
    data.name = $("input[name='name']").val();
    data.remark = $("input[name='remark']").val();
    data.min_loanmoney = $("input[name='min_loanmoney']").val();
    data.max_loanmoney = $("input[name='max_loanmoney']").val();
    data.loan_repayment = $("select[name='loan_repayment']").find("option:selected").attr("value");
    data.min_loan_periode = $("input[name='min_loan_periode']").val();
    data.max_loan_periode = $("input[name='max_loan_periode']").val();
    data.periode_rate = $("input[name='periode_rate']").val();
    data.is_sesame_credit = $("input[name='is_sesame_credit']:checked").val();
    data.sesame_credit_score = $("input[name='sesame_credit_score']").val();
    // data.deposit_percent = $("input[name='deposit_percent']").val();
    top.jdbox.alert(2);
    $.post("/Product/editCapital.html",data,function(F){
        console.log(F);
        top.jdbox.alert(F.status,F.info);
        if(F.status)
        {
          window.location.href = "/Product/capitalProduct";
        }
    },'json')
  })
})
</script>
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