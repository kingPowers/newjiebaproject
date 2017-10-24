<?php
/*
 * 合作公司管理
 */
class BusinessCompanyAction extends CommonAction {
    //service:commonModel 
    private $commonModel;
    //service 类名
    private $interClass = "BusinessCompany";
    
    public function __construct() {
        parent::__construct();
        $this->commonModel = D("service://Common");
    }
    /*
     * 所有合作公司列表
     * 
     */
    public function allCompanyList(){
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = 12;
        $listParams = [
            ["where"=>[
                        "companyname"=>$_REQUEST["companyname"],
                        "status"=>$_REQUEST["status"],
                       ],
                       
            ],
            $this->page["no"],//分页
            $this->page["num"],//每页条数
        ];
        $companyStatus = $this->commonModel->InternalCall($this->interClass,"getCompanyStatus");
        $companyList = $this->commonModel->InternalCall($this->interClass,"allCompanyList",$listParams);
        $this->page['count'] = $companyList['data']["count"];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->assign("companyStatus",$companyStatus["data"]);
        $this->assign("companyList",$companyList['data']["list"]);
        $this->setPage("allCompanyList".$this->getRequest()."/p/*.html");
        $this->display();
    }
    /*
     * 导出Excel所有合作公司列表
     * 
     */
    public function exportAllCompanyList(){
        
    }
    //新增合作公司
    public function addBusinessCompany(){
        if($_POST){
            $addResult = $this->commonModel->InternalCall($this->interClass,"addBusinessCompany",[$_POST]);
            if($addResult["respCode"]==0){
                $this->success($addResult["respMsg"]);
                //$this->redirect("allCompanyList");
            }else{
                $this->error($addResult["respMsg"]);
            }
        }
        $companyType = $this->commonModel->InternalCall($this->interClass,"getCompanyType");
        $this->assign("companyType",$companyType["data"]);
        $this->display();
    }
    //删除合作公司
    public function  deleteBusinessCompany(){
        
    }
    //修改合作公司
    public function editBusinessCompany(){
        $companyId = $_REQUEST["id"];
        $editType = $_REQUEST["editType"];
        if(empty($companyId))$this->redirect("allCompanyList");
        $company = $this->commonModel->InternalCall($this->interClass,"allCompanyList",[['where'=>["company_id"=>$companyId]],-1]);
        if($editType=='status'){//启用、停用
            $this->editCompanyStatus($companyId);
        }elseif($editType=='edit' && !empty($_POST)){
            $_POST["legal_certinumber_pic"] = $_POST["legal_certinumber_pic"]?$_POST["legal_certinumber_pic"]:$company["data"][0]["legal_certinumber_pic"];
            $_POST["license_number_pic"] = $_POST["license_number_pic"]?$_POST["license_number_pic"]:$company["data"][0]["license_number_pic"];
            $_POST["agreement1_pic"] = $_POST["agreement1_pic"]?$_POST["agreement1_pic"]:$company["data"][0]["agreement1_pic"];
            $_POST["agreement2_pic"] = $_POST["agreement2_pic"]?$_POST["agreement2_pic"]:$company["data"][0]["agreement2_pic"];
            //dump($_POST);exit;
            $editResult = $this->commonModel->InternalCall($this->interClass,"saveBusinessCompany",[$_POST]);
            if($editResult["respCode"]==0){
                $this->success($editResult["respMsg"]);
                //$this->redirect("allCompanyList");
            }else{
                $this->error($editResult["respMsg"]);
            }
        }elseif($editType=='view'){//查看
            return $this->viewBusinessCompanyDetail($companyId);
        }elseif($editType=="capital"){//关联资金方
            return $this->viewCapital($companyId);
        }
        
        $companyType = $this->commonModel->InternalCall($this->interClass,"getCompanyType");
        $this->assign("companyType",$companyType["data"]);
        $this->assign("company",$company["data"][0]);
        $this->display();
    }
    
    //修改合作公司状态
    private function editCompanyStatus($companyId){
         $company = $this->commonModel->InternalCall($this->interClass,"allCompanyList",[['where'=>["company_id"=>$companyId]],-1]);
         if(false==$company['data'][0])$this->redirect("allCompanyList");
         $company["data"][0]["status"] = $company["data"][0]["status"]==1?2:1;
         //dump($company["data"][0]);//exit;
         $saveResult = $this->commonModel->InternalCall($this->interClass,"saveBusinessCompany",[$company["data"][0]]);
         //dump($saveResult);exit;
         if($saveResult["respCode"]==0)$this->redirect("allCompanyList");
         $this->error($saveResult["respMsg"]);
    }
    
    //查看详情
    private function viewBusinessCompanyDetail($companyId){
        //$companyId = $_REQUEST["id"];
        $company = $this->commonModel->InternalCall($this->interClass,"allCompanyList",[['where'=>["company_id"=>$companyId]],-1]);
         if(false==$company['data'][0])$this->redirect("allCompanyList");
        $companyType = $this->commonModel->InternalCall($this->interClass,"getCompanyType");
        $this->assign("companyType",$companyType["data"]);
        $this->assign("company",$company["data"][0]);
        $this->display("viewBusinessCompanyDetail");
    }
    //查看关联的资金方
    private function viewCapital($companyId){
        $company = $this->commonModel->InternalCall($this->interClass,"connectCapitalCompany",[$companyId]);
        if(false==$company['data'][0])$this->redirect("allCompanyList");
        $this->assign("capital",$company["data"]);//dump($company);
        $this->display("viewCapital");
    }
    
