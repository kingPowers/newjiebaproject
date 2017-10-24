<?php
/**
* 
*/
class CapitalAction extends CommonAction
{
    private $status=['1'=>'启用','2'=>'停用'];//资金方状态
    private $page_num = 10;//分页设置：每页显示数量
    public function _initialize()
	{
		//获取省份列表
		$province_list = $this->getProvince();
		//dump($province_list);
		$this->assign("province_list",$province_list);
		$this->assign("subject",$this->subject());
		$this->assign("capital_type",$this->capitalType());
		$this->assign("open_type",$this->openType());
		$this->assign('status',$this->status);
	}
	//资金方列表
	public function index()
	{
		$Capital_name = $this->_get('v',"trim");
		$Capital_status = $this->_get('status',"trim");
		$map = [];
		$pagepa = '';
		if ($Capital_name)
		{
			$map['companyname'] = $Capital_name;
			$pagepa .= "v/{$Capital_name}/";
		}
		if ($Capital_status) 
		{
			$map['status'] = $Capital_status;
			$pagepa .= 'status/{$Capital_status}';
		}	
		$p = $_REQUEST['p']?$_REQUEST['p']:1;
		$params['class'] = 'CapitalCompany';
		$params['method'] = 'getAllCapitalCompanyList';
		$params['data']['params']['where'] = $map;
		$params['data']['page'] = $p;
		$params['data']['number'] = $this->page_num;
		$res = $this->invoke($params);//dump($res);
		$this->assign("list",$res['data']['list']);
		//设置分页
		$this->page['no'] = $p;
        $this->page['num'] = $this->page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Capital/index/{$pagepa}/p/*.html");
		$this->display();
	}
	//添加自己方
	public function add()
	{
		$this->assign("is_sub",1);//is_sub:是否需要提交：1：需要；0：不需要
		$this->display('edit');
	}
	//资金方数据入库
	public function dataToDb()
	{
		$capitalid = $this->_post("capitalid","trim");
		$data['companyname'] = $this->_post("companyname","trim");
		$data['subjectname'] = $this->_post("subject","trim");
		$data['typename'] = $this->_post("capital_type","trim");
		$data['legal_name'] = $this->_post("legal_name","trim");
		$data['legal_certinumber'] = $this->_post("legal_certinumber","trim");
		$data['organize_code'] = $this->_post("organize_code","trim");
		$data['company_address'] = $this->_post("company_address","trim");
		$data['license_number'] = $this->_post("license_number","trim");
		$data['contact_name'] = $this->_post("contact_name","trim");
		$data['contact_mobile'] = $this->_post("contact_mobile","trim");
		$data['contact_email'] = $this->_post("contact_email","trim");
		$data['legal_bank'] = $this->_post("legal_bank","trim");
		$data['legal_accno'] = $this->_post("legal_accno","trim");
		$data['legal_openname'] = $this->_post("legal_openname","trim");
		$data['legal_opentype'] = $this->_post("open_type","trim");
		$data['tax_certificate'] = $this->_post("tax_certificate","trim");
		$data['status'] = $this->_post("status","trim");
		$data['deposit_percent'] = $this->_post("deposit_percent","trim");
        $params['class'] = 'CapitalCompany';
        if ($capitalid)
        {//资金方id存在：修改数据
        	$data['id'] = $capitalid;
    		$params['method'] = 'saveCapitalCompany';
        } else {//资金方id不存在：添加数据
			$params['method'] = 'addCapitalCompany';
        } 
        $params['data']['data'] = $data;
        $res = $this->invoke($params);
        if ($res['respCode'] === 0)
        {
        	$this->operateToDb(1,$_SESSION['user']['id'],"编辑资金方【".$data['companyname']."】");//操作入库
        	$this->ajaxSuccess("操作成功");
        }
		$this->ajaxError($res['respMsg']);
	}
	//编辑资金方详情
	public function edit()
	{
		if($_POST && $_POST['companyname'])$this->dataToDb();
		$capitalid = $this->_get("id","trim");
		//获取资金方详情
		$params['class'] = 'CapitalCompany';
		$params['method'] = 'getOneCapitalCompany';
		$params['data']['id'] = $capitalid;
		$res = $this->invoke($params);//dump($res);	
		$this->assign("is_sub",$this->_get("is_sub","trim"));
		$this->assign("info",$res['data']);
		$this->display();
	}
	//获取资金方主体列表
	public function subject()
	{
		$params['class'] = 'CapitalCompany';
		$params['method'] = 'getSubjectname';
		return $this->invoke($params)['data'];
	}
	//获取资金方类型列表
	public function capitalType()
	{
		$params['class'] = 'CapitalCompany';
		$params['method'] = 'getTypeNmame';
		return $this->invoke($params)['data'];
	}
	//获取账号类型列表
	public function openType()
	{
		$params['class'] = 'CapitalCompany';
		$params['method'] = 'getLegalOpentype';
		return $this->invoke($params)['data'];
	}
}