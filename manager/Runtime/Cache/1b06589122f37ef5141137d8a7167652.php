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
input{text-align: center;position: relative;top:3px;margin:0 5px;}
input[type='radio']{position: relative;top:-3px;}
.add-dec{font-size: 25px;margin-left: 20px;}
div{margin:10px 0;}
</style>
<script type="text/javascript">
$(function(){
  $(".add-stair").click(function(){
    var level = $(this).parent("div").prev(".stair").find(".stair-content:last").attr('level');//alert(level);
    var channelid = $(this).attr("cid");
    if(!level)level = 0;
    level = parseInt(level)+1;
    var add_str = '';
    add_str += "<div class='stair-content' level='" + level +"'>";
    add_str += level + "级阶梯：<input type='text' name='min_money' class='short'>——<input type='text' name='max_money' class='short'>";
    add_str += '<input type="radio" name="rule_type_' + channelid + '_' + level + '" onclick="chose_type(this);" value="1">按比例<input type="text" name="rule_percent" disabled="" class="short values">%';
    add_str += '<input type="radio" name="rule_type_' + channelid + '_' + level + '" onclick="chose_type(this);" value="2">按固定值<input type="text" name="rule_value" disabled="" class="short values">元/笔</div>';
    $(this).parent("div").prev(".stair").append(add_str);
  })
  $(".dec-stair").click(function(){
    if(false == (confirm("是否确认删除")))return false;
    var last_node = $(this).parent("div").prev(".stair").find(".stair-content:last");
    var ruleid = last_node.attr("ruleid");
    $.post("/BusinessCompany/editRule",{"ruleid":ruleid,"is_desc":1},function(F){
        console.log(F);
        top.jdbox.alert(F.status,F.info);
        if (F.status)
        {
          last_node.remove();
        }
    },'json')
  })
})
function chose_type(_this)
{
    $(_this).parent(".stair-content").find(".values").val("").attr("disabled","disabled");
    $(_this).next(".values").removeAttr("disabled");
}
</script>
<form class="definewidth m20" id="editForm">
  <input type="hidden" name="author" value="<?php echo ($_SESSION['user']['names']); ?>">
  <table class="table table-bordered table-hover m10">
    <thead>
      <tr>
        <th colspan="4" style="text-align: center;">新增规则</th>
      </tr>  
    </thead>
    <tr>
      <td width="10%" class="tableleft">合作公司名称：</td>
      <td colspan="3"><input type="hidden" name="company_id" value="<?php echo ($info['business']['id']); ?>"><?php echo ($info['business']['companyname']); ?></td>
    </tr>
    <?php if(is_array($info['channel'])): foreach($info['channel'] as $key=>$cv): ?><tr class="channel" cid="<?php echo ($cv['id']); ?>">
      <td colspan="4" style="padding-left: 50px;">
        <div>渠道(<?php echo ($cv['companyname']); ?>，级别：<?php echo ($cv['channel_level']); ?>级)</div>
        <div>拒单费用：<input type="text" name="refuse_fee"  <?php if(($is_sub) == "1"): ?>class="short"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> value="<?php echo ($cv['rule'][0]['refuse_money']); ?>">元/笔</div>
        <div class="stair">
           <?php if(is_array($cv['rule'])): foreach($cv['rule'] as $key=>$cs): ?><div class="stair-content" level="<?php echo ($cs['grade']); ?>" ruleid="<?php echo ($cs['id']); ?>">
              <?php echo ($cs['grade']); ?>级阶梯：
              <input type="text" name="min_money"  <?php if(($is_sub) == "1"): ?>class="short"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> value="<?php echo ($cs['min_money']); ?>">——<input type="text" name="max_money"  <?php if(($is_sub) == "1"): ?>class="short"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?> value="<?php echo ($cs['max_money']); ?>">
              <input type="radio" name="rule_type_<?php echo ($cv['id']); ?>_<?php echo ($cs['grade']); ?>" onclick="chose_type(this);" value="1" <?php if(($cs['rule_type']) == "1"): ?>checked=""<?php endif; ?>>按比例
              <input type="text" name="rule_percent" value="<?php echo ($cs['rule_percent']); ?>" <?php if(($cs['rule_type']) == "2"): ?>disabled=""<?php endif; ?>   <?php if(($is_sub) == "1"): ?>class="short values"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?>>%
              <input type="radio" name="rule_type_<?php echo ($cv['id']); ?>_<?php echo ($cs['grade']); ?>" onclick="chose_type(this);" value="2" <?php if(($cs['rule_type']) == "2"): ?>checked=""<?php endif; ?>>按固定值
              <input type="text" value="<?php echo ($cs['rule_value']); ?>" name="rule_value"  <?php if(($cs['rule_type']) == "1"): ?>disabled=""<?php endif; ?>    <?php if(($is_sub) == "1"): ?>class="short values"<?php else: ?>class="dis_input_short" disabled=""<?php endif; ?>>元/笔
            </div><?php endforeach; endif; ?>  
        </div>
        <?php if(($is_sub) == "1"): ?><div>
          增加/较少阶梯
          <a class="btn btn-primary add-dec add-stair" cid="<?php echo ($cv['id']); ?>">+</a><a class="btn btn-success add-dec dec-stair" cid="<?php echo ($cv['id']); ?>">-</a>
        </div><?php endif; ?>
      </td>
    </tr><?php endforeach; endif; ?>
    <tr>
      <td colspan="4" style="text-align: center;width: 50px">
        <?php if(!empty($is_sub)): ?><a class="btn btn-success" id="sub">提交</a><?php endif; ?>
          <a class="btn btn-error" href="/BusinessCompany/profitRuleList" style="margin-left: 20px;">返回</a>
      </td>
    </tr>
  </table>
</form>
<script type="text/javascript">
$(function(){
  $("#sub").click(function(){
    var businessid = $("input[name='company_id']").val();
    var authorid = $("input[name='author']").val();
    var data = {};
    var j = 0;
    $("table").find(".channel").each(function(i,item){ 
      var m = 0;
      var n = {};      
       $(item).find(".stair-content").each(function(ci,citem){
            k = {};
            k.author = authorid;
            k.business_company_id = businessid;
            k.channel_company_id = $(item).attr("cid");
            k.refuse_money = $(item).find("input[name='refuse_fee']").val();
            k.grade = $(citem).attr("level");
            k.min_money = $(citem).find("input[name='min_money']").val();
            k.max_money = $(citem).find("input[name='max_money']").val();
            k.rule_type = $(citem).find("input[type='radio']:checked").val();
            k.rule_percent = $(citem).find("input[name='rule_percent']").val();
            k.rule_value = $(citem).find("input[name='rule_value']").val();
            $(n).attr(m,k);
            m++;
       });
       $(data).attr(j,n);
       j++;
    })
    console.log(data);
    top.jdbox.alert(2);
    $.post("/BusinessCompany/editRule.html",{"data":data,'is_ajax':'1'},function(F){
        console.log(F);
        top.jdbox.alert(F.status,F.info);
        if (F.status)
        {
          window.location.href = "/BusinessCompany/profitRuleList";
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