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
.tableright{width: 200px;text-align: center;padding-left:100px; }
.search-div{border:1px solid #bbb;width: 207px;margin-top: 0px;position: absolute;top:32px;left: 8px;z-index: 100;background: #fff;border-radius: 2px;display:none;}
.search-div p{cursor: pointer;margin: 0 auto;padding: 5px 10px;}
.search-div p:hover{background: #ccc}
.close{display: inline-block;float: right;font-size: 5px;color: #666;padding-right: 10px;}
label{display: inline-block;}
</style>
<script type="text/javascript">
$(function(){
  //查询商户和渠道
  $("input[name='business_company_id']").keyup(function(F){
     $keywords = $(this).val();
     if($keywords == '')return false;
     $(this).attr('choseid','');
     var searchNode = $(this).next(".search-div");
     var url = '';
     $.post("<?php echo U('/Public/getBusinessList');?>",{"name":$keywords},function(F){
        var search = F.data;console.log(F);
        var searchStr = '';
        searchNode.html('');
        if(search != '')searchNode.show();
        for (var i = 0;i < search.length;i ++)
        {
          searchStr += "<p channelid='" + search[i].channel_company_id + "' channelname='" + search[i].channel_companyname + "' onclick='chose(this);' choseid='" + search[i].id + "'>" + search[i].companyname + "</p>";
        }
        if(searchStr != '')searchStr += "<a class='close' onclick='closes(this);'>关闭</a>";
        searchNode.append(searchStr);
     },'json')
  })
  $("input[name='business_company_id']").blur(function(){
    $(this).val('').attr("choseid",'');
    $("input[name='channelid']").val('').attr("cid",'');
  })
  //查询资金方及其产品
  $("input[name='capitalid']").keyup(function(){
      $keywords = $(this).val();
     if($keywords == '')return false;
     $(this).attr('choseid','');
     var searchNode = $(this).next(".search-div");
     var url = '';
     $.post("<?php echo U('/Public/getCapitalList');?>",{"name":$keywords},function(F){
        var search = F.data;console.log(F);
        var searchStr = '';
        searchNode.html('');
        if(search != '')searchNode.show();
        for (var i = 0;i < search.length;i ++)
        {
          searchStr += "<p  onclick='chosecap(this);' choseid='" + search[i].id + "'>" + search[i].companyname + "</p>";
        }
        if(searchStr != '')searchStr += "<a class='close' onclick='closes(this);'>关闭</a>";
        searchNode.append(searchStr);
       
     },'json')
  })
  $("input[name='capitalid']").blur(function(){
    $(this).val('').attr("choseid","");
    $("select[name='loan_list']").html("<option>-- 请选择资金方 --</option>").attr("disabled","disabled");
  })
})
function chosecap(_this)
{
  var id = $(_this).attr("choseid");
  var capname = $(_this).html();
  $("input[name='capitalid']").val(capname);
  $("input[name='capitalid']").attr("choseid",id)
  $.post("<?php echo U('Public/getCapitalLoan');?>",{"id":id},function(F){
    console.log(F);
    var loan_list = F.data;
    var loan_str = '';
    if(loan_list == '')
    {
      $("select[name='loan_list']").html("<option>该资金方尚未添加产品</option>")
      return false;
    }
    $("select[name='loan_list']").html("<option>--请选择资金方产品--</option>");
    $("select[name='loan_list']").removeAttr("disabled");
    for(var i = 0;i < loan_list.length;i ++)
    {
      loan_str += "<option  loanid='" + loan_list[i].id + "'>" + loan_list[i].name + "</option>";    
    }
     $("select[name='loan_list']").append(loan_str);
  },'json');
  $(_this).parent(".search-div").hide()
}
$("select[name='loan_list']").live("change",function(){
  choseloan($(this).find("option:selected"));
});

function choseloan(_this)
{
  var loan_name = _this.text();
  var loan_id = _this.attr("loanid");
  if(loan_id == '')return false;
  $("input[name='loan_name']").val(loan_name);
}
function chose(_this)
{
  var choseid = $(_this).attr("choseid");
  var name = $(_this).html();
  var channelid = $(_this).attr("channelid");
  var channelname = $(_this).attr("channelname");
  $(_this).parent(".search-div").hide().prev("input").val(name).attr("choseid",choseid);
  $("input[name='channelid']").val(channelname).attr("cid",channelid);
}
function closes(_this)
{
  $(_this).parent(".search-div").hide();
}
</script>
<form class="definewidth m20" id="editForm">
  <table class="table table-bordered table-hover m10">
    <tr>
      <input type="hidden" class="need_search" name="productid" value="<?php echo ($info['id']); ?>">
      <td class="tableleft" style="text-align: center;" colspan="4">选择合作公司
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">合作公司:</td>
      <td class="tableright" style="position: relative;">
        <input type="text" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?> name="business_company_id" choseid="<?php echo ($info['business_company_id']); ?>" value="<?php echo ($info['bus_companyname']); ?>">
        <div class="search-div"></div>
      </td>
      <td width="10%" class="tableleft">渠道名称</td>
      <td class="tableright" style="position: relative;">
        <input type="text" name="channelid" cid=""  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?> value="<?php echo ($info['cha_companyname']); ?>" disabled="">
      </td>
    </tr>
    <tr>
        <td class="tableleft" style="text-align: center;" colspan="4">关联资金方</td>
      </tr>
    <tr>
      <td width="10%" class="tableleft">资金方：</td>
      <td class="tableright" style="position: relative;">
        <input type="text"  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?> name="capitalid" choseid='' value="<?php echo ($info['cap_companyname']); ?>">
        <div class="search-div"></div>
      </td>
      <td width="10%" class="tableleft">资金方产品：</td>
      <td align="center" class="tableright">
        <select name="loan_list" disabled=""  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>>
          <?php if(empty($info['cap_l_name'])): ?><option>-- 请选择资金方 --</option>
          <?php else: ?>
              <option loanid="<?php echo ($info['capital_loan_id']); ?>"><?php echo ($info['cap_l_name']); ?></option><?php endif; ?>
           
        </select>
      </td>
    </tr>
    <tr>
        <td class="tableleft" style="text-align: center;" colspan="4">
         产品配置
        </td>
      </tr>
    <tr>
      <td width="10%" class="tableleft">产品名称:</td>
      <td class="tableright">
        <input type="text"  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?> name="loan_name" value="<?php echo ($info['name']); ?>">
      </td>
      <td width="10%" class="tableleft">借款金额：</td>
      <td>
        <input type="text"  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> class="short" name="min_loanmoney" value="<?php echo ($info['min_loanmoney']); ?>">
        ——
        <input type="text"  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> class="short" name="max_loanmoney" value="<?php echo ($info['max_loanmoney']); ?>">
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">借款期限:</td>
      <td class="tableright" colspan="3">
        <?php if(is_array($periode)): foreach($periode as $key=>$vo): ?><label><input   <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> style="margin:0 5px 0 50px;position: relative;top:-3px;" type="radio" name="loan_periode_id" value="<?php echo ($vo['id']); ?>" <?php if(($vo['id']) == $info['loan_periode_id']): ?>checked=""<?php endif; ?>><?php echo ($vo['periode']); echo ($vo['unit']); ?></label><?php endforeach; endif; ?>
    </tr>
    <tr>
      <td width="10%" class="tableleft">借款利率:</td>
      <td class="tableright">
        <input type="text"  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> name="periode_rate" value="<?php echo ($info['periode_rate']); ?>">%(日利率)
      </td>
      <td width="10%" class="tableleft">借款逾期利率:</td>
      <td  class="tableright">
        <input type="text"  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> name="late_periode_rate" value="<?php echo ($info['late_periode_rate']); ?>">%(日利率)
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">手续费:</td>
      <td class="tableright">
        <input type="text"  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> name="procedure_free" value="<?php echo ($info['procedure_free']); ?>">
      </td>
      <td width="10%" class="tableleft">平台管理费率：</td>
      <td>
        <input type="text"  <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> name="plat_free" value="<?php echo ($info['plat_free_rate']); ?>">%
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">是否自动审核:</td>
      <td class="tableright" colspan="3">
        <label><input <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?> style="display: inline-block;" type="radio" name="is_auto" value="1" <?php if(($info['is_auto_pass']) == "1"): ?>checked=""<?php endif; ?> >是</label>
        <label><input <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?> type="radio" name="is_auto" value="0" <?php if(($info['is_auto_pass']) == "0"): ?>checked=""<?php endif; ?> >否</label>
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">是否启用:</td>
      <td class="tableright" colspan="3">
        <label><input <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?> style="display: inline-block;" type="radio" name="status" value="1" <?php if(($info['status']) == "1"): ?>checked=""<?php endif; ?> >启用</label>
        <label><input <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?> type="radio" name="status" value="2" <?php if(($info['status']) == "2"): ?>checked=""<?php endif; ?> >停用</label>
      </td>
    </tr>
    <tr>
      <td colspan="4" style="text-align: center;width: 50px">
        <?php if(!empty($is_sub)): ?><a class="btn btn-success" id="sub">提交</a><?php endif; ?>
          <a class="btn btn-error" href="/Product/index" style="margin-left: 20px;">返回
          </a>
      </td>
    </tr>
  </table>
</form>
<script type="text/javascript">
$(function(){
  $("#sub").click(function(){
    var data = {};
    data.id = $("input[name='productid']").val();
    data.business_company_id = $("input[name='business_company_id']").attr('choseid');
    data.channelid = $("input[name='channelid']").attr("cid");
    data.loanid = $("select[name='loan_list']").find("option:selected").attr("loanid");
    data.capitalid = $("input[name='capitalid']").attr("choseid");
    data.loan_name = $("input[name='loan_name']").val();
    data.min_loanmoney = $("input[name='min_loanmoney']").val();
    data.max_loanmoney = $("input[name='max_loanmoney']").val();
    data.loan_periode_id = $("input[name='loan_periode_id']:checked").val();
    data.periode_rate = $("input[name='periode_rate']").val();
    data.late_periode_rate = $("input[name='late_periode_rate']").val();
    data.procedure_free = $("input[name='procedure_free']").val();
    data.plat_free = $("input[name='plat_free']").val(); 
    data.is_auto = $("input[name='is_auto']:checked").val();
    data.status = $("input[name='status']:checked").val();
    top.jdbox.alert(2);
    $.post("/Product/editBusiness.html",data,function(F){
        console.log(F);
        top.jdbox.alert(F.status,F.info);
        if(F.status)
        {
          window.location.href = "/Product/index";
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