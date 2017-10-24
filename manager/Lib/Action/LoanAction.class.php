<?php
/**
* 贷款管理
*/
class LoanAction extends CommonAction
{
	private $page_num = 12;
	public function __construct()
	{
		parent::__construct();
		$params['class'] = "LoanOrder";
		$params['method'] = "getTenderStatusList";
		$this->assign("status",$this->invoke($params)['data']);
	}
	public function index()
	{
		$names = $this->_get('names',"trim");
		$channel = $this->_get('channel',"trim");
		$status = $this->_get("status","trim");
		$mobile = $this->_get("mobile","trim");
		$businessname = $this->_get("business","trim");
		$map = [];
		$pagepa = '';
		if ($names)
		{
			$map['names'] = $names;
			$pagepa .= "names/{$names}/";
		}
		if ($channel)
		{
			$map['chaCompanyname'] = $channel;
			$pagepa .= "channel/{$channel}/";
		}
		if ($status)
		{
			$map['status'] = $status;
			$pagepa .= "status/{$status}/";
		}
		if ($businessname)
		{
			$map['busCompanyname'] = $businessname;
			$pagepa .= "business/{$businessname}/";
		}
		if ($mobile)
		{
			$map['mobile'] = $mobile;
			$pagepa .= "mobile/{$mobile}";
		}
		$params['class'] = 'LoanOrder';
		$params['method'] = 'getAllTenderOrderList';
		$params['data']['params']['where'] = $map;
		$params['data']['page'] = $_REQUEST['p']?$_REQUEST['p']:1;
		$params['data']['number'] = $this->page_num;
		$res = $this->invoke($params);//dump($res);
		//设置分页
		$this->page['no'] = $params['data']['page'];
        $this->page['num'] = $this->page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Loan/index/{$pagepa}/p/*.html");
		$this->assign("list",$res['data']['list']);
		$this->display();
	}
	public function info()
	{
		$order_id = $this->_request("id");
		$memberid = $this->_request("mid");
		$order_id = $this->_get("id","trim");
		$info_params['class'] = "LoanOrder";
		$info_params['method'] = "getAllTenderOrderList";
		$info_params['data']['params']['where'] = ['id'=>$order_id];
		$info_res = $this->invoke($info_params);//dump($info_res['data']['list'][0]);
		$this->assign("info",$info_res['data']['list'][0]);
		$plan_params['class'] = 'LoanOrder';
		$plan_params['method'] = 'getAllTenderOrderList';
		$plan_params['data']['params']['where']['id'] = $order_id;
		//$plan_params['data']['params']['where']['allotStatus'] = 0;
		$plan_res = $this->invoke($plan_params);//dump($plan_res);
		$this->assign("plan",$plan_res['data']['list'][0]);
		$his_params['class'] = 'LoanOrder';
		$his_params['method'] = 'getAllTenderOrderList';
		$his_params['data']['params']['where']['memberId'] = $memberid;
		$his_params['data']['params']['where']['allotStatus'] = 1;
		$his_res = $this->invoke($his_params);//dump($his_res);
		$this->assign("history",$his_res['data']['list']);
		$this->display();
	}
}