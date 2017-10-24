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
$(function(){
  $("select[name='province']").change(function(){
     $province_code = $(this).find("option:selected").attr('pid');
     if(!$province_code)return top.jdbox.alert(0,"请重新选择省份");
     $.post("<?php echo U('/Public/getCity');?>",{"province_code":$province_code},function(F){
          var citys = F.data;
          var cityStr = '';
          $(".city").html("<option>-- 请选择 --</option>");
          for (var i=0;i<citys.length;i++){
            cityStr += "<option is_select='1'>" + citys[i].city_name + "</option>";
          }
          $(".city").append(cityStr);
     },'json')
  })
  $("input[name='channel_belong']").keyup(function(F){
     $keywords = $(this).val();
     if($keywords == '')return false;
     $(this).attr('pid','');
     $.post("<?php echo U('/Public/getChannelList');?>",{"name":$keywords},function(F){
        console.log(F);
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
  $("input[name='channel_belong']").blur(function(){
    if($(this).val() == '')$("select[name='channel_level']").removeAttr('disabled');
    $(this).val('');
  })
})
function chose(_this)
{
  var pid = $(_this).attr('cid');
  var channel_name = $(_this).html();
  var level = $(_this).attr('level');
  level++;
  if(level > 3)level = 3;//alert(level);
  $("input[name='channel_belong']").val(channel_name).attr("pid",pid);
  $("select[name='channel_level']").attr('disabled','true');
  $("select[name='channel_level'] option[v='" + level + "']").attr("selected","selected");
  $(".search-div").hide();
}
function closes(_this)
{
  $(".search-div").hide();
}
</script>
<form class="definewidth m20" id="editForm">
  <table class="table table-bordered table-hover m10">
    <tr>
    <input type="hidden" name="channelid" value="<?php echo ($info['id']); ?>">
      <td class="tableleft" style="text-align: center;" colspan="4">新增渠道</td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">所属渠道:</td>
      <td class="tableright" style="position: relative;"><input type="text" name="channel_belong" pid='' placeholder="" value="<?php echo ($info['p_companyname']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>><div class="search-div"></div></td>
      <td width="10%" class="tableleft">渠道名称</td>
      <td class="tableright"><input type="text" name="channel_name" value="<?php echo ($info['companyname']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">省份：</td>
      <td align="center" class="tableright">
        <select name="province" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>>
          <option>-- 请选择 --</option>
          <?php if(is_array($province_list)): foreach($province_list as $key=>$v): ?><option is_select="1" <?php if(($info['province']) == $v['provinceName']): ?>selected=''<?php endif; ?> pid="<?php echo ($v['province_code']); ?>"><?php echo ($v['provinceName']); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
      <td width="10%" class="tableleft">城市：</td>
      <td align="center" class="tableright">
        <select name="city" <?php if(($is_sub) == "1"): ?>class="city"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>>
          <?php if(!empty($info['city'])): ?><option is_select='1'><?php echo ($info['city']); ?></option><?php endif; ?>
          <option>-- 请选择省份 --</option> 
        </select>
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">地址:</td>
      <td class="tableright"><input type="text" style="width: 300px;" name="address" value="<?php echo ($info['address']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
      <td width="10%" class="tableleft">邮箱：</td>
      <td><input type="text" name="email" value="<?php echo ($info['email']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">负责人:</td>
      <td class="tableright"><input type="text" name="legalname" value="<?php echo ($info['legalname']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
      <td width="10%" class="tableleft">手机号码：</td>
      <td><input type="text" name="mobile" value="<?php echo ($info['mobile']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">渠道级别:</td>
      <td colspan="3" class="tableright">
        <select name="channel_level" <?php if(empty($info['parent_id'])): else: ?>disabled=""<?php endif; ?> <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>>
          <option>-- 请选择 --</option>
          <?php if(is_array($channel_level)): foreach($channel_level as $k=>$vo): ?><option v="<?php echo ($k); ?>" <?php if(($info['channel_level']) == $k): ?>selected=''<?php endif; ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
    </tr>
    <tr>
      <td class="tableleft" style="text-align: center;" colspan="4">结算信息</td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">开户银行:</td>
      <td class="tableright"><input type="text" name="bank_name" value="<?php echo ($info['bank_name']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
      <td width="10%" class="tableleft">分行名称：</td>
      <td><input type="text" name="bank_branch" value="<?php echo ($info['bank_branch']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">开户名称:</td>
      <td class="tableright"><input type="text" name="bank_account" value="<?php echo ($info['bank_account']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
      <td width="10%" class="tableleft">开户账号：</td>
      <td><input type="text" name="bank_accno" value="<?php echo ($info['bank_accno']); ?>" <?php if(($is_sub) == "1"): ?>class="search-input"<?php else: ?>class="dis_input" disabled=""<?php endif; ?>></td>
    </tr>
    <tr>
      <td>是否启用：</td>
      <td align="center"><label><input type="radio" name="status" <?php if($info['status'] == 1): ?>checked=""<?php endif; ?> value="1">启用</label></td>
      <td colspan="2"><label><input type="radio" name="status" value="0" <?php if($info['status'] == 0): ?>checked=""<?php endif; ?>>停用</label></td>
      </tr>
      <tr><td colspan="4" style="text-align: center;width: 50px"><?php if(!empty($is_sub)): ?><a class="btn btn-success" id="sub_add">提交</a><?php endif; ?><a class="btn btn-error" href="/Channel/index" style="margin-left: 20px;">返回</a></td>
      </tr>
  </table>
</form>
<script type="text/javascript">
$(function(){
  $("#sub_add").click(function(){
    var province = $("select[name='province']").find("option:selected").attr("is_select");
    var coty = $("select[name='city']").find("option:selected").attr("is_select");
    if((province != 1) || (coty != 1))
    {
      return top.jdbox.alert(0,"请选择省份和城市");
    }
    var data = {};
    data.channel_belong = $("input[name='channel_belong']").attr('pid');
    data.channel_name = $("input[name='channel_name']").val();
    data.province = $("select[name='province']").find("option:selected").html();
    data.city = $("select[name='city']").find("option:selected").html();
    data.address = $("input[name='address']").val();
    data.email = $("input[name='email']").val();
    data.legalname = $("input[name='email']").val();
    data.mobile = $("input[name='mobile']").val();
    data.channel_level = $("select[name='channel_level']").find("option:selected").attr('v');
    data.bank_name = $("input[name='bank_name']").val();
    data.bank_accno = $("input[name='bank_accno']").val();
    data.bank_branch = $("input[name='bank_branch']").val();
    data.bank_account = $("input[name='bank_account']").val();
    data.channelid = $("input[name='channelid']").val(); 
    data.status = $("input[name='status']:checked").val();
    top.jdbox.alert(2);
    $.post("/Channel/edit.html",data,function(F){
        console.log(F);
        top.jdbox.alert(F.status,F.info);
        if(F.status)
        {
          window.location.href = "/Channel/index";
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