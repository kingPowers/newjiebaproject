<?php
/**
* 交易管理
*/
class DealAction extends CommonAction
{
	public function _initialize()
	{
		$params['class'] = "LoanOrder";
		$params['method'] = "getTenderStatusList";
		$this->assign("status",array_merge($this->invoke($params)['data'],["4"=>"已结清"]));
	}
	public function index()
	{
		//dump($_GET);
		$p = $_REQUEST['p']?$_REQUEST['p']:1;
		$starttime = $this->_get("starttime","trim");
		$endtime = $this->_get("endtime","trim");
		$names = $this->_get("names","trim");
		$mobile = $this->_get("mobile","trim");
		$status = $this->_get("status","trim");
		$orderStatus = $this->_get("orderStatus","trim");
		$page_num = 10;
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
		
		$map['channelCompanyId'] = $_SESSION['user']['channelid'];
		$params['class'] = "LoanOrder";
		$params['method'] = "getAllTenderOrderList";
		$params['data']['params']['where'] = $map;
		$params['data']['page'] = $p;
		$params['data']['number'] = $page_num;
		$res = $this->invoke($params);//dump($map);
		//dump($res);
		$this->page['no'] = $p;
        $this->page['num'] = $page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Deal/index/{$pagepa}/p/*.html");
		$this->assign("list",$res['data']['list']);
		$this->display();
	}
}