<?php
/**
* 用户权限管理
*/
class AuthModel extends Model
{
	private $auth_config;
	public function __construct()
	{
		parent::__construct();
		$this->auth_config = C("AUTH_CONFIG");
	}
	public function get_first_menu()
	{
		$first_menu = M()
		->table($this->auth_config['AUTH_RULE'])
		->where("pid=0 and status=1")
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
		$menu_list = $this->get_menu_list($userid);
		$allow_list = array();
		foreach ($menu_list as $value) {
			$allow_list[] = $value['name'];
		}
		if(in_array($rule,$allow_list))return true;
		return false;
	}
	public function getMenuData($userid,$type = 'all')
	{
		$menu_list = $this->get_menu_list($userid);
		$first_menu = $this->get_first_menu();
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
					if($type == 'all')
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
	 */
	public function get_menu_list($userid)
	{
        if(empty($userid))return false;
        if(isset($_SESSION['AUTH_RULE_'.$userid]))
        {
        	return $_SESSION['AUTH_RULE_'.$userid];
        }
        $all_rule = $this->get_all_rule();
        //超级管理员获得所有权限
        $user_type = M("user")->where("id={$userid}")->getfield("typeid");
        if($user_type == 1)return $all_rule;
        $type_rule = $this->get_type_rule($userid);   
        if(empty($type_rule))return $type_rule;
        
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
	public function get_type_rule($userid)
	{
		if(empty($userid))return $userid;
		$res = M('user u')
	        ->join($this->auth_config['USER_TYPE']." at on at.id=u.typeid")
	        ->where("u.id={$userid} and at.status=1")
	        ->getfield("at.rule");
        $type_rule = explode(",",$res);
        return $type_rule;
	}
	public function get_all_rule()
	{
		return M($this->auth_config['AUTH_RULE']." ar")
		->join("user u on u.id=ar.adduser")
		->field("ar.*,u.username as addname")
		->where("ar.status=1 and ar.pid != 0")->order("ar.sort asc,ar.timeadd")->select();
	}
}