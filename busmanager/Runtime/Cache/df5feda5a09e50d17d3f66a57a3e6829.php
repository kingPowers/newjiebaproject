<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" href="_STATIC_/manager/css/index.css">
<style type="text/css">
	ul{list-style: none;}
	.w98{width: 1200px; margin:0 auto;}
	.info_list li{width: 45%;}
</style>
<input type="hidden" value="<?php echo ($id); ?>" id="hiddenid">
<input type="hidden" value="<?php echo ($mobile); ?>" id="hiddenmobile">
<input type="hidden" value="<?php echo ($names); ?>" id="hiddennames">
<div id="content" style="overflow:hidden; width: 1250px; ">  
	<div class="w98 bgf">
		<div class="tit_box">
			<div class="t_box">			
				<span>产品配置</span>
			</div>
			<ul class="info_list">
				<li>产品名称：<?php echo ($info['name']); ?></li>
				<li>备注：<?php echo ($info['remark']); ?></li>
				<li>借款金额：<?php echo ($info['min_loanmoney']); ?>元-<?php echo ($info['max_loanmoney']); ?>元</li>
				<li>借款期限：<?php echo ($info['min_loan_periode']); ?>-<?php echo ($info['max_loan_periode']); ?></li>
				<li>借款利率：<?php echo ($info['periode_rate']); ?>%(日利率)</li>
				<li>还款方式：<?php echo ($pay_type[$info['loan_repayment']]); ?></li>
				<li>是否需要芝麻信用：<?php if(($info['is_sesame_credit']) == "1"): ?>是<?php else: ?>否<?php endif; ?></li>
				<?php if(($info['is_sesame_credit']) == "1"): ?><li>芝麻信用分通过标准：<?php echo ($info['sesame_credit_score']); ?></li><?php endif; ?>
			</ul>		
		</div>
	</div>
</div>