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
<?php $group='一级导航'; ?>
<form id='menuForm' class="definewidth m20">
  <input type="hidden" name="groupid" value="<?php echo ($groupid); ?>">
  <table class="table table-bordered table-hover m10">
    <tr>
       <td colspan="2" class="tableleft">
        <?php if(is_array($group_list)): foreach($group_list as $key=>$v): if(($groupid) == $v['id']): echo ($v['title']); ?>菜单添加<?php endif; endforeach; endif; ?>
      </td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">上级</td>
      <td><select name="pid">

      <option value="0">一级导航</option>
      <?php if(is_array($list)): foreach($list as $key=>$vo): if(($vo['id']) == $pid): $group=$vo['title'] ?>
      	<option value="<?php echo ($vo['id']); ?>" selected="selected"><?php echo ($vo['title']); ?></option>
      <?php else: ?>
        <option value="<?php echo ($vo['id']); ?>"><?php echo ($vo['title']); ?></option><?php endif; endforeach; endif; ?>
      </select></td>
    </tr>
    <tr>
      <td class="tableleft">名称</td>
      <td><input type="text" name="title" jschecktitle="名称" jscheckrule="null=0" value="<?php echo ($menu['title']); ?>"/></td>
    </tr>
    <tr>
      <td class="tableleft">分组</td>
      <td><input type="text" name="group" disabled="disabled" value="<?php echo ($group); ?>"/></td>
    </tr>
    <tr>
      <td class="tableleft">Model</td>
      <td><input type="text" name="module" jschecktitle="Model" jscheckrule="null=0" value="<?php echo ($menu['module']); ?>"/></td>
    </tr>
    <tr>
      <td class="tableleft">Action</td>
      <td><input type="text" name="action" <?php if(empty($menu['pid'])): ?>disabled="disabled"<?php endif; ?> jschecktitle="Action" value="<?php echo ($menu['action']); ?>"/></td>
    </tr>
    <tr >
      <td class="tableleft">菜单icon</td>
      <td><input class="menu_icon" id="menu_icon" type="file" name="icon"></td>
    </tr>
    <tr>
      <td class="tableleft">菜单icon预览</td>
      <td><img style="width: 200px;max-height: 100px; " class="icon_look"  src="_STATIC_/<?php echo ($menu['icon']); ?>"></td>
    </tr>
    <tr>
      <td class="tableleft">类型</td>
      <td><input type="radio" name="type" value="1"  <?php if(($menu['type']) == "1"): ?>checked="checked"<?php endif; ?> />菜单
        <input type="radio" name="type" value="2" <?php if(($menu['type']) == "2"): ?>checked="checked"<?php endif; ?> />功能 </td>
    </tr>
    <tr>
      <td class="tableleft">状态</td>
      <td><input type="radio" name="status" value="1" <?php if(($menu['status']) == "1"): ?>checked="checked"<?php endif; ?> />启用
        <input type="radio" name="status" value="0" <?php if(($menu['status']) == "0"): ?>checked="checked"<?php endif; ?> />禁用</td>
    </tr>
    <tr>
      <td class="tableleft"></td>
      <td>
      <input type="hidden" name="mid" value="<?php echo ($menu['id']); ?>">
      <button class="btn btn-primary" type="button"> 保存 </button>
        <button type="button" class="btn btn-success" name="backid" id="backid">返回列表</button></td>
    </tr>
  </table>
</form>
<script language="javascript">
var mid = "<?php echo ($menu['id']); ?>";
$(function(){
  $(".menu_icon").change(function () {
      var file=document.getElementById("menu_icon")
      var src = window.URL.createObjectURL(file.files[0]);                                    
      $(".icon_look").attr("src",src);
  })
	$('button.btn-primary').click(function(){
		var P = check_form('#menuForm');
		if(P){
			var data={};
      $(data).attr('groupid',$("input[name='groupid']").val());
      if(data.groupid == '') 
      {
         top.jdbox.alert(0,"分组不存在，请刷新！");
         return false;
      }
      var formData = new FormData($( "#menuForm" )[0]);
       $.ajax({  
            url: '/Menu/edit.html' ,  
            type: 'POST',  
            data: formData,  
            async: false,  
            cache: false,  
            contentType: false,  
            processData: false,  
            success: function (F) {  
                var F = eval("("+F+")"); 
                console.log(F);
                top.jdbox.alert(F.status,F.info);  
                if(F.status){
                   window.location.href = "/Menu/index";
                } 
            },  
            error: function (F) {  
               top.jdbox.alert(0,"操作失败，请稍后再试");
            }  
      });  
			// $(data).attr('mid',mid);
			// $(data).attr('pid',$("select[name='pid']").val());
			// $(data).attr('title',$("input[name='title']").val());
			// $(data).attr('module',$("input[name='module']").val());
			// $(data).attr('action',$("input[name='action']").val());
			// $(data).attr('type',$("input[name='type']:checked").val());
			// $(data).attr('status',$("input[name='status']:checked").val());
			// top.jdbox.alert(2);
			// $.post('/Menu/edit.html',data,function(F){
			// 	top.jdbox.alert(F.status,F.info);
			// 	if(F.status){
			// 		window.location.href = "/Menu/index";
			// 	}
			// },'json');
		}
	});
	$("select[name='pid']").change(function(){
		var self = $(this), parent = false;
    if ($(this).val() == 0) {
      $(".menu_icon").removeAttr("disabled").val('');
    } else {
      $(".menu_icon").attr("disabled","disabled").val('');
      $(".icon_look").attr("src",'');
    }
		$(this).find('option').each(function(){
			if( self.val() == $(this).val()){
				$("input[name='group']").val( $(this).html() );
				if(self.val()!=0){
					parent = true;
				}
			}
		});
		if(parent){
			$("input[name='action']").attr({disabled:false,jscheckrule:'null=0'});
		}else{
			$("input[name='action']").attr({disabled:true,jscheckrule:''});
		}
	});
	$('button#backid').click(function(){
		window.location.href= "<?php echo U('Menu/index');?>";
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