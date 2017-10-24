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
      <input type="hidden" name="capitalid" value="<?php echo ($info['id']); ?>">
      <td class="tableleft" style="text-align: center;" colspan="4">基本信息</td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">资金方名称：</td>
      <td class="tableright"><input type="text" name="companyname" value="<?php echo ($info['companyname']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
      <td width="10%" class="tableleft">资金方主体：</td>
      <td class="tableright">
        <select name="subject"  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>>
          <?php if(is_array($subject)): foreach($subject as $key=>$v): ?><option <?php if(($v) == $info['subjectname']): ?>selected=""<?php endif; ?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">资金方类型：</td>
      <td class="tableright">
       <select name="capital_type" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>>
          <?php if(is_array($capital_type)): foreach($capital_type as $key=>$v): ?><option <?php if(($v) == $info['typename']): ?>selected=""<?php endif; ?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
      <td width="10%" class="tableleft">法人姓名：</td>
      <td class="tableright"><input type="text" name="legal_name" value="<?php echo ($info['legal_name']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">法人身份证号：</td>
      <td class="tableright"><input type="text" name="legal_certinumber" value="<?php echo ($info['legal_certinumber']); ?>"  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
      <td width="10%" class="tableleft">组织机构代码证：</td>
      <td class="tableright"><input type="text" name="organize_code" value="<?php echo ($info['organize_code']); ?>"  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">地址：</td>
      <td class="tableright"><input type="text" name="company_address" value="<?php echo ($info['company_address']); ?>"  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
      <td width="10%" class="tableleft">营业执照号：</td>
      <td class="tableright"><input type="text" name="license_number" value="<?php echo ($info['license_number']); ?>"  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">联系人姓名：</td>
      <td class="tableright"><input type="text" name="contact_name" value="<?php echo ($info['contact_name']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
      <td width="10%" class="tableleft">税务登记证：</td>
      <td class="tableright"><input type="text" name="tax_certificate" value="<?php echo ($info['tax_certificate']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">联系人手机号：</td>
      <td class="tableright"><input type="text" name="contact_mobile" value="<?php echo ($info['contact_mobile']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
      <td width="10%" class="tableleft">联系人邮箱：</td>
      <td class="tableright"><input type="text" name="contact_email" value="<?php echo ($info['contact_email']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
    </tr>
    <tr>
      <td class="tableleft" style="text-align: center;" colspan="4">银行卡信息</td>
    </tr>
     <tr>
      <td width="10%" class="tableleft">开户名称：</td>
      <td class="tableright"><input type="text" name="legal_openname" value="<?php echo ($info['legal_openname']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
      <td width="10%" class="tableleft">开户账号：</td>
      <td class="tableright"><input type="text" name="legal_accno" value="<?php echo ($info['legal_accno']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
    </tr>
     <tr>
      <td width="10%" class="tableleft">开户银行：</td>
      <td class="tableright"><input type="text" name="legal_bank" value="<?php echo ($info['legal_bank']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
      <td width="10%" class="tableleft">账号类型：</td>
      <td class="tableright">
       <select name="open_type" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>>
          <?php if(is_array($open_type)): foreach($open_type as $key=>$v): ?><option <?php if(($v) == $info['legal_opentype']): ?>selected=""<?php endif; ?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
    </tr> 
    <tr>
      <td class="tableleft" style="text-align: center;" colspan="4">资金方启用</td>
    </tr>
     <tr>
      <td class="tableleft">是否启用：</td>
      <td><label><input type="radio" name="status" value="1" <?php if(($info['status']) == "1"): ?>checked=""<?php endif; ?>>启用</label><label><input type="radio" name="status" value="2" <?php if(($info['status']) == "2"): ?>checked=""<?php endif; ?>>停用</label></td>
    </tr>
    <tr>
      <td class="tableleft" style="text-align: center;" colspan="4">保证金信息</td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">保证金比率：</td>
      <td colspan="3" class="tableright"><input type="text" name="deposit_percent"  value="<?php echo ($info['deposit_percent']); ?>" <?php if(($is_sub) == "1"): ?>class="short"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?>>%</td>
    </tr>
    <tr><td colspan="4" style="text-align: center;width: 50px"><?php if(!empty($is_sub)): ?><a class="btn btn-success" id="sub_info">提交</a><?php endif; ?><a class="btn btn-error" href="/Capital/index" style="margin-left: 20px;">返回</a></td>
      </tr>
  </table>
</form>
<script type="text/javascript">
$(function(){
  $("#sub_info").click(function(){
    var data = {};
    data.capitalid = $("input[name='capitalid']").val();
    data.companyname = $("input[name='companyname']").val();
    data.contact_name = $("input[name='contact_name']").val();
    data.subject = $("select[name='subject']").find("option:selected").html();
    data.legal_name = $("input[name='legal_name']").val();
    data.capital_type = $("select[name='capital_type']").find("option:selected").html();
    data.legal_certinumber = $("input[name='legal_certinumber']").val();
    data.license_number = $("input[name='license_number']").val();
    data.organize_code = $("input[name='organize_code']").val();
    data.company_address = $("input[name='company_address']").val();
    data.tax_certificate = $("input[name='tax_certificate']").val();
    data.contact_mobile = $("input[name='contact_mobile']").val();
    data.legal_openname = $("input[name='legal_openname']").val();
    data.contact_email = $("input[name='contact_email']").val();
    data.legal_accno = $("input[name='legal_accno']").val();
    data.legal_bank = $("input[name='legal_bank']").val();
    data.status = $("input[name='status']:checked").val();
    data.open_type = $("select[name='open_type']").find("option:selected").html();
    data.deposit_percent = $("input[name='deposit_percent']").val();
    top.jdbox.alert(2);
    $.post("/Capital/edit.html",data,function(F){
        console.log(F);
        top.jdbox.alert(F.status,F.info);
        if(F.status)
        {
          window.location.href = "/Capital/index";
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