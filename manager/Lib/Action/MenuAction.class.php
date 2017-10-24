<?php

/* 
 * 吉祥果
 */

/**
 * Description of CommonAction
 * 权限菜单
 */
class MenuAction extends CommonAction {

    private $_M = array();
    public $auth;
    //初始化
    protected function _initialize() {
        import("Think.ORG.Util.Auth");
        $this->auth = new Auth();
        $group_list = $this->getGroupList();
        //dump($group_list);
        $this->assign('group_list',$group_list);
        $this->_M = M('auth_rule');
        //dump($this->auth->getMenuData('','all',$groupid));
        // $this->assign('list', D('Auth')->getMenuData($_SESSION['user']['id'],'menu'));
    }
    //菜单列表
    public function index()
    {
        $groupid = $_REQUEST["groupid"]?$_REQUEST["groupid"]:1;
        //dump($this->auth->getMenuData('','all',$groupid));
        $this->assign('list',$this->auth->getMenuData('','all',$groupid));
        $this->assign("groupid",$groupid);
        $this->display();
    }

    public function add() {
        $groupid = $_REQUEST["groupid"]?$_REQUEST["groupid"]:1;
        $this->assign('list',$this->auth->getMenuData('','all',$groupid));
        $this->assign("groupid",$groupid);
        $this->display('menu');
    }

    public function edit() {
        if($_POST && isset($_POST['title']))$this->dataToDb();
        $id = $this->_get('id', 'intval');
        $menu = M(C("AUTH_CONFIG")['AUTH_RULE'])->where("id={$id}")->find();
        list($menu['module'], $menu['action']) = explode('-', $menu['name']);
        //dump($menu);
        $groupid = $menu['groupid'];
        $this->assign('groupid',$groupid);//dump($this->auth->getMenuData('','all',$groupid));
        $this->assign('list',$this->auth->getMenuData('','all',$groupid));
        $this->assign('pid', $menu['pid'] ? $menu['pid'] :0);
        $this->assign('menu', $menu);
        $this->display('menu');
    }

    //添加数据
    public function dataToDb() 
    {
        $groupid = $this->_post("groupid","trim");
        $pid = $this->_post("pid",'trim');
        $title = $this->_post("title",'trim');
        $ac = $this->_post("module",'trim');
        $func = $this->_post("action",'trim');
        $status = $this->_post("status",'trim');
        $type = $this->_post("type",'trim');
        $mid = $this->_post('mid', 'intval');
        if(empty($title) || empty($ac) || !in_array($status,['0','1']))
        {
            $this->ajaxError("请填写完整信息");
        }
        if(empty($groupid) || !in_array($groupid,$this->auth->getGroups()))$this->ajaxError("系统错误，请刷新重试");
        $data = array();
        if ($_FILES) {
            if (false == ($icon_url = $this->uploadIcon('icon'))) {
                $this->ajaxError("上传菜单icon失败");
            }
            $data['icon'] = explode("static/",$icon_url)[1];
        }    
        $data['groupid'] = $groupid;
        $data['pid'] = $pid;
        $data['name'] = (($pid == 0) || empty($func))?$ac:($ac . "-" . $func);
        $data['title'] = $title; 
        $data['operateuser'] = $_SESSION['user']['id'];
        $data['status'] = $status;
        $data['type'] = $type;  
        $data['lasttime'] = date("Y-m-d H:i:s",time());
        $res = false;
        if(empty($mid))
        {   
            $data['timeadd'] = date("Y-m-d H:i:s",time());
            $data['adduser'] = $_SESSION['user']['id'];
            $res = M("auth_rule")->add($data);
        } else {
            $res = M("auth_rule")->where("id={$mid}")->save($data);
        }
        $this->operateToDb($res,$_SESSION['user']['id'],"编辑菜单【" . $data['title'] . "】",1);
        if(false == $res)
        {
            $this->ajaxError("操作失败");
        }      
        $this->ajaxSuccess("操作成功");
    }
    //菜单排序
    public function sort() {
        $string = $this->_post('string');
        $sort = explode('|', $string);
        foreach ($sort as $var) {
            list($id, $sort) = explode(':', $var);
            M('AuthRule')->where("id={$id}")->save(array('sort' => $sort));
        }
        $this->_reset_data();
        $this->success('修改成功');
    }
    public function getGroupList()
    {
       return M('auth_group')->select();
    }
    //更新缓存菜单
    private function _reset_data() {
        F('global_auth_rule_all', null);
        F('global_auth_rule_menu', null);
    }
    /*
     * 上传图标
     *    eg.  <input name='pic' type="file"/>
     *         $this->uploadIcon("pic");
     *      上传成功返回:F:\xinjieba/ThinkPHP/../static/Upload/menuIcon/149933052753839_0.jpg
     * 
     *  @return 
     *   上传成功后返回该图片的绝对路径  否则返回FALSE    
     *     
     *    
     */
    public function uploadIcon($saveName){
        $commonModel = D("service://Common");
        $commonModel->savePath = "menuIcon/";
        $result = $commonModel->InternalCall("UploadFile","uploadImage",[$saveName]);
        if($result["respCode"]==0){
                return $result["data"];
        }else{
            return false;
        }
    }
}
