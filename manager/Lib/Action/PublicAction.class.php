<?php

/* 
 *  吉祥果
 */

/**
 * Description of CommonAction
 * 无验证模块
 * @author Nydia
 */
class PublicAction extends Action {
    private $allow_group = [1,2];
    public function __CONSTRUCT(){
        header("Content-Type:text/html; charset=utf-8");
        parent::__CONSTRUCT();
    }
    public function emp()
    {
        exit("您访问的页面不存在！");
    }
    // public function login()
    // {
    //     $err = $this->_get("err","trim");
    //     $this->assign('err',$err);
    //     $this->display();
    // }
    public function doLogin()
    {
        import("Think.ORG.Util.Login");
        $login = new Login();
        $data = $_POST;
        $data['auth_group'] = 1;
        if (false == ($log_res = $login->doLogin($data)))
        {
            $this->ajaxReturn('',$login->getError(),0);
        }
        $_SESSION['_token_'] = $log_res['_token_'];
        $_SESSION['user'] = S("token_".$log_res['_token_']."_info"); 
        $this->operateToDb(1,$_SESSION['user']['id'],"用户登录",1);
        $this->ajaxReturn(['jumpurl'=>$log_res['jumpurl']],"登录成功",1);
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
        R("/Common/operateToDb",array(1,$_SESSION['user']['id'],"用户退出登录",1));
        S("token_".$_SESSION['_token_']."_info",null);
        unset($_SESSION['_token_'],$_SESSION['user']);
        $this->ajaxReturn('',"退出成功",1);
    }
    //判断是否登录
    public function isLogin()
    {
        return $_SESSION['_token_'] && S("token_" . $_SESSION['_token_'] . "_info");
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
    //获取菜单
    public function getJsMenu() {
        import("Think.ORG.Util.Auth");
        $auth = new Auth();
        $return = $auth->getMenuData($_SESSION['user']['id']);
        foreach ($return as $key=>$re)
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
        //dump($return);
        exit(json_encode($return));
    }

    //生成验证码
    public function verifyCode() 
    {
        import('ORG.Util.Image');
        Image::buildImageVerify();
    }
    //获取验证码
    public function getVerify() 
    {
        if ($_POST['is_get'] == 1) {
            $this->ajaxReturn($_SESSION['verify'],'成功',1);
        }
    }
    //编辑器上传图片
    public function imageUpload(){
        import('Think.ORG.Util.EditorUpload');
        EditorUpload::save();
    }
    /*
    获取城市列表
    $province_code:城市所属省份
    返回：城市数组列表
     */
    public function getCity($province_code='')
    {
        if(empty($province_code))$province_code = $this->_post("province_code",'trim');
        $params['class'] = 'City';
        $params['method'] = 'getCityList';
        $params['data'] = $province_code;
        $res = $this->invoke($params);
        if($res['respCode'] === 0)
        {
            $this->ajaxReturn($res['data'],"获取成功",1);
        }
        $this->error("获取失败");
    }
    /*
    根据渠道名称模糊查询
    $name:渠道名称
     */
    public function getChannelList($name)
    {
        if(empty($name))$name = $this->_post("name","trim");
        $params['class'] = "ChannelCompany";
        $params['method'] = 'getChannelCompanyList';
        $params['data'] = $name;
        $res = $this->invoke($params);
        if ($res['respCode'] === 0)
        {
            $this->ajaxReturn($res['data'],'成功',1);
        }
        $this->error($res['respMsg']);
    }
    /*
    获取所有的渠道
     */
    public function getAllChannel()
    {
        $params['class'] = 'ChannelCompany';
        $params['method'] = 'getAllChannelCompanyList';
        $res = $this->invoke($params);
        $this->ajaxReturn($res['data']);
    }
    //根据商户名称模糊查询商户列表
    //$name:商户名称
    public function getBusinessList($name)
    {
        if(empty($name))$name = $this->_post("name","trim");
        $params['class'] = "BusinessCompany";
        $params['method'] = 'getBusinessCompanyList';
        $params['data'] = $name;
        $res = $this->invoke($params);
        if ($res['respCode'] === 0)
        {
            $this->ajaxReturn($res['data']['list'],'成功',1);
        }
        $this->error($res['respMsg']);
    }
    /*
    获取所有商户
     */
    public function getAllBusiness()
    {
        $params['class'] = 'BusinessCompany';
        $params['method'] = 'allCompanyList';
        $res = $this->invoke($params);
        $this->ajaxReturn($res['data']['list']);
    }
    //根据资金方名称查询资金方列表
    //$name:资金方名称
    public function getCapitalList($name)
    {
        if(empty($name))$name = $this->_post("name","trim");
        $params['class'] = "CapitalCompany";
        $params['method'] = 'getCapitalCompanyList';
        $params['data'] = $name;
        $res = $this->invoke($params);
        if ($res['respCode'] === 0)
        {
            $this->ajaxReturn($res['data'],'成功'.$name,1);
        }
        $this->error($res['respMsg']);
    }
    //根据资金方id查询资金方产品列表
    //$name:资金方id
    public function getCapitalLoan($id)
    {
        if(empty($id))$id = $this->_post("id","trim");
        $params['class'] = "CapitalLoan";
        $params['method'] = 'getCapLoanList';
        $params['data'] = $id;
        $res = $this->invoke($params);
        if ($res['respCode'] === 0)
        {
            $this->ajaxReturn($res['data'],'成功'.$id,1);
        }
        $this->error($res['respMsg']);
    }
    public function getAuthList()
    {
        $groupid = $this->_post("groupid","trim");
        if(empty($groupid))$this->error("请选择正确类型");
        import("Think.ORG.Util.Auth");
        $auth = new Auth();
        $auth_list = $auth->getMenuData('','all',$groupid);
        $this->ajaxReturn($auth_list);
    }
    public function realQuotaMoney()
    {
        $params['class'] = 'Capital';
        $params['method'] = 'getCapitalDetail';
        $params['data']['businessId'] = $this->_post("businessid","trim");
        $params['data']['capitalid'] = $this->_post("capitalid","trim");
        $res = $this->invoke($params);
        $this->ajaxReturn($res['data']);
    }
    //调用service应用下的业务功能
    //$params : 传递的内容
    //          class:调用的类（必须）
    //          method:调用方法（必须）
    //          data:传递的数据
    public function invoke($params){
        $model = D("service://Common");
        $result = $model->InternalCall($params['class'],$params['method'],$params['data']);
        return $result;
    }
}
