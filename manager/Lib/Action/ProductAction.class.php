<?php
/**
* 产品管理
*/
class ProductAction extends CommonAction
{
	private $page_num = 10;
	protected function _initialize()
	{
		$params['class'] = "LoanPeriode";
		$params['method'] = "getLoanPeriodeList";
		$res = $this->invoke($params);//dump($res);
		$this->assign("periode",$res['data']);
		$this->assign("pay_type",$this->payType());
	}
	public function index()
	{
		$name = $this->_get('name',"trim");
		$channel = $this->_get('channel',"trim");
		$status = $this->_get("status","trim");
		$business = $this->_get("business","trim");
		$map = [];$pagepa = '';
		if ($name)
		{
			$map['name'] = $name;
			$pagepa .= "name/{$name}/";
		}
		if ($channel)
		{
			$map['chaCompanyname'] = $channel;
			$pagepa .= "channel/{$channel}/";
		}
		if ($business)
		{
			$map['busCompanyname'] = $business;
			$pagepa = "business/{$business}/";
		}
		if ($status)
		{
			$map['status'] = $status;
			$pagepa .= "status/{$status}";
		}
		$params['class'] = 'BusinessLoan';
		$params['method'] = 'getAllBusinessLoanList';
		$params['data']['params']['where'] = $map;
		$params['data']['page'] = $_REQUEST['p']?$_REQUEST['p']:1;
		$params['data']['number'] = $this->page_num;
		$res = $this->invoke($params);//dump($res);
		//设置分页
		$this->page['no'] = $params['data']['page'];
        $this->page['num'] = $this->page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Product/index/{$pagepa}/p/*.html");
		$this->assign("list",$res['data']['list']);
		$this->assign("status",['0'=>"全部",'1'=>'启用','2'=>'停用']);
		$this->display();
	}
	public function addBusiness()
	{
		$this->assign("is_sub",1);
		$this->display("editBusiness");
	}
	public function editBusiness()
	{	
		if($_POST && isset($_POST['loan_name']))$this->BusinessDataToDb();
		$productid = $this->_get("id","trim");
		$params['class'] = "BusinessLoan";
		$params['method'] = "getOneBusinessLoan";
		$params['data']['id'] = $productid;
		$res  = $this->invoke($params);//dump($res);
		$this->assign("is_sub",$this->_request("is_sub"));
		$this->assign("info",$res['data']);
		$this->display();
	}
	public function BusinessDataToDb()
	{
		$productid = $this->_post("id","trim");//$this->error(json_encode($_POST));
		$data['business_company_id'] = $this->_post("business_company_id","trim");
		$data['capital_loan_id'] = $this->_post("loanid","trim");
		$data['capitalid'] = $this->_post("capitalid","trim");
		$data['name'] = $this->_post("loan_name","trim");
		$data['min_loanmoney'] = $this->_post("min_loanmoney","trim");
		$data['max_loanmoney'] = $this->_post("max_loanmoney","trim");
		$data['loan_periode_id'] = $this->_post("loan_periode_id","trim");
		$data['periode_rate'] = $this->_post("periode_rate","trim");
		$data['late_periode_rate'] = $this->_post("late_periode_rate","trim");
		$data['procedure_free'] = $this->_post("procedure_free","trim");
		$data['plat_free_rate'] = $this->_post("plat_free","trim");
		$data['is_auto_pass'] = $this->_post("is_auto","trim");
		$data['status'] = $this->_post("status","trim");
		$params['class'] = "BusinessLoan";
		if ($productid)
		{
			$data['id'] = $productid;
			$params['method'] = "saveBusinessLoan";
		} else {
			$params['method'] = "addBusinessLoan";
		}
		$params['data']['data'] = $data;
		$res = $this->invoke($params);
		if ($res['respCode'] === 0)
		{
			$this->operateToDb(1,$_SESSION['user']['id'],"编辑商户产品【".$data['loan_name'] . "】");
			$this->ajaxSuccess("操作成功");
		}
		$this->ajaxError($res['respMsg']);
	}
	public function capitalProduct()
	{
		$product = $this->_get('product',"trim");
		$capital = $this->_get('capital',"trim");
		$map = [];
		$pagepa = '';
		if ($capital)
		{
			$map['companyname'] = $capital;
			$pagepa .= "capital/{$capital}/";
		}
		if ($product)
		{
			$map['name'] = $product;
			$pagepa .= "product/{$product}";
		}
		$params['class'] = 'CapitalLoan';
		$params['method'] = 'getAllCapitalLoanList';
		$params['data']['params']['where'] = $map;
		$params['data']['page'] = $_REQUEST['p']?$_REQUEST['p']:1;
		$params['data']['number'] = $this->page_num;
		$res = $this->invoke($params);//dump($res);
		//设置分页
		$this->page['no'] = $params['data']['page'];
        $this->page['num'] = $this->page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Channel/index/{$pagepa}/p/*.html");
		$this->assign("list",$res['data']['list']);
		$this->display();
	}
	public function addCapital()
	{
		$this->assign("is_sub",1);
		$this->display("editCapital");
	}
	public function editCapital()
	{
		if($_POST && $_POST['name'])$this->capitalDataToDb();
		$productid = $this->_request("id");
		$params['class'] = "CapitalLoan";
		$params['method'] = "getOneCapitalLoan";
		$params['data']['id'] = $productid;
		$res = $this->invoke($params);
		//dump($res);
		$this->assign("info",$res['data']);
		$this->assign("is_sub",$this->_request("is_sub"));
		$this->display();
	}
	public function capitalDataToDb()
	{
		$productid = $this->_post("productid","trim");
		$data['capital_company_id'] = $this->_post("capital_company_id","trim");
		$data['name'] = $this->_post("name","trim");
		$data['remark'] = $this->_post("remark","trim");
		$data['min_loanmoney'] = $this->_post("min_loanmoney","trim");
		$data['max_loanmoney'] = $this->_post("max_loanmoney","trim");
		$data['loan_repayment'] = $this->_post("loan_repayment","trim");
		$data['min_loan_periode'] = $this->_post("min_loan_periode","trim");
		$data['max_loan_periode'] = $this->_post("max_loan_periode","trim");
		$data['periode_rate'] = $this->_post("periode_rate","trim");
		$data['is_sesame_credit'] = $this->_post("is_sesame_credit","trim");
		$data['sesame_credit_score'] = $this->_post("sesame_credit_score","trim");
		// $data['deposit_percent'] = $this->_post("deposit_percent","trim");
		$params['class'] = 'CapitalLoan';
		if ($productid)
		{
			$data['id'] = $productid;
			$params['method'] = 'saveCapitalLoan';
		} else {
			$params['method'] = 'addCapitalLoan';
		}
		$params['data']['data'] = $data;
		$res = $this->invoke($params);
		if ($res['respCode'] === 0)
		{
			$this->operateToDb(1,$_SESSION['user']['id'],"编辑资金方(".$data['capital_company_id'].")产品【".$data['name']."】");
			$this->ajaxSuccess("操作成功");
		}
		$this->ajaxError($res['respMsg']);
	}
	public function payType()
	{
		$params['class'] = 'CapitalLoan';
		$params['method'] = 'getLoanRepayment';
		return $this->invoke($params)['data'];
	}
}