<?php
/**
* 公共类
*/
class PublicAction extends Action
{
	
	public function index()
	{
		dump($_SESSION['_token_']);
        dump(S("token_{$_SESSION['_token_']}_info"));
        dump($_SESSION['user']);
	}
    public function doLogin() 
    {
        import("Think.ORG.Util.Login");
        $login = new Login();
        $data = $_POST;
        $data['auth_group'] = 2;
        if (false == ($log_res = $login->doLogin($data)))
        {
            //redirect("http://jiemanager.lingqianzaixian.com/Public/login/err/".$login->getError());
            $this->ajaxReturn('',$login->getError(),0);
        }
        $_SESSION['_token_'] = $log_res['_token_'];
        $_SESSION['user'] = S("token_".$log_res['_token_']."_info");
        $this->operateToDb(1,$_SESSION['user']['id'],"用户登录",1);
        //redirect("/#64/65");
        $this->ajaxReturn(['jumpurl'=>$log_res['jumpurl']],"登录成功",1);

    }
    /*
    用户操作入库
    $res:操作结果：1：成功；0：失败；
    $userid:用户id
    $remark:操作内容
    $type:操作类型：1：系统管理；2：其他
     */
    public function operateToDb($res,$userid,$remark,$type=2)
    {
        $data = array();
        $data['status'] = $res?1:0;
        $data['userid'] = $userid;
        $data['remark'] = $remark;
        $data['type'] = $type;
        $data['ip'] = get_client_ip();
        $data['timeadd'] = date("Y-m-d H:i:s");
        foreach ($data as $value)
        {
            if(empty($value))return false;
        }
        return M("user_operate")->add($data);
    }
    //验证验证码
    public function verifyCheck($verify) 
    {
        return $_SESSION['verify'] == md5($verify) ? true : false;
        return true;
    }
	//退出登录
    public function logOut()
    {
        if (!$this->isLogin())$this->ajaxReturn('',"退出失败，您尚未登录",0);
        $saveData = array();
        $saveData['timeout'] = date("Y-m-d H:i:s",time());
        if (false == M('user')->where("id={$_SESSION['user']['id']}")->save($saveData))
        {
            $this->ajaxReturn('',"退出失败",0);
        }
        //R("/Common/operateToDb",array(1,$_SESSION['user']['id'],"用户退出登录",1));
        S("token_".$_SESSION['_token_']."_info",null);
        unset($_SESSION['_token_'],$_SESSION['user']);
        $this->ajaxReturn('',"退出成功",1);
    }
    //判断是否登录
    public function isLogin()
    {
        return $_SESSION['_token_'] && S("token_{$_SESSION['_token_']}_info");
    }
	//生成验证码
    public function verifyCode() {
        import('ORG.Util.Image');
        Image::buildImageVerify();
    }
    //获取菜单
    public function getJsMenu() {
        import("Think.ORG.Util.Auth");
        $auth = new Auth();
        $return = $auth->getMenuData($_SESSION['user']['id']);
        foreach ($return as $key=> &$re)
        {
            $is_unset = 1;
            foreach ($re['child'] as $child)
            {
                if($child['type'] == 1)
                {
                    $is_unset = 0;
                    break;
                }
            }
            if ($is_unset || empty($re['child']))unset($return[$key]);    
        }
        foreach ($return as $key => &$value) {
        	foreach ($value['child'] as &$child) {
            	$child['href'] = implode("/",explode("-",$child['name']));
            }
        }
        exit(json_encode($return));
    }
	public function recoverPwd()
	{
		$this->display();
	}
	public function recoverSuccess()
	{
		$this->display();
	}
}