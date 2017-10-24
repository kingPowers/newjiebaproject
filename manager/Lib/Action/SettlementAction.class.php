<?php
/**
* 清结算管理
*/
class SettlementAction extends CommonAction
{
	public function _initialize()
	{
		$params1['class'] = "ChannelCompany";
		$params1['method'] = "getAllChannelCompanyList";
		$channel = $this->invoke($params1);//dump($channel);
		$params2['class'] = "BusinessCompany";
		$params2['method'] = "allCompanyList";
		$params2['data'] = ['params'=>[],'page'=>-1];
		$business = $this->invoke($params2);//dump($business);
		$this->assign("business_list",$business['data']);
		$this->assign("channel_list",$channel['data']);
	}
	public function clear()
	{
		$p = $_REQUEST['p']?$_REQUEST['p']:1;
		$channel_name = $this->_get("channel_name","trim");
		$businessname = $this->_get("businessname","trim");
		$startTime = $this->_get("starttime","trim");
		$endTime = $this->_get("endtime","trim");
		$page_num = 10;
		$map = [];$pagepa = '';
		if ($channel_name && $channel_name!="全部") {
			$map['channelCompanyname'] = $channel_name;
			$pagepa .= "/channel_name/{$channel_name}";
		}
		if ($businessname && $businessname!="全部") {
			$map['businessCompanyname'] = $businessname;
			$pagepa .= "/businessname/{$businessname}";
		}
		if ($startTime && $endTime) {
			$map['startTime'] = $startTime;
			$map['endTime'] = $endTime;
			$pagepa .= "/startTime/{$startTime}/endTime/{$endTime}";
		}
		$params['class'] = "Clear";
		$params['method'] = "clearList";
		$params['data']['params']['where'] = $map;
		$params['data']['page'] = $p;
		$params['data']['number'] = $page_num;
		$res = $this->invoke($params);
		//dump($res);
		$this->page['no'] = $p;
        $this->page['num'] = $page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Settlement/clear/{$pagepa}/p/*.html");
		$this->assign("list",$res['data']['list']);
		$this->display();
	}
	public function profit()
	{
		$p = $_REQUEST['p']?$_REQUEST['p']:1;
		$channel_name = $this->_get("channel_name","trim");
		$businessname = $this->_get("businessname","trim");
		$startTime = $this->_get("starttime","trim");
		$endTime = $this->_get("endtime","trim");
		$page_num = 10;
		$map = [];$pagepa = '';
		if ($channel_name && $channel_name!="全部") {
			$map['channelCompanyname'] = $channel_name;
			$pagepa .= "/channel_name/{$channel_name}";
		}
		if ($businessname && $businessname!="全部") {
			$map['businessCompanyname'] = $businessname;
			$pagepa .= "/businessname/{$businessname}";
		}
		if ($startTime && $endTime) {
			$map['startTime'] = $startTime;
			$map['endTime'] = $endTime;
			$pagepa .= "/startTime/{$startTime}/endTime/{$endTime}";
		}
		$params['class'] = "Clear";
		$params['method'] = "clearProfitList";
		$params['data']['params']['where'] = $map;
		$params['data']['page'] = $p;
		$params['data']['number'] = $page_num;
		$res = $this->invoke($params);
		//dump($res);
		$this->page['no'] = $p;
        $this->page['num'] = $page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Settlement/profit/{$pagepa}/p/*.html");
		$this->assign("data",$res['data']);
		$this->display();
	}
}