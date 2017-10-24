<?php
/**
* 资金管理
*/
class FundAction extends CommonAction
{
	public function businessFund()
	{
		$map[] = '';
		$channelname = $this->_get("channelname","trim");
		$businessname = $this->_get("businessname","trim");
		if ($channelname) {
			$map['capCompanyname'] = $channelname;
		}
		if ($businessname) {
			$map['busCompanyname'] = $businessname;
		}
		$params['class'] = 'Capital';
		$params['method'] = 'getAllCapitalList';
		$params['data']['params']['where'] = $map;
		$res = $this->invoke($params);//dump($res);
		$this->assign('list',$res['data']);
		$this->display();
	}
	public function addBusinessDeposit()
	{
		//$this->error(json_encode($_POST));
		if($_POST && isset($_POST['capitalid']))$this->businessDepositToDb();
		$this->display();
	}
	public function businessDepositInfo()
	{	
		$businessid = $this->_get("bid","trim");
		$capitalid = $this->_get("cid","trim");
		$parmas['class'] = "Capital";
		$parmas['method'] = "getCapitalDetail";
		$parmas['data']['businessCompanyId'] = $businessid;
		$parmas['data']['capitalCompanyId'] = $capitalid;
		$res = $this->invoke($parmas);//dump($res);
		$this->assign("list",$res['data']);
		$this->display();
	}
	public function businessDepositToDb()
	{
		$data['capital_company_id'] = $this->_post("capitalid","trim");
		$data['business_company_id'] = $this->_post("businessid","trim");
		$data['first_credit_money'] = $this->_post("award_limit","trim");
		$data['promise_money'] = $this->_post("deposit","trim");
		$data['author'] = $_SESSION['user']['names'];
		$params['class'] = 'Capital';
		$params['method'] = 'addCapital';
		$params['data']['data'] = $data;
		$res = $this->invoke($params);
		if ($res['respCode'] === 0)
		{
			$this->operateToDb(1,$_SESSION['user']['id'],"新增合作公司保证金信息");
			$this->ajaxSuccess("操作成功");
		}
		$this->ajaxError($res['respMsg']);
	}
	public function managerFund()
	{
		$this->display();
	}
	public function addManagerDeposit()
	{
		if($_POST && $_POST['channelid'])$this->managerDepositToDb();
		$this->display();
	}
	public function managerDepositToDb()
	{

	}
}