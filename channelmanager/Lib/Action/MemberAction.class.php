<?php
/**
* 用户管理
*/
class MemberAction extends CommonAction
{
	
	public function index()
	{
		$user_info = $this->getUserInfo();
		$businessname = $this->_get("businessname","trim");
		if ($businessname) {
			$map['companyname'] = $businessname;
		}
		//dump($user_info);
		$map['channel_company_id'] = $user_info['channelid'];
		$params['class'] = "BusinessCompany";
		$params['method'] = "allCompanyList";
		$params['data']['params']['where'] = $map;
		$res = $this->invoke($params);//dump($res);
		$this->assign("list",$res['data']['list']);
		$this->display();
	}
}