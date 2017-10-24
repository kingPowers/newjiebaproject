<?php
/**
* 用户管理
*/
class MemberAction extends CommonAction
{
	private $status = ['1'=>"新注册",'9'=>'冻结','3'=>'存量'];
	public function _initialize()
	{
		$this->assign("status",$this->status);
	}
	//用户列表
	public function index()
	{
		$p = $_REQUEST['p']?$_REQUEST['p']:1;
		$names = $this->_get("names","trim");
		$mobile = $this->_get("mobile","trim");
		$status = $this->_get("status","trim");
		$page_num = 7;
		$map = [];$pagepa = '';
		if ($names) {
			$map['names'] = $names;
			$pagepa .= "/names/{$names}";
		}
		if ($mobile) {
			$map['mobile'] = $mobile;
			$pagepa .= "/mobile/{$mobile}";
		}
		if ($status) {
			$map['status'] = $status;
			$pagepa .= "/status/{$status}";
		}
		$map['busCompanyId'] = $_SESSION['user']['businessid']?$_SESSION['user']['businessid']:'no';
		$params['class'] = "Member";
		$params['method'] = "getAllMemberList";
		$params['data']['params']['where'] = $map;
		$params['data']['page'] = $p;
		$params['data']['number'] = $page_num;
		$res = $this->invoke($params);//dump($map);
		$this->page['no'] = $p;//dump($res);
        $this->page['num'] = $page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Member/index/{$pagepa}/p/*.html");
		$this->assign("list",$res['data']['list']);
		$this->display();
	}
	//用户明细
	public function detail()
	{
		$p = $_REQUEST['p']?$_REQUEST:1;
		$pagepa = '';
		$pagepa .= "id/{$memberid}";
		$page_num = 7;
		$memberid = $this->_get("id","trim");
		$map['memberId'] = $memberid;
		$map['businessid'] = $_SESSION['user']['businessid'];
		$params['class'] = "Member";
		$params['method'] = "getAllMemberList";
		$params['data']['params']['where'] = $map;
		$res = $this->invoke($params);
		//dump($res);
		//贷款详情
		$loan_params['class'] = "LoanTender";
		$loan_params['method'] = "getTenderList";
		$loan_params['data']['memberId'] = $memberid;
		$loan_res = $this->invoke($loan_params);//dump($loan_res);
		//设置分页
		$this->page['no'] = $p;
        $this->page['num'] = $page_num;
        $count = $loan_res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Member/detail/{$pagepa}/p/*.html");
		$this->assign("loan_info",$loan_res['data']['list']);
		$this->assign("info",$res['data']['list'][0]);
		$this->display();
	}
	//用户冻结、解冻操作
	public function operate()
	{
		//dump($_GET);
		if ($_POST && ($_POST['is_save'] == 1)) {
			$params['class'] = "Member";
			$params['method'] = "memberStatus";
			$params['data']['memberId'] = $this->_post("mid","trim");
			$res = $this->invoke($params);
			if ($res['respCode'] === 0) {
				$this->operateToDb(1,$_SESSION['user']['id'],"用户【".$params['data']['memberId']."】解冻/冻结");
				$this->ajaxSuccess("操作成功");
			}
			$this->ajaxError($res['respMsg']);
		}
		$this->display();
	}
	//用户调额操作
	public function adjustMoney()
	{
		if ($_POST && ($_POST['is_save'] == 1)) {
			$params['class'] = "MemberImport";
			$params['method'] = "setPromiseMoney";
			$params['data']['id'] = $this->_post("mid","trim");
			$params['data']['promiseMoney'] = $this->_post("new_promise","trim");
			$res = $this->invoke($params);
			if ($res['respCode'] === 0) {
				$this->operateToDb(1,$_SESSION['user']['id'],"用户【".$params['data']['id']."】调额");
				$this->ajaxSuccess("操作成功");
			}
			$this->ajaxError($res['respMsg']);
		}
		$this->display();
	}
	//导入用户
	public function importMember()
	{
		$params['class'] = 'MemberImport';
		$params['method'] = 'importMember';
		$params['data']['company_id'] = $_SESSION['user']['businessid'];
		$res = $this->invoke($params);
		if ($res['respCode'] === 0) {
			$this->operateToDb(1,$_SESSION['user']['id'],"导入合作公司【".$params['data']['company_id']."】用户");
			$this->ajaxSuccess("导入成功");
		}
		$this->ajaxError($res['respMsg']);
	}
}