<?php
/**
* 用户权限管理
*/
class Auth
{
	private $auth_config;
	static $auth_group = [];
	public function __construct()
	{
		$this->auth_config = C("AUTH_CONFIG");
		$groups = M("auth_group")->where("status=1")->select();
		foreach ($groups as $value) {
			$this->auth_group[] = $value['id'];
		}
	}
	/*
	获取各组一级权限列表
	$groupid:分组ＩＤ
	 */
	public function getFirstMenu($groupid)
	{
		if(false == $this->checkGroup($groupid))return [];
		$first_menu = M()
		->table($this->auth_config['AUTH_RULE'])
		->where("pid=0 and status=1 and groupid='{$groupid}'")
		->order("sort desc,timeadd asc")
		->select();
		return $first_menu;
	}
	/*
	判断用户权限
	$userid:用户id
	$rule:操作菜单
	 */
	public function check($userid,$rule)
	{
		$menu_list = $this->getMenuList($userid);
		$allow_list = array();
		foreach ($menu_list as $value) {
			$allow_list[] = $value['name'];
		}
		if(in_array($rule,$allow_list))return true;
		return false;
	}
	/*
	获取权限菜单数据
	$userid:用户ID
	$type:权限类型 menu:表示菜单权限；其他表示菜单+功能权限
	$groupid : 分组id ；若为空则使用该用户的分组ID
	 */
	public function getMenuData($userid = '',$type = 'menu',$groupid = '')
	{
		$is_get_group = false;
		if($groupid)$is_get_group = true;
		$groupid = $groupid?$groupid:M("user")->where("id='{$userid}'")->getfield("groupid");
		if(false == $this->checkGroup($groupid))return [];
		$group = $is_get_group?$groupid:'';
		$menu_list = $this->getMenuList($userid,'',$group);
		$first_menu = $this->getFirstMenu($groupid);
		$return = [];
		foreach ($first_menu as $value)
		{
			if ($value['type'] == 1)
			{
				$return[$value['id']] = $value;
			}
		}
		foreach ($menu_list as $value) {
			foreach ($return as &$val) {
				if(($val['id'] == $value['pid']))
				{
					if($type == 'menu')
					{
						if($value['type'] == 1)
						{
							$val['child'][] = $value;
						}
					} else {
						$val['child'][] = $value;
					}
				}	
			}
		}
		return $return;
	}
	/*
	获取用户已获权限列表
	$userid : 用户id
	$is_all : 是否获得该组全部权限 all:获取全部
	$groupid : 分组ID;若为空则使用该用户的分组ID
	 */
	public function getMenuList($userid = '',$is_all = '',$groupid = '')
	{
		$post_group = $groupid;//传过来的初始分组ID
        if (empty($userid) && empty($groupid))return [];
        $groupid = $groupid?$groupid:M('user')->where("id='{$userid}'")->getfield("groupid");
        if (false == $this->checkGroup($groupid))return [];
        if (isset($_SESSION['AUTH_RULE_'.$userid]))
        {//是否实时获取权限列表
        	return $_SESSION['AUTH_RULE_'.$userid];
        }
        $all_rule = $this->getAllRule($groupid);
        //超级管理员、$is_all=1、$groupid不为空时获得所有权限
        $user_type = M("user")->where("id={$userid}")->getfield("typeid");
        if(($user_type == 1) || ($is_all == 'all') || !empty($post_group))return $all_rule;
        //获取该用户类型的权限
        $type_rule = $this->getTypeRule($userid);  
        if(empty($type_rule))return []; 
        $menu_list = array();
        foreach ($all_rule as $value) {
        	if(in_array($value['id'],$type_rule))
        	{
        		$menu_list[] = $value;
        	}
        }
        if($this->auth_config['AUTH_TYPE'] == 2)
        {
        	$_SESSION['AUTH_RULE_'.$userid];
        }
        return $menu_list;
	}
	public function checkGroup($groupid)
	{
		return in_array($groupid,$this->auth_group);
	}
	/*
	获取该用户类型的权限
	$userid:用户ID
	 */
	public function getTypeRule($userid)
	{
		if(empty($userid))return $userid;
		$res = M('user u')
	        ->join($this->auth_config['USER_TYPE']." at on at.id=u.typeid")
	        ->where("u.id={$userid} and at.status=1")
	        ->getfield("at.rule");
        $type_rule = explode(",",$res);
        return $type_rule;
	}
	/*
	获取改组全部权限
	$groupid:分组ID
	 */
	public function getAllRule($groupid)
	{    
		if(false == $this->checkGroup($groupid))return [];
		return M($this->auth_config['AUTH_RULE']." ar")
		->join("user u on u.id=ar.adduser")
		->field("ar.*,u.username as addname")
		->where("ar.status=1 and ar.pid != 0 and ar.groupid='{$groupid}'")->order("ar.sort asc,ar.timeadd")->select();
	}
	/*
	获取分组列表
	 */
	public function getGroups()
	{
		return $this->auth_group;
	}
}