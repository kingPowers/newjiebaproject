<?php
/**
* 贷款管理
*/
class OrderAction extends CommonAction
{
	public function _initialize()
	{
		$params['class'] = "LoanOrder";
		$params['method'] = "getTenderStatusList";
		$this->assign("status",array_merge($this->invoke($params)['data'],["4"=>"已结清"]));
	}
	//贷款列表
	public function index()
	{
		$p = $_REQUEST['p']?$_REQUEST['p']:1;
		$starttime = $this->_get("starttime","trim");
		$endtime = $this->_get("endtime","trim");
		$names = $this->_get("names","trim");
		$mobile = $this->_get("mobile","trim");
		$status = $this->_get("status","trim");
		$orderStatus = $this->_get("orderStatus","trim");
		$page_num = 7;
		$map = [];$pagepa = '';
		if ($starttime) {
			$map['startTime'] = $starttime;
			$pagepa .= "/starttime/{$starttime}";
		}
		if ($endtime) {
			$map['endTime'] = $endtime;
			$pagepa .= "/endtime/{$endtime}";
		}
		if ($names) {
			$map['names'] = $names;
			$pagepa .= "/names/{$names}";
		}
		if ($mobile) {
			$map['mobile'] = $mobile;
			$pagepa .= "/mobile/{$mobile}";
		}
		if ($status && $status!=4) {
			$map['status'] = $status;
			$pagepa .= "/status/{$status}";
		}
		if ($status && $status==4) {
			$map['orderStatus'] = 3;
			$pagepa .= "/orderStatus/{$orderStatus}";
		}
		$map['busCompanyId'] = $_SESSION['user']['businessid'];
		$params['class'] = "LoanOrder";
		$params['method'] = "getAllTenderOrderList";
		$params['data']['params']['where'] = $map;
		$params['data']['params']['order'] = "tender.status asc,tender.timeadd asc,tender.is_approve asc,tender.is_sign asc,allot.status asc";
		$params['data']['page'] = $p;
		$params['data']['number'] = $page_num;
		$res = $this->invoke($params);//dump($map);
		//dump($res);
		$this->page['no'] = $p;
        $this->page['num'] = $page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Order/index/{$pagepa}/p/*.html");
		$this->assign("list",$res['data']['list']);
		$this->display();
	}
	//审核订单
	public function checkOrder()
	{
		if ($_POST && ($_POST['is_check'] == 1))$this->check();
		$order_id = $this->_get("id","trim");
		$params['class'] = "LoanOrder";
		$params['method'] = "getAllTenderOrderList";
		$params['data']['params']['where'] = ['id'=>$order_id];
		$res = $this->invoke($params);//dump($res);
		$this->assign("info",$res['data']['list'][0]);
		$this->display();
	}
	//提交审核结果
	public function check()
	{
		$order_id = $this->_post("order_id","trim");
		$check_result = $this->_post("check_result","trim");
		$remark = $this->_post("remark",'trim');
		$method = $check_result?"orderPass":"orderDeny";
		if (!in_array($check_result,['0','1']))
			$this->ajaxError("请选择审核结果");
		$params['class'] = "LoanOrder";
		$params['method'] = $method;
		$params['data']['tenderId'] = $order_id;
		$params['data']['params']['remark'] = $remark;
                $params['data']['params']['author'] = $_SESSION['user']['names'];
		$res = $this->invoke($params);
		if ($res['respCode'] === 0) {
			$this->operateToDb(1,$_SESSION['user']['id'],"审核订单【" . $order_id . "】");
			$this->ajaxSuccess("审核成功");
		}
		$this->ajaxError($res['respMsg']);
	}
	//贷款详情
	public function orderInfo()
	{
		$order_id = $this->_get("id","trim");
		$params['class'] = "LoanOrder";
		$params['method'] = "getAllTenderOrderList";
		$params['data']['params']['where'] = ['id'=>$order_id];
		$res = $this->invoke($params);//dump($res);
		$this->assign("info",$res['data']['list'][0]);
		$this->display();
	}
	//贷款审核结果
	public function checkResult()
	{
		$order_id = $this->_get("id","trim");
		$params['class'] = "LoanOrder";
		$params['method'] = "getAllTenderOrderList";
		$params['data']['params']['where'] = ['id'=>$order_id];
		$res = $this->invoke($params);
		$this->assign("info",$res['data']['list'][0]);
		$this->display();
	}
	//还款计划
	public function repayPlan()
	{
		$loadid = $this->_request("id");
		$orderParams['class'] = 'LoanOrder';
		$orderParams['method'] = 'getAllTenderOrderList';
		$orderParams['data']['params']['where']['id'] = $loadid;
		//$orderParams['data']['params']['where']['allotStatus'] = 0;
		$orderRes = $this->invoke($orderParams);//dump($orderRes);
		$this->assign("info",$orderRes['data']['list'][0]);
		$this->display();
	}
	//还款历史
	public function repayHistory()
	{
		$memberid = $this->_get("mid","trim");
		$p = $_REQUEST['p']?$_REQUEST['p']:1;
		$pagepa = '';$page_num = 10;
		$pagepa .= "id/{$_GET['id']}/mid/{$memberid}";
		$orderParams['class'] = 'LoanOrder';
		$orderParams['method'] = 'getAllTenderOrderList';
		$orderParams['data']['params']['where']['memberId'] = $memberid;
		$orderParams['data']['params']['where']['allotStatus'] = 1;
		$orderParams['data']['page'] = $p;
		$orderParams['data']['number'] = $page_num;
		$res = $this->invoke($orderParams);//dump($res);
		$this->page['no'] = $p;
        $this->page['num'] = $page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Order/repayHistory/{$pagepa}/p/*.html");
		$this->assign("list",$res['data']['list']);
		$this->display();
	}
	//放款
	public function payMoney()
	{
		$order_id = $this->_post("order_id","trim");
		$parmas['class'] = "LoanOrder";
		$parmas['method'] = "orderPay";
		$parmas['data']['tenderId'] = $order_id;
		$parmas['data']['parmas']['author'] = $_SESSION['user']['names'];
		$res = $this->invoke($parmas);
               // dump($res);exit;
		if ($res['respCode'] === 0) {
			$this->operateToDb(1,$_SESSION['user']['id'],"贷款订单【".$order_id."】放款");
			$this->ajaxSuccess("放款成功");
		}
		$this->ajaxError($res['respMsg']);
	}
	//拒单
	public function refuseOrder()
	{
		$order_id = $this->_post("order_id","trim");
		$parmas['class'] = "LoanOrder";
		$parmas['method'] = "orderDeny";
		$parmas['data']['tenderId'] = $order_id;
		$parmas['data']['parmas']['author'] = $_SESSION['user']['names'];
		$res = $this->invoke($parmas);
		if ($res['respCode'] === 0) {
			$this->operateToDb(1,$_SESSION['user']['id'],"贷款订单【".$order_id."】拒单");
			$this->ajaxSuccess("拒单成功");
		}
		$this->ajaxError($res['respMsg']);
	}
}