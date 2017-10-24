<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
<title>页面提示</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="_STATIC_/2017/public/js/jquery-1.10.2.min.js"></script>
<style>
html, body{margin:0; padding:0; border:0 none;font:14px Tahoma,Verdana;line-height:150%;background:white}
a{text-decoration:none; color:#174B73; border-bottom:1px dashed gray}
a:hover{color:#F60; border-bottom:1px dashed gray}
div.message{margin:30px auto;clear:both;padding:5px;text-align:center; width:45%}
span.wait{color:blue;font-weight:bold}
span.error{color:red;font-weight:bold}
span.success{color:blue;font-weight:bold}
div.msg{margin:20px 0px}
</style>
<script type="text/javascript">
	$waitSecond = "<?php echo ($waitSecond); ?>";
	if($waitSecond == 1)window.location.href = '/login.html';
</script>
</head>
<body>
<div><span>提醒</span></div>
<div class="message">
	<div class="msg">
	<?php if(isset($message)): ?><span class="success"><?php echo ($msgTitle); ?><br/><?php echo ($message); ?></span>
	<?php else: ?>
	<span class="error"><?php echo ($msgTitle); ?><br/><?php echo ($error); ?></span>
	<input type="hidden" name="waitSecond" value="<?php echo ($waitSecond); ?>"><?php endif; ?>
	</div>
</div>

</body>
</html>