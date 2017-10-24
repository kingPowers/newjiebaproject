<?php
/**
* 公共类
*/
class PublicAction extends Action
{
	
	public function index()
	{
        $parmas['class'] = "CustomerMessage";
        $parmas['method'] = "getCustomerRealTimeList";
        $parmas['data']['fromId'] = 8;
        $parmas['data']['toId'] = $_SESSION['user']['id'];
        $res = $this->invoke($parmas);dump($res);
		$this->display();
	}
    //登录
	public function doLogin()
	{
		import("Think.ORG.Util.Login");
		$login = new Login();
		$_POST['auth_group'] = 3;
		if (false == ($log_res = $login->doLogin($_POST)))
		{
			$this->ajaxReturn('',$login->getError(),0);
		}
		$_SESSION['_token_'] = $log_res['_token_'];
        $_SESSION['user'] = S("token_".$log_res['_token_']."_info"); 
		//$this->error("shibai");
		$this->operateToDb(1,$_SESSION['user']['id'],"用户登录",1);
        $this->ajaxReturn(['jumpurl'=>$log_res['jumpurl']],"登录成功");
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
    //用户在线定时刷新在线时间
    public function refreshTime()
    {
        $where['user_id'] = $_SESSION['user']['id'];
        $res = M("welive_user")
             ->where($where)
             ->save(array('online_time'=>date("Y-m-d H:i:s",time())));
        $this->ajaxReturn($res,"更新",$res);
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
}