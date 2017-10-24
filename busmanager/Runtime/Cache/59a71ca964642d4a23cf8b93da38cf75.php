<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script type="text/javascript">var WWW = "_WWW_", STATIC = "_STATIC_", APP = "_APP_";</script>    
    <link rel="stylesheet" href="_STATIC_/2015/css/public.css">
    <link rel="stylesheet" href="_STATIC_/2015/css/reCSS.css">
    <link rel="stylesheet" href="_STATIC_/manager/box/wbox.css">
    <script type="text/javascript" src="_STATIC_/2015/js/wdate/WdatePicker.js"></script>
    <script src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>    
    <script src="_STATIC_/2015/js/public.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/manager/js/jquery.box.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/manager/js/wbox.js" type="text/javascript" charset="utf-8"></script>   
    <link rel="stylesheet" href="_STATIC_/2015/member/css/zdialog.css" />
    <link rel="stylesheet" href="_STATIC_/2015/member/css/reset.css" />
    <script type="text/javascript" src="_STATIC_/2015/member/js/tk.js"></script>
    <style type="text/css">
    </style>
<script type="text/javascript">
var conAlert = function(content,ensure,ldata,cancle = alerts)
{
    $.DialogByZ.Confirm({Title: "提示", Content: content,FunL:ensure,Ldata:ldata,FunR:cancle})
}
function alerts(){
      $.DialogByZ.Close();
}
</script>
 
<link rel="stylesheet" href="_STATIC_/manager/css/index.css">
<link rel="stylesheet" href="_STATIC_/2015/css/public.css">
<style>
	#content{width:1000px;padding:10px 0; margin: 0 auto;}
	body,ul{margin: 0; padding: 0;}
	ul{list-style: none;}
	img{display: inline-block;}
	input{width: 20px; height: 20px; display: inline-block; }
	.father{width: 600px; margin: 10px 0 0 10px; font-size: 14px;}
	.father li{line-height: 30px; width: 100%;}
	.item-1,.item-2,.class-4{margin-left: 25px;}	
	.btn1,.btn2{cursor: pointer;}
	.rolediv{width: 90%; height: 300px; border: 1px #ddd solid; background: #fcfcfc; border-radius: 10px; float: left; margin-left: 5px; overflow-y: auto;}	
	.btn_save{width: 240px; height: 38px; line-height: 38px; text-align: center; background: #40a7ff; color: #fff; font-size: 16px; border-radius: 10px; display: block; margin:40px auto;}
</style>
<div><span>新增角色</span></div>
<div id="content">
	<div class="selectdiv">
		<font>角色姓名:</font>
		<input type="text" name="role_name" value="<?php echo ($role['title']); ?>" class="input_text box_round w238">	
	</div>
	<div class="selectdiv">	
	<input type="hidden" name="type_id" value="<?php echo ($role['id']); ?>">	
		<font style="float: left;">角色权限:</font>
		<div class="rolediv">
			<ul class="father">
				
				<li class="class-1">
					<img src="_STATIC_/2015/box/ico_o.png" class="btn1">
					<input type="checkbox" class="checkbox-1">
					<img src="_STATIC_/2015/box/ico_bag.png">
					全部
					<?php if(is_array($auth_list)): foreach($auth_list as $key=>$vo): ?><ul class="class-2">
						<li class="item-1">
							<img src="_STATIC_/2015/box/ico_o.png" class="btn2">
							<input type="checkbox" class="checkbox-2">
							<img src="_STATIC_/2015/box/ico_bag.png">
							<?php echo ($vo['title']); ?>
							<ul class="class-3">								<?php if(is_array($vo['child'])): foreach($vo['child'] as $key=>$v): ?><li class="item-2">
									<input type="checkbox" name="rule[]" value="<?php echo ($v['id']); ?>" <?php if(in_array(($v['id']), is_array($role['rule'])?$role['rule']:explode(',',$role['rule']))): ?>checked="checked"<?php endif; ?> class="checkbox-3">
									<img src="_STATIC_/2015/box/ico_key.png">
									<?php echo ($v['title']); ?>
								</li><?php endforeach; endif; ?>
							</ul>
						</li><?php endforeach; endif; ?>						
					</ul>
				</li>   				
			</ul>
		</div>			
	</div>
	<a class="btn_save">保存</a>
</div>
<script type="text/javascript">
	$(".btn1").click(function(){	 		
        var parentIndex=$(this).parent().index()
        $(".father .class-2").eq(parentIndex).toggle()            
    })
    $(".btn2").click(function(){
        $(this).parent().children(".class-3").toggle()
    })
    $(".btn3").click(function(){
        $(this).parent().children(".class-4").toggle()
    })
   
    $(".checkbox-1").click(function(){
        var parentIndex=$(this).parent().index();
        var isChecked = $(this).prop("checked");
        $(".father .class-2").eq(parentIndex).find("input").prop("checked", isChecked);
    })
    $(".checkbox-2").click(function(){
        var parentIndex=$(this).parent().index();
        var isChecked = $(this).prop("checked");
        $(this).parent().find("input").prop("checked", isChecked);
    })   
     $(".checkbox-3").click(function(){
        var parentIndex=$(this).parent().index();
        var isChecked = $(this).prop("checked");
        $(this).parent().find("input").prop("checked", isChecked);
    })
    $(".btn_save").click(function(){
    	var check = [],data = {};
    	var role_name = $("input[name='role_name']").val();
    	$("input[name='rule[]']").each(function(){
			if( $(this).is(':checked') ){
				check.push($(this).val());
			}
		});
		$(data).attr("type_id",$("input[name='type_id']").val());
		$(data).attr("rule",check.join(','));
		$(data).attr("role_name",role_name);
		$(data).attr("is_save",1);
		$.post("/Auth/roleEdit",data,function(F){
			console.log(F);
			if (F.status) {
				top.jdbox.alert(F.status,F.info)
			} else {
			    top.jdbox.alert(F.status,F.info,'',1);
			}
		},'json')

    })       
</script>