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
<script language="javascript">
var uid = "<?php echo ($uid); ?>";
$(function(){
  $("select[name='user_belong']").change(function(){
     var company_id = $(this).find("option:selected").val();
     var groupid = $("input[name='groupid']").val();
      if(company_id == '')return false;
      getUserType(company_id,groupid);
  })
})
function getUserType(company_id,groupid)
{
  $.post("/User/edit",{"company_id":company_id,"groupid":groupid,"is_type":1},function(F){
      console.log(F);
      var user_str = '';
      var base_str = '';
      base_str = (!F.data)?"<option>--该公司尚未创建角色--</option>":"<option>--请选择用户角色--</option>";
      $("select[name='typeid']").html(base_str);
      if (!F.data)$("select[name='typeid']").attr("disabled","disabled");
      $.each(F.data,function(i,item){
        user_str += "<option value='" + item.id + "'>" + item.title +"</option>";
      })
      $("select[name='typeid']").removeAttr("disabled").append(user_str);
  },'json')
}
</script>
<form id="userForm" class="definewidth m20">
  <input type="hidden" name="groupid" value="<?php echo ($groupid); ?>">
  <table class="table table-bordered table-hover definewidth m10">
    <tr>
      <td style="text-align: center;" colspan="2" class="tableleft">
        <?php if(is_array($group_list)): foreach($group_list as $key=>$v): if(($v['id']) == $groupid): echo ($v['title']); endif; endforeach; endif; ?>
        用户
      </td>
    </tr>
    <tr>
      <td class="tableleft">所属公司：</td>
      <td>
        <select name="user_belong">
          <option>--请选择所属公司--</option>
          <?php if(is_array($company_list)): foreach($company_list as $key=>$vo): ?><option value="<?php echo ($vo['id']); ?>" <?php if(($vo['id']) == $user['company_id']): ?>selected=""<?php endif; ?> ><?php echo ($vo['companyname']); ?></option><?php endforeach; endif; ?>
        </select>
      </td>
    </tr>
    <tr>
      <td class="tableleft">角色：</td>
      <td>
        <select name="typeid" disabled="">
          <?php if(empty($user['typeid'])): ?><option >--请选择用户角色--</option>
          <?php else: ?>
            <option value="<?php echo ($user['typeid']); ?>"><?php echo ($user['title']); ?></option><?php endif; ?>
          
        </select>
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">账号</td>
      <td><input type="text" name="username"  jschecktitle="登录名" jscheckrule="null=0" value="<?php echo ($user['username']); ?>"/></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">姓名</td>
      <td><input type="text" name="names"  jschecktitle="用户姓名" jscheckrule="null=0" value="<?php echo ($user['names']); ?>"/></td>
    </tr>
    <tr>
      <td class="tableleft">密码</td>
      <td>
      <?php if(empty($uid)): ?><input type="password" name="password"  jschecktitle="密码" jscheckrule="null=0;minlencn=6;maxlencn=15" />
      <?php else: ?>
        <input type="password" name="password"  jschecktitle="密码" jscheckrule="minlencn=6;maxlencn=15" />
        <font color="#FF0000">留空不修改密码</font><?php endif; ?>
      </td>
    </tr>
    <tr>
      <td class="tableleft">手机号</td>
      <td><input type="text" name="mobile" value="<?php echo ($user['mobile']); ?>"  jschecktitle="手机号" jscheckrule="null=0;regexp=^1[3|4|5|8][0-9]{9}$" /></td>
    </tr>
    <tr>
      <td class="tableleft">状态</td>
      <td><input type="radio" name="status" value="1" <?php if(($user['status']) == "1"): ?>checked="checked"<?php endif; ?>/>启用
        <input type="radio" name="status" value="0" <?php if(($user['status']) == "0"): ?>checked="checked"<?php endif; ?>/>禁用 </td>
    </tr>
    <tr>
      <td class="tableleft"></td>
      <td><button class="btn btn-primary" type="button">保存</button>
        <button type="button" class="btn btn-success" name="backid" id="backid">返回列表</button></td>
    </tr>
  </table>
</form>
<script language="javascript">
$(function(){
	$('button.btn-primary').click(function(){

		var P = 1;//check_form('#userForm');    
		if(P){
			var data={};
			$(data).attr('uid',uid);
      $(data).attr('groupid',$("input[name='groupid']").val());
      $(data).attr('company_id',$("select[name='user_belong']").find("option:selected").val());
			$(data).attr('username',$("input[name='username']").val());
      $(data).attr('names',$("input[name='names']").val());
			$(data).attr('password',$("input[name='password']").val());
			$(data).attr('mobile',$("input[name='mobile']").val());
			//$(data).attr('type',$("input[name='type']:checked").val());
			$(data).attr('typeid',$("select[name='typeid']").find("option:selected").val());
			$(data).attr('status',$("input[name='status']:checked").val());
      $(data).attr('is_edit','1');
			top.jdbox.alert(2);
			$.post('/User/edit.html',data,function(F){
				top.jdbox.alert(F.status,F.info);
				if(F.status){
					window.location.href= "<?php echo U('User/index');?>";
				}
			},'json');
		}
	});
	$('button#backid').click(function(){
		window.location.href= "<?php echo U('User/index');?>";
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