<?php
/**
* 权限管理
*/
class AuthAction extends CommonAction
{
	public function _initialize()
	{
		$this->assign("user_status",['0'=>'禁用','1'=>'启用']);
	}
	//角色管理
	public function role()
	{
		$title = $this->_get("role_name","trim");
		$map = [];
		if ($title) {
			$map['at.title'] = ['like',"%{$title}%"];
		}
	    $map['at.businessid'] = $_SESSION['user']['businessid'];
		$role_list = M('auth_type at')
		->join("user u on u.id=at.adduser")
		->field("at.*,u.names as adduser_name")
		->where($map)
		->order("at.timeadd desc")
		->select();
		//dump($role_list);dump($map);
		$this->assign("list",$role_list);
		$this->display();
	}
	//角色添加
	public function roleAdd()
	{
		$auth_list = $this->getAuthList($_SESSION['user']['id']);
		$this->assign("auth_list",$auth_list);
		$this->display('roleEdit');
	}
	//角色编辑
	public function roleEdit()
	{
		if ($_POST && ($_POST['is_save'] == 1))$this->roleToDb();
		$type_id = $this->_get("id","trim");
		$auth_list = $this->getAuthList($_SESSION['user']['id']);
		//dump($auth_list);
		$role = M("auth_type")->where("id='{$type_id}'")->find();
		//dump($role);
		$this->assign("auth_list",$auth_list);
		$this->assign("role",$role);
		$this->display();
	}
	public function roleToDb()
	{
		$type_id = $this->_post("type_id","trim");
		$data['title'] = $this->_post("role_name","trim");
		$data['rule'] = $this->_post("rule","trim");
		$data['groupid'] = $_SESSION['user']['groupid'];
		$data['businessid'] = $_SESSION['user']['businessid'];
		if (empty($data['title']) || empty($data['groupid']) || empty($data['businessid']))
        {
            $this->ajaxError("信息不完整！");
        }
        //新增
        if (!$type_id) {
        	$data['adduser'] = $_SESSION['user']['id'];
        	$data['timeadd'] = date("Y-m-d H:i:s",time());
        	$data['lasttime'] = $data['timeadd'];
        	$data['status'] = 1;
        	$data['type_number'] = rand(1111,9999)."-".date("YmdHis",time());
        	$res = M('auth_type')->add($data);
        } else {
        	$data['lasttime'] = date("Y-m-d H:i:s",time());
        	$res = M('auth_type')->where("id='{$type_id}'")->save($data);
        }
        $this->operateToDb($res,$_SESSION['user']['id'],"编辑角色【".$data['title']."】",1);
        if ($res) {
        	$this->ajaxSuccess("操作成功！");
        }
        $this->ajaxError('系统错误请重新再试！');
	}
	//获取用户权限列表 $userid : 用户ID
	public function getAuthList($userid)
    {
        if(empty($userid))return false;
        import("Think.ORG.Util.Auth");
        $auth = new Auth();
        return $auth->getMenuData($userid,'all');   
    }
    //用户列表
	public function user()
	{
		$names = $this->_get("names","trim");
		$mobile = $this->_get("mobile","trim");
		$map = [];
		if ($names) $map['u.names'] = ['like',"%{$names}%"];
		if ($mobile) $map['u.mobile'] = $mobile;
		$map['u.businessid'] = $_SESSION['user']['businessid'];
		$user_list = M('user u')
		->join("user au on au.id=u.adduser")
		->join("auth_type at on at.id=u.typeid")
		->field("u.*,au.names as adduser_name,at.title as type_name")
		->where($map)
		->order("u.timeadd desc")
		->select();
		//dump($user_list);
		$this->assign("user_list",$user_list);
		$this->display();
	}
	//添加用户
	public function userAdd()
	{
        $this->assign("type_list",$this->getUserType($_SESSION['user']['businessid']));
		$this->display('userEdit');
	}
	//编辑用户
	public function userEdit()
	{
		if ($_POST && ($_POST['is_save'] == 1))$this->userToDb();
		$userid = $this->_get("id","trim");
		$user_info = M('user')->where("id='{$userid}'")->find();
		$this->assign("user",$user_info);
		$this->assign("type_list",$this->getUserType($_SESSION['user']['businessid']));
		$this->display();
	}
	public function userToDb()
	{
		$userid = $this->_post("userid","trim");
		$data['username'] = $this->_post("username","trim");
		$data['names'] = $this->_post("names","trim");
		$data['mobile'] = $this->_post("mobile","trim");
		$data['status'] = $this->_post("status","trim");
		$data['typeid'] = $this->_post("typeid","trim");
		if (empty($data['username']) || empty($data['names']) || empty($data['mobile']) || empty($data['typeid']) || !in_array($data['status'],['0','1'])) {
			$this->ajaxError("信息不完整！");
		}
		//新增用户
		$post_password = $this->_post("password",'trim');
		$password = $post_password?$post_password:($data['username']."123456");
		if (!$userid) {
			$data['adduser'] = $_SESSION['user']['id'];
			$data['timeadd'] = date("Y-m-d H:i:s",time());
			$data['user_number'] = rand(0,9999)."-".time();
			$data['regip'] = get_client_ip();
			$data['groupid'] = $_SESSION['user']['groupid'];
			$data['businessid'] =$_SESSION['user']['businessid'];
			$data['password'] = gen_password($password);
			$res = M("user")->add($data);
		} else {
			$res = M("user")->where("id={$userid}")->save($data);
		}
		$this->operateToDb($res,$_SESSION['userid']['id'],"编辑/添加用户【".$data['username']."】",1);
		if ($res) {
			$this->ajaxSuccess("操作成功！密码为".$password);
		}
		$this->ajaxError("系统错误请重新再试！");
	}
	//获取该商户的所有角色
	public function getUserType($businessid)
	{
		if (empty($businessid)) return [];
		return M("auth_type")->where("businessid='{$businessid}'")->select();
	}
    public function process()
    {
    	$this->display();
    }
    public function processAdd()
    {
    	$this->display('processEdit');
    }
    public function processEdit()
    {
    	$this->display();
    }
}