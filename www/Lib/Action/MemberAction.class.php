<?php
/**
* 用户管理
*/
class MemberAction  extends CommonAction
{
	
	public function register()
	{       
        $redirect_url = urldecode($_REQUEST['redirecturl']);
        if(empty($_POST) && empty($_POST['is_register']) && true==$this->isLogin())$this->redirect($redirect_url?$redirect_url:"/member/account");
		if ($_POST && ($_POST['is_register'] == 1)) {
			if (!$this->valid_session("_register_"))$this->ajaxError("页面失效，请刷新重试");
			$parmas['cmd'] = "Member/register";
			$parmas['mobile'] = $this->_post("mobile","trim");
			$parmas['sms_code'] = $this->_post("verify",'trim');
			$parmas['password'] = $this->_post("password","trim");
			$parmas['repassword'] = $this->_post("repassword","trim");
			$service_res = $this->service($parmas);//dump($service_res);
			if ($service_res['respcode'] === 0) {
				unset($_SESSION['_register_']);
				$_SESSION['token'] = $service_res['dataresult']['token'];
				$_SESSION['member'] = $this->getMemberInfo();
				$this->ajaxSuccess("注册成功");
			}
			$this->ajaxError($service_res['respmsg']);
		}
		$this->assign("_register_",$this->set_session("_register_"));
		$this->display();
	}
	public function registerVerify()
	{
		$mobile = $this->_post("mobile","trim");
		$parmas['cmd'] = "Member/sendRegisterSms";
		$parmas['mobile'] = $mobile;
		$server_res = $this->service($parmas);
		//$this->success("dadsa");
		if ($server_res['respcode'] === 0) {
			$this->ajaxSuccess("获取验证码成功");
		}
		$this->ajaxError($server_res['respmsg']);
	}
	public function login()
	{
        $redirect_url = urldecode($_REQUEST['redirecturl']);
        if(empty($_POST) && empty($_POST['is_login']) && true==$this->isLogin())$this->redirect($redirect_url?$redirect_url:"/member/account");
		if ($_POST && ($_POST['is_login'] == 1)) {
			if (!$this->valid_session("_login_"))$this->ajaxError("页面失效，请刷新重试");
			$jump_url = $this->_post('redirect_url',"trim");
			$parmas['cmd'] = "Member/login";
			$parmas['mobile'] = $this->_post("mobile","trim");
			$parmas['password'] = $this->_post("password","trim");
			$server_res = $this->service($parmas);//dump($server_res);
			if ($server_res['respcode'] === 0) {
				unset($_SESSION['_login_']);
				$_SESSION['token'] = $server_res['dataresult']['token'];
				$_SESSION['member'] = $this->getMemberInfo();
				$this->ajaxSuccess("登录成功",['jump_url'=>$jump_url]);
			}
			$this->ajaxError($server_res['respmsg']);
		}
		$this->assign("redirect_url",$redirect_url);
		$this->assign("_login_",$this->set_session("_login_"));
		$this->display();
	}
	public function recoverpwd()
	{       
        if(true==$this->isLogin())$this->redirect("/member/account");
		if ($_POST && ($_POST['is_recover'] == 1)) {
			if (false == $this->valid_session("_recoverpwd_"))$this->ajaxError("页面失效，请刷新重试");
			$parmas['cmd'] = "Member/reSetPassword";
			$parmas['mobile'] = $this->_post("mobile","trim");
			$parmas['sms_code'] = $this->_post("verify","trim");
			$parmas['password'] = $this->_post("newpassword","trim");
			$server_res = $this->service($parmas);
			if ($server_res['respcode'] === 0) {
				unset($_SESSION['_recoverpwd_']);
				$this->ajaxSuccess("密码找回成功");
			}
			$this->ajaxError($server_res['respmsg']);
		}
		$this->assign("_recoverpwd_",$this->set_session("_recoverpwd_"));
		$this->display();
	}
	public function recoverVerify()
	{
		$mobile = $this->_post("mobile","trim");
		$parmas['cmd'] = "Member/reSetPasswordSms";
		$parmas['mobile'] = $mobile;
		$server_res = $this->service($parmas);
		if ($server_res['respcode'] === 0) {
			$this->ajaxSuccess("验证码发送成功");
		}
		$this->ajaxError($server_res['respmsg']);
	}
	public function resetpwd()
	{
		if ($_POST && ($_POST['is_reset'] == 1)) {
			if (!$this->valid_session("_resetpwd_"))$this->ajaxError("页面失效，请刷新重试");
			$parmas['cmd'] = "Member/changePassword";
			$parmas['old_password'] = $this->_post("password","trim");
			$parmas['password'] = $this->_post("newpassword","trim");
			$parmas['repassword'] = $this->_post("renewpassword","trim");
			$server_res = $this->service($parmas);
			if ($server_res['respcode'] === 0) {
				unset($_SESSION['_resetpwd_']);
				$this->ajaxSuccess("修改成功");
			}
			$this->ajaxError($server_res['respmsg']);
		}
		$this->assign("_resetpwd_",$this->set_session("_resetpwd_"));
		$this->display();
	}
	public function logOut()
	{
	   	 $params['cmd'] = 'Member/loginOut';	
	   	 $server_res = $this->service($params);
	   	 if(0===$server_res['respcode']){
	   	  	unset($_SESSION['token'],$_SESSION['member']);
	   	  	$this->ajaxSuccess($server_res['respmsg']);
	   	 } 
	   	 $this->ajaxError($server_res['respmsg']);
	}	
	public function account()
	{
		//dump($this->member_info);
		$this->assign("member_info",$this->member_info);
		$this->display();
	}
	public function bank_card()
	{
		//dump($this->member_info);
		$this->member_info['certiNumber'] = substr($this->member_info['certiNumber'],0,strlen($this->member_info['certiNumber'])-4)."****";
		$this->member_info['bank_mobile'] = substr($this->member_info['bank_mobile'],0,strlen($this->member_info['bank_mobile'])-4)."****";
		$this->assign("member_info",$this->member_info);
		$this->display();
	}
	public function appset()
	{
		$this->display();
	}
	public function aboutapp()
	{
		$this->display();
	}
}