<?php
/**
* 逾期管理
*/
class OverdueAction extends CommonAction
{
	public $operate_status = ['0'=>"全部",'1'=>'未处理','2'=>'催款成功','3'=>'催款失败'];
	public function _initialize()
	{
		$params['class'] = "AllotUrge";
		$params['method'] = "getUrgeType";
		$this->assign("status",$this->invoke($params)['data']);
		$this->assign("operate_status",$this->operate_status);
	}
	//逾期贷款列表
	public function index()
	{
		$p = $_REQUEST['p']?$_REQUEST['p']:1;
		$names = $this->_get("names","trim");
		$mobile = $this->_get("mobile","trim");
		$status = $this->_get("status","trim");
		$page_num = 10;
		$map = [];$pagepa = '';
		if ($names) {
			$map['names'] = $names;
			$pagepa .= "/names/{$names}";
		}
		if ($mobile) {
			$map['mobile'] = $mobile;
			$pagepa .= "/mobile/{$mobile}";
		}
		if ($status) {
			$map['urgeStatus'] = $status;
			$pagepa .= "/status/{$status}";
		}
		$map['busCompanyId'] = $_SESSION['user']['businessid'];
		$map['isLate'] = 1;
		$params['class'] = "LoanOrder";
		$params['method'] = "getAllTenderOrderList";
		$params['data']['params']['where'] = $map;
		$params['data']['page'] = $p;
		$params['data']['number'] = $page_num;
		$res = $this->invoke($params);//dump($map);
		foreach ($res['data']['list'] as &$value) {
			$urgent_params['class'] = 'AllotUrge';
			$urgent_params['method'] = 'urgeResult';
			$urgent_params['data']['tender_id'] = $value['id'];
			$urgent_res = $this->invoke($urgent_params);
			$value['urgeStatus'] = $urgent_res['data'];
		}//dump($res);
		$this->page['no'] = $p;
        $this->page['num'] = $page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Overdue/index/{$pagepa}/p/*.html");
		$this->assign("list",$res['data']['list']);
		$this->display();
	}
	//催收
	public function operate()
	{
		if ($_POST && ($_POST['is_urgent'] == 1))$this->urgentOrder();
		$order_id = $this->_get("id","trim");
		$orderParams['class'] = 'LoanOrder';
		$orderParams['method'] = 'getAllTenderOrderList';
		$orderParams['data']['params']['where']['id'] = $order_id;
		$orderRes = $this->invoke($orderParams);//dump($orderRes);
		$order_info = $orderRes['data']['list'][0];
		//催收结果
		$urgent_params['class'] = 'AllotUrge';
		$urgent_params['method'] = 'urgeResult';
		$urgent_params['data']['tender_id'] = $order_info['id'];
		$urgent_res = $this->invoke($urgent_params);
		$order_info['urgeStatus'] = $urgent_res['data'];
		//dump($orderRes);
		//催收历史
		$urgent_his['class'] = "AllotUrge";
		$urgent_his['method'] = "urgeList";
		$urgent_his['data'] = $order_info['id'];
		$his_res = $this->invoke($urgent_his);
		//dump($his_res);
		$this->assign("urgent_his",$his_res['data']);
	    $this->assign("order_info",$order_info);
		$this->display();
	}
	//提交催收结果
	public function urgentOrder()
	{
		$data['tender_id'] = $this->_post("order_id","trim");
		$data['allot_id'] = $this->_post("allot_id","trim");
		$data['content'] = $this->_post("remark","trim");
		$data['urge_type'] = $this->_post("result","trim");
		$data['author'] = $_SESSION['user']['names'];
		$params['class'] = 'AllotUrge';
		$params['method'] = 'addUrge';
		$params['data']['data'] = $data;
		$res = $this->invoke($params);
		if ($res['respCode'] === 0) {
			$this->operateToDb(1,$_SESSION['user']['id'],"处理催收订单【".$data['tender_id']."】");
			$this->ajaxSuccess("处理成功");
		}
		$this->ajaxError($res['respMsg']);

	}
}