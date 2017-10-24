<?php

/* 
 * 吉祥果
 */

/**
 * Description of CommonAction
 * 继承基类
 */
class CommonAction extends Action {

    //登录用户
    public $user = array();//用户登录时保存的信息（非实时）
    //分页
    public $page = array();//分页数组

    //初始化，子类用_initialize
    public function __construct() {
        $this->page = M()->page;
        $this->page['num'] = 13;
        parent::__construct();
        $uid = session('uid');
        if (!$this->isLogin()) {
            //跳转时间为1时立即跳转
            $this->assign("waitSecond",1);
            $this->error("请重新登录","/login.html");
            exit;
        }
        //检测是否有操作权限
        if ((MODULE_NAME != "Public") && (false == D("Auth")->check($_SESSION['user']['id'],MODULE_NAME . '-' . ACTION_NAME)))
        {    
             $this->error("亲，您没有操作权限".MODULE_NAME . '-' . ACTION_NAME);
        }
        $this->user = $_SESSION['user'];
    }
    //判断是否登录
    public function isLogin()
    {
        return $_SESSION['_token_'] && S("token_{$_SESSION['_token_']}_info");
    }
    //设置分页
    public function setPage($pageurl) {
        $this->assign('page_vars', $this->page);
        $this->assign('page_url', $pageurl);
        $pagehtml = $this->fetch('Public:page');
        $this->assign('page', $pagehtml);
    }
    
    //获取用户的实时信息
    public function getUserInfo()
    {
        if (!$this->isLogin())return false;
        $info = S("token_" . $_SESSION['_token_'] . "_info");
        return M("user")->where("id={$info['id']}")->find();
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
    //获取请求数据
    public function boxpost() {
        $data = $this->_request('data');
        if (empty($data)) {
            return null;
        }
        $postring = explode('&amp;', $data);
        $post = array();
        foreach ($postring as $po) {
            $poitem = explode('=', $po);
            if ($poitem[0] == 'r')
                continue;
            $post[$poitem[0]] = $poitem[1];
        }
        return $post;
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
    //获取身份列表
    public function getProvince()
    {
        $params['class'] = 'City';
        $params['method'] = 'provinceList';
        $res = $this->invoke($params);
        return $res['data']?$res['data']:[];
    }
    
    public function getRequest(){
        $params = [];
        foreach($_REQUEST as $key=>$val){
            $params[] = is_string($val)?"{$key}={$val}":"";
        }
        return "/".implode("&",$params);
    }
    protected function ajaxSuccess($message='',$data=''){
        $this->ajaxReturn($data,$message,1,'',$this->pagestring);
    }

    protected function ajaxError($message='',$data=''){
        $this->ajaxReturn($data,$message,0,'',$this->pagestring);
    }
}
