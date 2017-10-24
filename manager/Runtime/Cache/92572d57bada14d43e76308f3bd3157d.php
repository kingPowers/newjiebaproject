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
<script type="text/javascript">
$(function(){
  $("select[name='group']").change(function(){
     var groupid = $(this).find("option:selected").attr("gid");
     switch (groupid)
     {
          case '1':admin(groupid);
              break;
          case '2':channel(groupid);
              break;
          case '3':business(groupid);
              break;
     }
  })
})
function admin(groupid)
{
  $("select[name='user_belong']").html("<option choseid='1'>管理员</option>");
  getAuthList(groupid);
}
function channel(groupid)
{
  $.post("/Public/getAllChannel",{},function(F){
    console.log(F);
    var channel_list = '';
    channel_list += "<option choseid=''>--请选择渠道--</option>"
    $.each(F.data,function(i,item){
        channel_list += "<option choseid='" + item.id +"'>" + item.companyname + "</option>";
    })
    $("select[name='user_belong']").html('').removeAttr("disabled").append(channel_list);
  },'json')
  getAuthList(groupid);
}
function business(groupid)
{
  $.post("/Public/getAllBusiness",{},function(F){
    console.log(F);
    var business_list = '';
    business_list += "<option choseid=''>--请选择商户--</option>"
    $.each(F.data,function(i,item){
        business_list += "<option choseid='" + item.id +"'>" + item.companyname + "</option>";
    })
    $("select[name='user_belong']").html('').removeAttr("disabled").append(business_list);
  },'json')
  //$("select[name='user_belong']").html("<option choseid='0'>管理员</option>");
  getAuthList(groupid);
}
function getAuthList(groupid)
{
  $.post("/Public/getAuthList",{"groupid":groupid},function(F){
      //console.log(F);
      if (F.status == 0)
      {
        top.jdbox.alert(0,F.info);
        return false;
      }
      var auth_str = '';
      $.each(F.data,function(i,item){
          auth_str += "<li><label class='checkbox inline'><input type='checkbox' disabled='disabled' />" + item.title +"</label><ul>";
          for (var key in item.child)
          {
            auth_str += "<li><label class='checkbox inline'><input type='checkbox'  name='node[]' value='" + item.child[key].id + "'/>" + item.child[key].title +"</label></li>";
          } 
          auth_str += "</ul></li>";
      })
      $(".auth_list").html('').append(auth_str);
  },'json')
}
</script>
<form id="roleForm" class="definewidth m20">
  <table class="table table-bordered table-hover definewidth m10">
    <tr>
      <td width="10%" class="tableleft">角色类型</td>
      <td>
        <select name="group">
              <option gid=''>请选择角色类型</option>
        <?php if(is_array($group_list)): foreach($group_list as $key=>$v): ?><option gid="<?php echo ($v['id']); ?>" <?php if(($v['id']) == $role['groupid']): ?>selected=''<?php endif; ?>><?php echo ($v['title']); ?></option><?php endforeach; endif; ?>  
        </select>
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">用户所属</td>
      <td>
        <select name="user_belong" disabled="">
              <?php if(empty($user_belong)): ?><option choseid=''>--所属--</option>
              <?php else: ?>
                <option choseid="<?php echo ($user_belong['id']); ?>"><?php echo ($user_belong['companyname']); ?></option><?php endif; ?>
              
        </select>(默认不修改)
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">角色名称</td>
      <td><input type="text" name="title" jschecktitle="角色名称" jscheckrule="null=0" value="<?php echo ($role['title']); ?>"/></td>
    </tr>
    <tr>
      <td class="tableleft">状态</td>
      <td><input type="radio" name="status" value="1" <?php if(($role['status']) == "1"): ?>checked="checked"<?php endif; ?>/> 启用 
      <input type="radio" name="status" value="0" <?php if(($role['status']) == "0"): ?>checked="checked"<?php endif; ?>/> 禁用 </td>
    </tr>
    <tr>
      <td class="tableleft">权限</td>
      <td>
      <ul class="auth_list">
          <?php if(is_array($list)): foreach($list as $key=>$vo): ?><li>
          	<label class='checkbox inline'><input type='checkbox' disabled="disabled" /><?php echo ($vo['title']); ?></label>
            <ul>
            <?php if(is_array($vo['child'])): foreach($vo['child'] as $key=>$v): ?><li><label class='checkbox inline'><input type='checkbox'  name='node[]' value="<?php echo ($v['id']); ?>" <?php if(in_array(($v['id']), is_array($role['rule'])?$role['rule']:explode(',',$role['rule']))): ?>checked="checked"<?php endif; ?>/><?php echo ($v['title']); ?></label></li><?php endforeach; endif; ?>
            </ul>
          </li><?php endforeach; endif; ?>
      </ul>
      </td>
    </tr>
    <tr>
      <td class="tableleft"></td>
      <td><button class="btn btn-primary" type="button">保存</button>
        <button type="button" class="btn btn-success" name="backid" id="backid">返回列表</button></td>
    </tr>
  </table>
</form>
<script language="javascript">
var rid = "<?php echo ($role['id']); ?>";
$(function(){
	$('button.btn-primary').click(function(){
    var group = $("select[name='group']").find("option:selected").attr("gid");
    if(group == '')return top.jdbox.alert(0,"请选择角色类型");
    var choseid = $("select[name='user_belong']").find("option:selected").attr("choseid");
		var data={},check=[];
		var p = check_form('#roleForm');
		if(p){
			$("input[name='node[]']").each(function(){
				if( $(this).is(':checked') ){
					check.push($(this).val());
				}
			});
		}
		$(data).attr('rid',rid);
		$(data).attr('title',$("input[name='title']").val());
		$(data).attr('status',$("input[name='status']:checked").val());
    $(data).attr('group',group);
    $(data).attr('choseid',choseid);
		$(data).attr('rule',check.join(','));
		top.jdbox.alert(2);
		$.post('/Role/edit.html',data,function(F){
			top.jdbox.alert(F.status,F.info);
			if(F.status){
				window.location.href="<?php echo U('Role/index');?>";
			}
		},'json');
	})
	$('button#backid').click(function(){
		window.location.href= "<?php echo U('Role/index');?>";
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