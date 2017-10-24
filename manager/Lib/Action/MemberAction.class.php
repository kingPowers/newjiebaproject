<?php
/**
* 用户管理
*/
class MemberAction extends CommonAction
{
	private $status = ['1'=>"新注册",'9'=>'冻结','3'=>'存量'];
	public function index()
	{
		$p = $_REQUEST['p']?$_REQUEST['p']:1;
		$channel_name = $this->_get("channel_name","trim");
		$businessname = $this->_get("businessname","trim");
		$names = $this->_get("names","trim");
		$mobile = $this->_get("mobile","trim");
		$status = $this->_get("status","trim");
		$page_num = 10;
		$map = [];$pagepa = '';
		if ($channel_name) {
			$map['chaCompanyname'] = $channel_name;
			$pagepa .= "/channel_name/{$channel_name}";
		}
		if ($businessname) {
			$map['busCompanyname'] = $businessname;
			$pagepa .= "/businessname/{$businessname}";
		}
		if ($names) {
			$map['names'] = $names;
			$pagepa .= "/names/{$names}";
		}
		if ($mobile) {
			$map['mobile'] = $mobile;
			$pagepa .= "/mobile/{$mobile}";
		}
		if ($status) {
			$map['status'] = $status;
			$pagepa .= "/status/{$status}";
		}
		$params['class'] = "Member";
		$params['method'] = "getAllMemberList";
		$params['data']['params']['where'] = $map;
		$params['data']['page'] = $p;
		$params['data']['number'] = $page_num;
		$res = $this->invoke($params);//dump($map);
		//dump($res);
		$this->page['no'] = $p;
        $this->page['num'] = $page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Member/index/{$pagepa}/p/*.html");
		$this->assign("list",$res['data']['list']);
		$this->assign("status",$this->status);
		$this->display();
	}
}