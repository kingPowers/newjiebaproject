<?php

/**
* 
*/
class Login 
{
	public $allow_group = [];
	public $error = '';
	public function __construct()
	{
		$groups = M("auth_group")->where("status=1")->select();
		foreach ($groups as $value) {
			$this->allow_group[] = $value['id'];
		}
	}
	public function doLogin($data)
	{
		$this->error = "请填写完整信息";
            return false;
        $username = $data['username'];
        $password = $data['password'];
        $verify = $data['verify'];
        $auth_group = $data['auth_group'];
        if (empty($username) || empty($password) || empty($verify))
        {
            $this->error = "请填写完整信息";
            return false;
        }
        if (!$this->verifyCheck($verify)) {
            $this->error = '验证码不正确';
            return false;
        }
        $info = M('user')->where("username='{$username}'")->find();
        if (empty($info))
        {
            $this->error = "该用户不存在";
            return false;
        }
        if ($info['status'] != 1)
        {
            $this->error = "该账号已禁用";
            return false;
        }
        if ($info['password'] != gen_password($password))
        {
            $this->error = "密码错误";
            return false;
        }
        if ((in_array($info['groupid'],$this->allow_group)) && $auth_group && ($info['groupid'] != $auth_group))
        {
            $this->error = "请选择正确的用户分组";
            return false;
        }
            
        $_token_ = $this->genToken($info['id'],$info['username'],$info['mobile']);
        try{
            if (!D('common')->inTrans())
            {
                D('common')->startTrans();
                $trans = true;
            } 
            $saveData = array();
            $saveData['lasttime'] = date("Y-m-d H:i:s",time());
            $saveData['lastip'] = get_client_ip();
            if (false == M("user")->where("username='{$username}'")->save($saveData))
            {
                throw new Exception("登录失败");
            }
            if (false == M('user')->where("username='{$username}'")->setInc('logincount'))
            {
                throw new Exception("登录失败");
            }
            if (false == $this->saveToken($_token_,$info))
            {
                throw new Exception("登录失败，请稍后再试");
            } 
            if ($trans)
            {    
                D('common')->commit(); 
                return ['username'=>$username,'_token_'=>$_token_];   
            }
        } catch (Exception $ex) {
            if ($trans)
            {
                D('common')->rollback();
                $this->error($ex->getMessage());
            }
        }       
	}
	 //验证验证码
    public function verifyCheck($verify) {
        //return session('verify')."-".md5($verify);
        return session('verify') == md5($verify) ? true : false;
        return true;
    }
	//生成token
    private function genToken($userid,$username,$mobile)
    {
        return md5($userid.$username.$mobile.C("SECURE_KEY").time());
    }
    //保存token数据入库
    private function saveToken($_token_,$info)
    {
        $old_info = M("user_token")->where("userid={$info['username']}")->find();
        $update_info = array();
        $update_info['token'] = $_token_;
        $update_info['username'] = $info['username'];
        $update_info['logintime'] = date("Y-m-d H:i:s",time());
        if ($old_info)
        {
            S('token_'.$old_info['token']."_info",null);
            $res = M('user_token')->where("username='{$info['username']}'")->save($update_info);
        } else {
            $update_info['userid'] = $info['id'];
            $res = M('user_token')->add($update_info);
        }
        if($res)
        {
            S("token_".$_token_."_info",$info,60*60*24);
        }   
        return $res;
    } 
    public function getError()
    {
       return $this->error;
    }
}