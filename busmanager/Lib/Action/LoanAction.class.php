<?php
/**
* 产品管理
*/
class LoanAction extends CommonAction
{
	protected function _initialize()
	{
		$params['class'] = "LoanPeriode";
		$params['method'] = "getLoanPeriodeList";
		$res = $this->invoke($params);//dump($res);
		$this->assign("periode",$res['data']);
	}
	//产品列表
	public function index()
	{
		$loan_name = $this->_get("loan_name",'trim');
		$status = $this->_get("status","trim");
		$map = [];
		if ($status)$map['status'] = $status;
		if ($loan_name)$map['name'] = $loan_name;
		$map['busCompanyId'] = $_SESSION['user']['businessid'];//dump($map);
		$params['class'] = "BusinessLoan";
		$params['method'] = "getAllBusinessLoanList";
		$params['data']['params']['where'] = $map;
	    $res = $this->invoke($params);//dump($res);
	    $this->assign("status",['0'=>"全部",'1'=>'启用','2'=>'停用']);
	    $this->assign("list",$res['data']);
		$this->display();
	}
	//商户产品详情
	public function info()
	{
		$loan_id = $this->_get('id',"trim");
		$params['class'] = "BusinessLoan";
		$params['method'] = "getOneBusinessLoan";
		$params['data']['loan_id'] = $loan_id;
		$res = $this->invoke($params);//dump($res);
		$this->assign("info",$res['data']);
 		$this->display();
	}
	//资金方产品明细
	public function detail()
	{
		$id = $this->_get("id","trim");
		$params['class'] = "CapitalLoan";
		$params['method'] = "getOneCapitalLoan";
		$params['data']['id'] = $id;
		$res = $this->invoke($params);//dump($res);
		$this->assign("info",$res['data']);
		$this->assign("pay_type",$this->payType());
		$this->display();
	}
	//获取还款方式
	public function payType()
	{
		$params['class'] = 'CapitalLoan';
		$params['method'] = 'getLoanRepayment';
		return $this->invoke($params)['data'];
	}
}