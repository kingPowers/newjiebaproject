<?php

/* 
 * 吉祥果
 */

/**
 * Description of CommonAction
 * 后台用户管理
 * @author Nydia
 */
class UserAction extends CommonAction {

    private $_M = array();

    protected function _initialize() {
        $group_list = R("/Menu/getGroupList");
        //dump($group_list);
        $this->assign('group_list',$group_list);
        $this->_M = M('user');
    }

    //首页
    public function index() {
        $username = $this->_get('username','trim');
        $groupid = $_REQUEST['groupid']?$_REQUEST['groupid']:1;
        $p = $this->_get('p', 'intval', 1);
        $user_info = $this->getUserInfo();
        $map = array();
        $params = '';
        $params .= "groupid/{$groupid}";
        if($username){
            $map['u.username'] = array("like","%{$username}%");
            $this->assign('username', $username);
            $params .= "/username/{$username}";
        } 
        $map['u.groupid'] = $groupid;
        $this->page['no'] = $p;
        $this->page['num'] = 15;
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $list = $this->getUserList($map,$limit);
        //dump($list);
        $count = $list['count'];
        $this->assign("groupid",$groupid);
        $this->page['total'] = ceil($count / $this->page['num']);
        //dump($this->page);
        //dump($list);
        $this->assign('list', $list['data']);
        $this->setPage("/User/index/{$params}/p/*.html");
        $this->display();
    }

    public function add() {
        $groupid = $_REQUEST['groupid']?$_REQUEST['groupid']:1;
        $company_list = $this->getCompanyList($groupid);
        //dump($company_list);
        $this->assign("company_list",$company_list);
        $this->assign("groupid",$groupid);
        $this->display('user');
    }

