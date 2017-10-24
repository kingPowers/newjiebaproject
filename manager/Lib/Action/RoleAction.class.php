<?php

/* 
 * 吉祥果
 */

/**
 * 角色管理
 */
class RoleAction extends CommonAction {

    private $_M = array();
    public $auth;
    //初始化
    protected function _initialize() {
        $group_list = R("/Menu/getGroupList");
        //dump($group_list);
        $this->assign('group_list',$group_list);
        $this->_M = M('AuthGroup');
    }

    //首页
    public function index() {
        //dump($this->getRoleList($this->getUserInfo()));
        $type_name = $this->_get("type_name","trim");
        $groupid = $this->_get("group","trim");
        if ($type_name) {
            $map['title'] = $type_name;
        } 
        if ($groupid) {
            $map['groupid'] = $groupid;
        }
        //dump($this->getRoleList($map));
        $this->assign('list', $this->getRoleList($map));
        $this->display();
    }
    public function add() {
        $group_list = $this->getGroupList($this->getUserInfo());
        $this->assign("group_list",$group_list);
        $this->display('role');
    }
    public function edit() {
        if($_POST && isset($_POST['title']))$this->dataToDb();
        $user_info = $this->getUserInfo();
        $id = $this->_get('id', 'intval');
        $role = M(C("AUTH_CONFIG")['USER_TYPE'])->where("id={$id}")->find();
        $user_belong = $this->getUserBelong($role);//dump($user_belong);
        $this->assign("user_belong",$user_belong);
        $groupid = $role['groupid'];
        $group_list = $this->getGroupList($user_info);
        $this->assign("group_list",$group_list);
        $this->assign('rid', $id);//dump($role);
        $this->assign('role', $role);
        import("Think.ORG.Util.Auth");
        $auth = new Auth();
        $this->assign('list', $auth->getMenuData('','all',$groupid));
        $this->display('role');
    }
    public function dataToDb() {
        $user_info = $this->getUserInfo();
        $choseid = $this->_post("choseid","trim");
        $data['title'] = $this->_post("title","trim");
        $data['groupid'] = $this->_post("group","trim");
        $data['status'] = $this->_post("status","trim");
        $data['rule'] = $this->_post("rule","trim");
        $rid = $this->_post('rid', 'intval');
        if (empty($data['title']) || empty($data['groupid']) || !in_array($data['status'],['0','1']))
        {
            $this->ajaxError("请填写完整信息");
        }
        if ($choseid) {
            if($data['groupid'] == '1') {
                $data['headquartersid'] = $choseid;
            } elseif ($data['groupid'] == '2') {
                $data['channelid'] = $choseid;
            } elseif ($data['groupid'] == '3') {
                $data['businessid'] = $choseid;
            }
        }
        $adduser = $user_info['id'];//操作用户
        if(empty($rid))
        {
            if(empty($choseid))$this->ajaxError("请选择用户所属");
            $data['type_number'] = rand(1111,9999)."-".date("YmdHis",time());
            $data['adduser'] = $adduser;
            $data['timeadd'] = date("Y-m-d H:i:s",time());
            $res = M(C("AUTH_CONFIG")['USER_TYPE'])->add($data);
            $this->operateToDb($res,$adduser,"添加角色【" . $data['title'] . "】",1);
            if(false == $res)
            {                   
                $this->ajaxError("添加失败");
            }
            $this->ajaxSuccess("添加成功");
        }
        $res = M(C("AUTH_CONFIG")['USER_TYPE'])->where("id='{$rid}'")->save($data);
        $this->operateToDb($res,$adduser,"编辑角色【" . $data['title'] . "】",1);
        if(false == $res)
        {                   
            $this->ajaxError("编辑失败");
        }
        $this->ajaxSuccess("编辑成功");
    }
    public function getUserBelong($role)
    {
        if(empty($role['groupid']))return [];
        $res = [];
        switch ($role['groupid']) {
            case '1':
                $res = ['id'=>'1','companyname'=>'管理员'];
                break;
            case '2':
                $res = M('channel_company')->where("id={$role['channelid']}")->find();
                break;
            case '3':
                $res = M('business_company')->where("id={$role['businessid']}")->find();
                break;
        }
        return $res;
    }
    public function getRoleList($map='',$limit='')
    {
        $where = [];
        if ($map['title']) $where['at.title'] = array("like","%{$map['title']}%");
        if ($map['groupid']) $where['at.groupid'] = $map['groupid'];
        $file = "case when cl.companyname is not null then concat(cl.companyname,'(渠道)')  when bc.companyname is not null then concat(bc.companyname,'(商户)') else '借吧管理员' end as companyname";
        $list = M(C("AUTH_CONFIG")['USER_TYPE'].' at')
        ->join("user u on u.id=at.adduser")
        ->join(C("AUTH_CONFIG")['AUTH_GROUP'].' ag on ag.id=at.groupid')
        ->join("channel_company cl on cl.id=at.channelid")
        ->join("business_company bc on bc.id=at.businessid")
        ->where($where)
        ->field("at.*,u.username,ag.title as group_name,{$file},cl.companyname as cc,bc.companyname as buc")
        ->order("at.timeadd desc")
        ->limit($limit)
        ->select();
        //return M(C("AUTH_CONFIG")['USER_TYPE'].' at')->getLastSql();
        return $list;
    }
    public function getGroupList($user_info)
    {
        $groupid = M("user")->where("id='{$user_info['id']}' and status=1")->getfield("groupid");
        $all_group_id = M()->table(C("AUTH_CONFIG")['AUTH_GROUP'])->field("id")->select();
        $ids = array();
        foreach ($all_group_id as $value) {
            $ids[] = $value['id'];
        }
        if(!in_array($groupid,$ids))return false;
        $all_group_list = M()->table(C("AUTH_CONFIG")['AUTH_GROUP'])->select();
        if ($groupid == 2) {//渠道
            foreach ($all_group_list as $key => $value) {
                if ($value['id'] == 1) {
                    unset($all_group_list[$key]);
                }
            }
        } elseif ($groupid == 3) {//商户
            foreach ($all_group_list as $key => $value) {
                if (($value['id'] == 1) || ($value['id'] == 2)) {
                    unset($all_group_list[$key]);
                }
            }
        }
        return $all_group_list;
    }
}
