<?php
/**
* 分润管理
*/
class ProfitAction extends CommonAction
{
	
	public function index()
	{
		$starttime = $this->_get("starttime","trim");
		$endtime = $this->_get("endtime","trim");
		$businessname = $this->_get("businessname","trim");
		$map = [];$pagepa = '';
		if ($starttime) {
			$map['startTime'] = $starttime;
		}
		if ($endtime) {
			$map['endTime'] = $endtime;
		}
		if ($businessname) {
			$map['businessCompanyname'] = $businessname;
		}
		$map['channelCompanynameId'] = $_SESSION['user']['channelid'];
		$params['class'] = "Clear";
		$params['method'] = "clearProfitList";
		$params['data']['params']['where'] = $map;
		$res = $this->invoke($params);//dump($map);
		//dump($res);
		$this->assign("data",$res['data']);
		$this->display();
	}
}