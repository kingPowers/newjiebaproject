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
</head>
<body>
<style type="text/css">
.tableright{width: 200px;text-align: center;padding-left:100px; }
.search-div{border:1px solid #bbb;width: 207px;margin-top: 0px;position: absolute;top:32px;z-index: 100;background: #fff;border-radius: 2px;display: none;}
.search-div p{cursor: pointer;margin: 0 auto;padding: 5px 10px;}
.search-div p:hover{background: #ccc}
.close{display: inline-block;float: right;font-size: 5px;color: #666;padding-right: 10px;}
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
      <td class="tableright"><input type="text" name="companyname" value=""></td>
      <td width="10%" class="tableleft">资金方主体：</td>
      <td class="tableright">
        <select name="fund_subject" >
          <?php if(is_array($fund_subject)): foreach($fund_subject as $key=>$v): ?><option <?php if(($v) == $info['subjectname']): ?>selected=""<?php endif; ?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">资金方类型：</td>
      <td class="tableright">
       <select name="fund_type">
          <?php if(is_array($fund_type)): foreach($fund_type as $key=>$v): ?><option <?php if(($v) == ""): ?>selected=""<?php endif; ?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
      <td width="10%" class="tableleft">法人姓名：</td>
      <td class="tableright"><input type="text" name="legalname" value="<?php echo ($info['legalname']); ?>"></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">法人身份证号：</td>
      <td class="tableright"><input type="text" name="legal_certinumber" value="<?php echo ($info['legal_certinumber']); ?>"></td>
      <td width="10%" class="tableleft">组织机构代码证：</td>
      <td class="tableright"><input type="text" name="organize_code" value="<?php echo ($info['organize_code']); ?>"></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">地址：</td>
      <td class="tableright"><input type="text" name="company_address" value="<?php echo ($info['company_address']); ?>"></td>
      <td width="10%" class="tableleft">营业执照号：</td>
      <td class="tableright"><input type="text" name="license_number" value="<?php echo ($info['license_number']); ?>"></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">联系人姓名：</td>
      <td class="tableright"><input type="text" name="contact_name" value="<?php echo ($info['contact_name']); ?>"></td>
      <td width="10%" class="tableleft">税务登记证：</td>
      <td class="tableright"><input type="text" name="tax_certificate" value="<?php echo ($info['companyname']); ?>"></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">联系人手机号：</td>
      <td class="tableright"><input type="text" name="contact_mobile" value="<?php echo ($info['contact_mobile']); ?>"></td>
      <td width="10%" class="tableleft">联系人邮箱：</td>
      <td class="tableright"><input type="text" name="contact_email" value="<?php echo ($info['contact_email']); ?>"></td>
    </tr>
    <tr>
      <td class="tableleft" style="text-align: center;" colspan="4">银行卡信息</td>
    </tr>
     <tr>
      <td width="10%" class="tableleft">开户名称：</td>
      <td class="tableright"><input type="text" name="legal_openname" value="<?php echo ($info['legal_openname']); ?>"></td>
      <td width="10%" class="tableleft">开户账号：</td>
      <td class="tableright"><input type="text" name="legal_accno" value="<?php echo ($info['legal_accno']); ?>"></td>
    </tr>
     <tr>
      <td width="10%" class="tableleft">开户银行：</td>
      <td class="tableright"><input type="text" name="legal_bank" value="<?php echo ($info['legal_bank']); ?>"></td>
      <td width="10%" class="tableleft">账号类型：</td>
      <td class="tableright">
       <select name="open_type">
          <?php if(is_array($open_type)): foreach($open_type as $key=>$v): ?><option ><?php echo ($v); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
    </tr> 
    <tr><td colspan="4" style="text-align: center;width: 50px"><?php if(!empty($is_sub)): ?><a class="btn btn-success" id="sub_add">提交</a><?php endif; ?><a class="btn btn-error" href="/Channel/index" style="margin-left: 20px;">取消</a></td>
      </tr>
  </table>
</form>
<script type="text/javascript">
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