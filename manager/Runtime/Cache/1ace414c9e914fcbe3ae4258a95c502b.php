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
select[class='dis_input']{background: #fff;box-shadow: 0 0;border: 0;text-align: center;}
</style>
</head>
<body>
<style type="text/css">
.tableright{width: 200px;text-align: center;padding-left:100px; }
.search-div{border:1px solid #bbb;width: 207px;margin-top: 0px;position: absolute;top:32px;z-index: 100;background: #fff;border-radius: 2px;display: none;}
.search-div p{cursor: pointer;margin: 0 auto;padding: 5px 10px;}
.search-div p:hover{background: #ccc}
.close{display: inline-block;float: right;font-size: 5px;color: #666;padding-right: 10px;}

</style>
<script type="text/javascript" src="_STATIC_/2015/js/jquery.form.js"></script>
<script type="text/javascript">
$(function(){

  $("input[name='channel_company']").focus(function(){
	$keywords = $(this).val();
	$.post("<?php echo U('/Public/getChannelList');?>",{"channel_name":$keywords},function(F){
        var search = F.data;
        var searchStr = '';
        $(".search-div").html('');
        if(search != '')$(".search-div").show();
        for (var i = 0;i < search.length;i ++)
        {
          searchStr += "<p onclick='chose(this);' cid='" + search[i].id + "' level='" + search[i].channel_level + "'>" + search[i].companyname + "</p>";
        }
        if(searchStr != '')searchStr += "<a class='close' onclick='closes(this);'>关闭</a>";
        $(".search-div").append(searchStr);
     },'json')
  
  });
  $("input[name='channel_company']").keyup(function(F){
     $keywords = $(this).val();
     if($keywords == '')return false;
     $(this).attr('pid','');
     $.post("<?php echo U('/Public/getChannelList');?>",{"channel_name":$keywords},function(F){
        //console.log(F);
        var search = F.data;
        var searchStr = '';
        $(".search-div").html('');
        if(search != '')$(".search-div").show();
        for (var i = 0;i < search.length;i ++)
        {
          searchStr += "<p onclick='chose(this);' cid='" + search[i].id + "' level='" + search[i].channel_level + "'>" + search[i].companyname + "</p>";
        }
        if(searchStr != '')searchStr += "<a class='close' onclick='closes(this);'>关闭</a>";
        $(".search-div").append(searchStr);
     },'json')
  })
  $("input[name='channel_company']").blur(function(){
    //if($(this).val() == '')$("select[name='channel_level']").removeAttr('disabled');
    $(this).val('');
  })
})
function chose(_this)
{
  var id = $(_this).attr('cid');
  var channel_name = $(_this).html();
  $("input[name='channel_company_id']").val(id);
  $("input[name='channel_company']").val(channel_name);
  $(".search-div").hide();
}
function closes(_this)
{
  $(".search-div").hide();
}
</script>
<form class="definewidth m20" method="post" action="addBusinessCompany" id="addBusinessCompanyForm"  enctype='multipart/form-data'>
  <table class="table table-bordered table-hover m10">
    <tr>
      <td class="tableleft" style="text-align: left;" colspan="4">基本信息</td>
    </tr>
    <tr>
	  <input name="id" type="hidden" value='<?php echo ($company["id"]); ?>'/>
      <td width="10%" class="tableleft">所属渠道:</td>
      <td class="tableright" style="position: relative;">
	  <input type="text" name="channel_company"  value='<?php echo ($company["channel_companyname"]); ?>' placeholder="请选择所属渠道" autocomplete="off"><div class="search-div"></div>
	  <input name="channel_company_id" type="hidden" value='<?php echo ($company["channel_company_id"]); ?>'/>
	  </td>
      <td width="10%" class="tableleft">合作公司名称</td>
      <td class="tableright"><input type="text" name="companyname" value='<?php echo ($company["companyname"]); ?>'></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">合作公司类型：</td>
      <td align="center" class="tableright">
        <select name="company_type">
          <option>-- 请选择 --</option>
          <?php if(is_array($companyType)): foreach($companyType as $key=>$v): ?><option <?php if(($company["company_type"]) == $v): ?>selected=selected<?php endif; ?> ><?php echo ($v); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
      <td width="10%" class="tableleft">行业类型：</td>
      <td align="center" class="tableright">
        <input type="text" name="company_trade"  value='<?php echo ($company["company_trade"]); ?>'>
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">详细地址:</td>
      <td class="tableright"><input type="text" name="company_address"  value='<?php echo ($company["company_address"]); ?>'></td>
      <td width="10%" class="tableleft">负责人姓名：</td>
      <td><input type="text" name="legal_name"  value='<?php echo ($company["legal_name"]); ?>'></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">负责人身份证:</td>
      <td class="tableright"><input type="text" name="legal_certinumber" value='<?php echo ($company["legal_certinumber"]); ?>'></td>
      <td width="10%" class="tableleft">负责人号码：</td>
      <td><input type="text" name="legal_mobile" value='<?php echo ($company["legal_mobile"]); ?>'></td>
    </tr>
	<tr>
      <td width="10%" class="tableleft">组织机构代码号:</td>
      <td class="tableright"><input type="text" name="company_code" value="<?php echo ($company["company_code"]); ?>"></td>
      <td width="10%" class="tableleft"></td>
      <td></td>
    </tr>
	<tr>
      <td class="tableleft" style="text-align: left;" colspan="4">证件信息</td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">负责人身份证正面:</td>
      <td class="tableright">
	  <img src='<?php echo ($company["legal_certinumber_path"]); ?>' style="max-width:100px"/>
		
		<input type="file" name="legal_certinumber_pic" id="">
							
	  </td>
      <td width="10%" class="tableleft">营业执照：</td>
      <td>
	  <img src='<?php echo ($company["license_number_path"]); ?>' style="max-width:100px"/>
	  <input type="file" name="license_number_pic" id="">
	  
	  </td>
    </tr>
	<tr>
      <td width="10%" class="tableleft">协议1:</td>
      <td class="tableright">
		
		<img src='<?php echo ($company["agreement1_path"]); ?>' style="max-width:100px"/>
		<input type="file" name="agreement1_pic" id="">
		
	  </td>
      <td width="10%" class="tableleft">协议2：</td>
      <td>
	  
		<img src='<?php echo ($company["agreement2_path"]); ?>' style="max-width:100px"/>
		<input type="file" name="agreement2_pic" id="">
		
	  </td>
    </tr>
	
    <tr>
      <td class="tableleft" style="text-align: left;" colspan="4">结算信息</td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">开户银行:</td>
      <td class="tableright"><input type="text" name="legal_bank" value='<?php echo ($company["legal_bank"]); ?>'></td>
      <td width="10%" class="tableleft">分行名称：</td>
      <td><input type="text" name="legal_branch" value='<?php echo ($company["legal_branch"]); ?>'></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">开户名称:</td>
      <td class="tableright"><input type="text" name="legal_openname" value='<?php echo ($company["legal_openname"]); ?>'></td>
      <td width="10%" class="tableleft">开户账号：</td>
      <td><input type="text" name="legal_accno" value='<?php echo ($company["legal_accno"]); ?>'></td>
    </tr>
    <tr ><td colspan="4" style="text-align: center;width: 50px">
	<input type="hidden" name="editType" value="edit"/>
	<input class="btn btn-success  addBusinessCompany" type="button" value="提交"/>
	<a class="btn btn-error" href="/BusinessCompany/allCompanyList" style="margin-left: 20px;">取消</a></td></tr>
  </table>
</form>
<script type="text/javascript">
$(function(){
  $(".addBusinessCompany").click(function(){
		var formUrl = "/BusinessCompany/editBusinessCompany"
		var options = {
                url: formUrl,
                type: "post",
                dataType: "json",
                success: function(o) {
					alert( o.info);
                    if (o.status == 0) {
                        
                    } else {
                   	 	location.href="/BusinessCompany/allCompanyList";
                    }
                }
            };
            $("#addBusinessCompanyForm").ajaxSubmit(options);
  });
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