    public function edit() {
        if($_POST && ($_POST['is_edit'] == 1))$this->dataToDb();
        if($_POST['is_type'])$this->getUserType();
        $uid = $this->_get('id', 'intval');
        $user = $this->userDetail($uid);//dump($user);
        $company_list = $this->getCompanyList($user['groupid']);
        $this->assign("company_list",$company_list);
        $this->assign('uid', $uid);
        $this->assign('user', $user);
        $this->assign("groupid",$user['groupid']);
        $this->display('user');
    }
    public function userDetail($uid)
    {
        if (empty($uid))return [];
        $baseinfo = M("user")->where("id='{$uid}'")->find();
        $groupid = $baseinfo['groupid'];//return $baseinfo;
        if ($groupid == '1')
        {
            $companyinfo = ['company_id'=>1,'companyname'=>'管理员'];
            
        }
        if ($groupid == '2')
        {
            
            $companyinfo = M('channel_company')->field("id as company_id,companyname")->where("id='{$baseinfo['channelid']}'")->find();
        }
        if ($groupid == '3')
        {
            $companyinfo = M('business_company')->field("id as company_id,companyname")->where("id='{$baseinfo['businessid']}'")->find();
        }
        $typeinfo = M('auth_type')->field("id as typeid,title")->where("id='{$baseinfo['typeid']}'")->find();
        return array_merge(array_merge($baseinfo,$companyinfo),$typeinfo);
    }
    public function dataToDb() 
    {   
        $data = [];
        $groupid = $this->_post("groupid",'trim');
        $company_id = $this->_post("company_id","trim");
        $data['username'] = $this->_post("username",'trim');
        $data['names'] = $this->_post("names",'trim');
        $data['mobile'] = $this->_post("mobile",'trim');
        $data['status'] = $this->_post("status",'trim');
        $data['typeid'] = $this->_post("typeid",'trim');
        $password = $this->_post("password",'trim');
        $uid = $this->_post("uid","trim");
        if (empty($groupid))$this->ajaxError("系统错误，请刷新重试");
        if (empty($company_id))$this->ajaxError("请选择所属公司");
        if (empty($data['username']))$this->ajaxError("登录账号不能为空");
        if (empty($data['names']))$this->ajaxError("用户姓名不能为空");
        if (empty($data['mobile']))$this->ajaxError("电话号码不能为空");
        if (empty($data['typeid']))$this->ajaxError("请选择用户角色");
        if (!in_array($data['status'],array(0,1)))$this->ajaxError("请确定用户状态");
        if (!$uid)
        {
            if(M('user')->where("username='{$data['username']}' OR mobile='{$data['mobile']}'")->find())
            $this->ajaxError("该账号或手机已经存在");
        }
        switch ($groupid) {
            case '1':
                $data['headquartersid'] = 1;
                break;
            case '2':
                $data['channelid'] = $company_id;
                break;
            case '3':
                $data['businessid'] = $company_id;
                break;
        }
        if($password && (strlen($password)<6))$this->ajaxError("密码不能少于6个字符");
        if (!$uid)
        {//添加新用户  
            if(empty($password))$this->ajaxError("密码不能为空");
            $data['groupid'] = $groupid;
            $data['password'] = gen_password($password);
            $data['adduser'] = $_SESSION['user']['id'];
            $data['regip'] = get_client_ip();
            $data['timeadd'] = date("Y-m-d H:i:s",time());
            $data['user_number'] = rand(0,9999)."-".time();
            $res = M("user")->add($data);
            $this->operateToDb($res,$data['adduser'],"添加用户【".$data['username']."】（" . $res . "）",1);
            if ($res)
            {
                $this->ajaxSuccess("添加成功密码为".$password);
            }
            $this->ajaxError("添加失败，请稍后再试！");
        }
        //编辑用户
        if ($password) $data['password'] = gen_password($password);
        $res = M('user')->where("id='{$uid}'")->save($data);
        $this->operateToDb($res,$_SESSION['user']['id'],"编辑用户【" . $data['username'] . "】（" . $res . "）",1);
        if ($res)
        {
            $this->ajaxSuccess("修改成功");
        }
        $this->ajaxError("修改失败，请稍后再试！");
    }
    public function record()
    {
        $userid = $this->_request("id","trim");
        $starttime = $this->_get("starttime","trim");
        $endtime = $this->_get("endtime","trim");
        $params['where']['userid'] = $userid;
        $pagepa = '';
        $pagepa .= "id/{$userid}/";
        if ($starttime)
        {
            $params['where']['starttime'] = $starttime;
            $pagepa .= "starttime/{$starttime}";
        }
        if ($endtime)
        {
            $params['where']['endtime'] = $endtime;
            $pagepa .= "/endtime/{$endtime}";
        } 
        $page = $_REQUEST['p']?$_REQUEST['p']:1;
        $number = 20;
        $record = D("User")->getUsrRecord($params,$page,$number);//dump($record);
        //设置分页
        $this->page['no'] = $page;
        $this->page['num'] = $number;
        $count = $record['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/User/record/{$pagepa}/p/*.html");
        $this->assign("list",$record['list']);
        $this->display();
    }
    public function getCompanyList($groupid)
    {
        if (empty($groupid))return [];
        $return = [];
        if ($groupid == 1) {
            $return = [['id'=>1,"companyname"=>'管理员']];
        } elseif ($groupid == 2) {
            $return = M('channel_company')->field("id,companyname")->select();
        } elseif ($groupid == 3) {
            $return = M('business_company')->field("id,companyname")->select();
        }
        return $return;
    }
    public function getUserType($company_id = '',$groupid = '')
    {
        $company_id = $company_id?$company_id:$this->_post("company_id","trim");
        $groupid = $groupid?$groupid:$this->_post("groupid","trim");
        $map = [];
        $map['groupid'] = $groupid;
        switch ($groupid) {
            case '1': $map['headquartersid'] = 1;
                break;
            case '2': $map['channelid'] = $company_id;
                break;
            case '3': $map['businessid'] = $company_id;
                break;
            default:return [];
                break;
        } 
        $map['status'] = 1;
        $return =  M(C("AUTH_CONFIG")['USER_TYPE'])
        ->where($map)
        ->field('id,title')
        ->order("timeadd")
        ->select();
        if ($_POST['is_type'] == 1)
        {
            $this->ajaxSuccess("成功",$return);
        }
        return $return;
    }
    public function getUserList($_map='',$limit='')
    {
        $map = $_map;
        $status = "case when u.status=0 then '已禁用' when u.status=1 then '使用中' end as statusname";
        $count = M("user u")
            ->join("auth_type at on at.id=u.typeid")
            ->join("auth_group ag on ag.id=u.groupid")
            ->join("(select username,id as addid from user) as au on au.addid=u.adduser")
            ->where($map)
            ->count();
        if ($count > 0)
        {
            $list = M("user u")
                ->join("auth_type at on at.id=u.typeid")
                ->join("auth_group ag on ag.id=u.groupid")
                ->join("(select username,id as addid from user) as au on au.addid=u.adduser")
                ->where($map)
                ->field("u.id,u.username,u.names,u.mobile,u.status,u.timeadd,u.lasttime,at.title,au.username as addusername,{$status}")
                ->order("u.groupid,u.timeadd")
                ->limit($limit)
                ->select();
        }
        return ['data'=>$list,"count"=>$count];
    }

}
