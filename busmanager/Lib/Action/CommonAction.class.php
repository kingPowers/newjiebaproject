<?php
/**
* 公共基类
*/
class CommonAction extends Action
{
	 //登录用户
    public $user = array();//用户登录时保存的信息（非实时）
    //分页
    public $page = array();//分页数组
	public function __construct()
	{
		$this->page = M()->page;
        $this->page['num'] = 13;
		parent::__construct();
		if (!$this->isLogin()) {
            //跳转时间为1时立即跳转
            $this->assign("waitSecond",1);
            $this->assign("jumpurl","/login.html");
            $this->error("请重新登录");
            exit;
        }
        import("Think.ORG.Util.Auth");
        $auth = new Auth();
        //检测是否有操作权限
        if ((MODULE_NAME != "Public") && (false == $auth->check($_SESSION['user']['id'],MODULE_NAME . '-' . ACTION_NAME)))
        {    
             $this->error("亲，您没有操作权限");
        }
	}
	//判断是否登录
    public function isLogin()
    {
        return $_SESSION['_token_'] && S("token_" . $_SESSION['_token_'] . "_info");
    }
    //设置分页
    public function setPage($pageurl) {
        $this->assign('page_vars', $this->page);
        $this->assign('page_url', $pageurl);
        $pagehtml = $this->fetch('Public:page');
        $this->assign('page', $pagehtml);
    }
    //获取用户详情
    public function getUserInfo()
    {
        $base_info = M("user")->where("id='{$_SESSION['user']['id']}'")->find(); 
       $base_info['company_info'] = M("channel_company")->where("id='{$base_info['channelid']}'")->find();
       return $base_info;
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
    //调用service应用下的业务功能
    //$params : 传递的内容
    //          class:调用的类（必须）
    //          method:调用方法（必须）
    //          data:传递的数据
    public function invoke($params)
    {
        $model = D("service://Common");
        $result = $model->InternalCall($params['class'],$params['method'],$params['data']);
        return $result;
    }
    protected function ajaxSuccess($message='',$data=''){
        $this->ajaxReturn($data,$message,1,'',$this->pagestring);
    }
    protected function ajaxError($message='',$data=''){
        $this->ajaxReturn($data,$message,0,'',$this->pagestring);
    }
}