    //分润负责列表
    public function profitRuleList()
    {
        $businessname = $this->_get("businessname","trim");
        if ($businessname)
        {
            $map['companyname'] = $businessname;
        }
        $params['class'] = 'BusinessRule';
        $params['method'] = 'ruleList';
        $params['data']['params']['where'] = $map;
        $res = $this->invoke($params);
        $this->assign("list",$res['data']);
        $this->display();
    }
    //编辑分润规则
    public function editRule()
    {
        if($_POST && ($_POST['is_ajax'] == 1))$this->ruleToDb();
        if($_POST && ($_POST['is_desc'] == 1))$this->descStair();
        $company_id = $this->_get("id");
        $params['class'] = "BusinessRule";
        $params['method'] = "viewBusinessRule";
        $params['data']['companyId'] = $company_id;
        $res = $this->invoke($params);
        $this->assign("info",$res['data']);
        $this->assign("is_sub",$this->_request("is_sub"));
        $this->display();
    }
    //减少分润阶梯
    public function descStair()
    {
        $ruleid = $this->_post("ruleid","trim");
        $params['class'] = "ChannelRule";
        $params['method'] = "deleteRule";
        $params['data']['id'] = $ruleid;
        $res = $this->invoke($params);
        if ($res['respCode'] === 0)
        {
            $this->ajaxSuccess("删除成功");
        }
        $this->ajaxError($res['respMsg'].$ruleid);
    }
    //分润规则入库
    public function ruleToDb()
    {
        $params['class'] = "ChannelRule";
        $params['method'] = "addChannelRule";
        $params['data']['data'] = $_POST['data'];
        $res = $this->invoke($params);//dump($res);
        if ($res['respCode'] === 0)
        {
            $this->operateToDb(1,$_SESSION['user']['id'],"编辑合作公司【" . $_POST[0]['business_company_id'] . "】分润规则");
            $this->ajaxSuccess("操作成功");
        }
        $this->ajaxError($res['respMsg'],$res);
    }
    //预存款列表
    public function businessPreDeposit()
    {
        $companyname = $this->_get("companyname",'trim');
        $map = [];$pagep = '';
        if ($companyname) {
            $map['companyname'] = $companyname;
            $pagep .= "companyname/{$companyname}";
        }
        $page_num = 5;
        $p = $_REQUEST['p']?$_REQUEST['p']:1;
        $params['class'] = 'BusinessPredeposit';
        $params['method'] = 'predeList';
        $params['data']['params']['where'] = $map;
        $params['data']['page'] = $p;
        $params['data']['number'] = $page_num;
        $res = $this->invoke($params);
        //设置分页
        $this->page['no'] = $p;
        $this->page['num'] = $page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/BusinessCompany/businessPreDeposit/{$pagepa}/p/*.html");
        $this->assign('list',$res['data']['list']);
        //dump($res);
        $this->display();
    }
    //增加预存款
    public function addDeposit()
    {
        if ($_POST && ($_POST['is_add'] == 1))$this->addDepositToDb();
        $this->display();
    }
    public function addDepositToDb()
    {
        $data['business_company_id'] = $this->_post("company_id","trim");
        $data['money'] = $this->_post("money","trim");
        $data['author'] = $_SESSION['user']['names'];
        $params['class'] = "BusinessPredeposit";
        $params['method'] = 'addPredeposit';
        $params['data']['data'] = $data;
        $res = $this->invoke($params);
        if ($res['respCode'] === 0) {
            $this->operateToDb(1,$_SESSION['user']['id'],"新增合作公司【" . $data['business_company_id'] . "】预存款");
            $this->ajaxSuccess("新增成功");
        }
        $this->ajaxError($res['respMsg']);
    }
    //合作公司预存款新增明细
    public function depositeDetail()
    {
        $company_id = $this->_get("company_id","trim");
        $params['class'] = "BusinessPredeposit";
        $params['method'] = "predepositDetailList";
        $params['data']['companyId'] = $company_id;
        $res = $this->invoke($params);
        //dump($res);dump($params);
        $this->assign("list",$res['data']);
        $this->display();
    }
    //合作公司预存款支出明细
    public function outDetail()
    {
        $company_id = $this->_get("company_id","trim");
        $p = $_REQUEST['p']?$_REQUEST['p']:1;
        $page_num = 10;
        $map = [];
        $params['class'] = "BusinessPredeposit";
        $params['method'] = "costoutDetailList";
        $params['data']['params']['where'] = $map;
        $params['data']['page'] = $p;
        $params['data']['number'] = $page_num;
        $res = $this->invoke($params);//dump($res);
        $this->page['no'] = $p;
        $this->page['num'] = $page_num;
        $count = $res['data']['count'];
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/BusinessCompany/outDetail/p/*.html");
        $this->assign("list",$res['data']['list']);
        $this->display();
    }
}