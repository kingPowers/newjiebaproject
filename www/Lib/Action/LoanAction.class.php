<?php 
/**
* 借款处理
*/
class LoanAction extends CommonAction
{	
	public $status_url = ["待审核"=>"","待签约"=>"/Loan/check_sign","待打款"=>"","待还款"=>"/Loan/loan_info/s/1","已结清"=>"/Loan/loan_info/s/2"];
	public function index()
	{
		header("content-type:text/html;charset=utf-8");
		$parmas['cmd'] = "LoanOrder/getBusinessLoanList";
        $server_res = $this->service($parmas);
        $apply_par['cmd'] = "LoanOrder/apply";
        $apply_res = $this->service($apply_par);
        //dump($apply_res);
        $this->assign("allow_money",$apply_res['dataresult']['quota']);
        //dump($server_res);
        $this->assign("loan_list",$server_res['dataresult']);
        $this->assign("_loanapply_",$this->set_session("_loanapply_"));
		$this->display();
	}
	public function apply()
	{
		if (!$this->valid_session("_loanapply_"))$this->ajaxError("页面失效，请刷新重试");
		$parmas['cmd'] = "LoanTender/addLoanTender";
		$parmas['loanId'] = $this->_post("pro_id","trim");
		$parmas['money'] = $this->_post("loanmoney","trim");
		$server_res = $this->service($parmas);
		if ($server_res['respcode'] === 0) {
			unset($_SESSION['_loanapply_']);
			$this->ajaxSuccess("提交成功，请等待审核");
		}
		$this->ajaxError($server_res['respmsg']);
	}
	public function loan_info()
	{
		$loan_status = $this->_get("s","trim");
		$parmas['cmd'] = "LoanOrder/getMyTenderList";
		$parmas['tenderId'] = $this->_get("id","trim");
		$server_res = $this->service($parmas);//dump($server_res);
		$this->assign("info",$server_res['dataresult']['list'][0]);
		$this->assign("member_info",$this->member_info);
		$this->display();
	}
	public function repayment()
	{
		$parmas['cmd'] = "LoanOrder/orderRepayment";
		$parmas['tenderId'] = $this->_post("id","trim");
		$server_res = $this->service($parmas);//dump($server_res);
		if ($server_res['respcode'] === 0) {
			$this->ajaxSuccess("还款成功");
		}
		$this->ajaxError($server_res['respmsg']);
	}
	public function loan_sign()
	{
		$this->display();
	}
	public function check_sign()
	{
		if ($_POST && ($_POST['is_sign'] == 1)) {
			if (!$this->valid_session("_loansign_"))$this->ajaxError("页面失效，请刷新重试");
			$parmas['cmd'] = "LoanOrder/orderSign";
			$parmas['signCode'] = $this->_post("verify","trim");
			$parmas['tenderId'] = $this->_post("loan_id","trim");
			$server_res = $this->service($parmas);//dump($server_res);
			if ($server_res['respcode'] === 0) {
				unset($_SESSION['_loansign_']);
				$this->ajaxSuccess("签约成功");
			}
			$this->ajaxError($server_res['respmsg']);
		}
		$mobile = substr($this->member_info['mobile'],0,3)."****".substr($this->member_info['mobile'],-4);
		$this->assign("_loansign_",$this->set_session("_loansign_"));
		$this->assign("mobile",$mobile);
		$this->display();
	}
	public function signverify()
	{
		$loan_id = $this->_post("loan_id","trim");
		$parmas['cmd'] = "LoanOrder/orderSignSms";
		$parmas['tenderId'] = $loan_id;
		$server_res = $this->service($parmas);
		if ($server_res['respcode'] === 0) {
			$this->ajaxSuccess("验证码发送成功");
		}
		$this->ajaxError($server_res['respmsg']);
	}
	public function loan_history()
	{
		$p = $_REQUEST['p']?$_REQUEST['p']:1;
		$page_num = 10;
		$status = $_REQUEST['s'];
		if ($status) {
			$parmas['allotStatus'] = (int)($status-1);
		}
		$parmas['cmd'] = "LoanOrder/getMyTenderList";
		$parmas['page'] = $p;
		$parmas['number'] = $page_num;//dump($parmas);
		$server_res = $this->service($parmas);
		$loan_list = $server_res['dataresult']['list'];	
		foreach ($loan_list as &$value) {
			$value['timeadd'] = date("Y-m-d",strtotime($value['timeadd']));
			$value['jumpurl'] = $this->status_url[$value['order_status_name']];
		}
		if ($_POST && ($_POST['is_ajax'] == 1)) {
			if (empty($loan_list)) $this->ajaxError("没有更多的订单了");
			$this->ajaxSuccess("加载成功",$loan_list);
		}
		//dump($loan_list);
		$this->assign("list",$loan_list);
		$this->display();
	}
}
