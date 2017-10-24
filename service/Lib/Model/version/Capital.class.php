<?php
/*
 * 资金管理
 * 
 */

namespace version;

use version\Object;
class Capital extends Object{
   
    public function addCapital($data){
        $model = new \CapitalModel();
        $model->postData = $data;
        if($model->create($data)){
            if($model->add()){
                $this->success("添加成功");
            }else{
                $this->error("没保存成功(".$model->getDbError().")");
            }
        }else{
            $this->error($model->getError());
        }
    }
    /*
     * 获取所有的资金列表
     *      $params
     */
    public function getAllCapitalList($params = [],$page = -1,$number = 12){
        $model = new \CapitalModel();
        $paramWhere = isset($params["where"])?$params["where"]:[];
        $order = isset($params["order"])?$params["order"]:"";
        //合作公司ID
        if(isset($paramWhere["businessId"]) && !empty($paramWhere["businessId"])){
            $where["bus.id"] = $paramWhere["businessId"];
        }
        //资金方ID
        if(isset($paramWhere["capitalId"]) && !empty($paramWhere["capitalId"])){
            $where["cap.id"] = $paramWhere["capitalId"];
        }
        //资金方公司名称
        if(isset($paramWhere["capCompanyname"]) && !empty($paramWhere["capCompanyname"])){
            $where["cap.companyname"] = ["like","%{$paramWhere["capCompanyname"]}%"];
        }
        //合作公司名称
        if(isset($paramWhere["busCompanyname"]) && !empty($paramWhere["busCompanyname"])){
            $where["bus.companyname"] = ["like","%{$paramWhere["busCompanyname"]}%"];
        }
        
        $fields = "business_company_id,capital_company_id,sum(c.promise_money) as total_promise_money,"//保证金金额
                . "c.first_credit_money,"//授信额度
                . "round(sum(c.promise_money)/cap.deposit_percent*100,2) as real_quota_money,"//实际额度
                . "bus.companyname as bus_companyname,"//合作公司名称
                . "cap.companyname as cap_companyname,cap.capital_number,cap.deposit_percent";//资金方信息
        
        $where["_string"] = "c.business_company_id=bus.id "
                . "and c.capital_company_id=cap.id ";
        
        $table = "business_company bus,"
                . "capital_company cap,"
                . "capital c";
        $group =  "business_company_id,capital_company_id";
            $list = $model->table($table)
                          ->where($where)
                          ->field($fields)
                          ->order($order)
                          ->group($group)
                          ->select();
            $orderObject = new LoanOrder($this->commonModel);
            $businessLoanObject = new BusinessLoan($this->commonModel);
            foreach($list as &$val){
                //商户公司-资方 之间关联的产品
                $busWhere = ["where"=>["capCompanyId"=>$val["capital_company_id"],"busCompanyId"=>$val["business_company_id"]]];
                $loanIds = array_column($businessLoanObject->getAllBusinessLoanList($busWhere, -1),"id");
                
                $where = ["where"=>["orderStatus"=>2,"busLoanId"=>["in",$loanIds]]];
                $orderList = $orderObject->getAllTenderOrderList($where, -1);//未结清订单列表
                
                $val['noRepayment'] = array_sum(array_column($orderList, "money"));//贷款余额（未还款金额）
                $val["realAvailQuotaMoney"] = $val["real_quota_money"]-$val['noRepayment'];//实际可用额度 = 实际额度-贷款余额
                //保证金头寸 = 保证金金额—（实际可用额度/(实际额度/保证金金额)）
                $val["promiseMoneyPos"] = round($val["total_promise_money"]-$val["realAvailQuotaMoney"]/($val["real_quota_money"]/$val["total_promise_money"]),2);
            }
            return (array)$list;
    }
    
    //查看明细
    public function getCapitalDetail($businessCompanyId,$capitalCompanyId){
        $capitalModel = new \CapitalModel();
        $where = [
            "business_company_id"=>$businessCompanyId,
            "capital_company_id"=>$capitalCompanyId,
            "_string"=>"c.capital_company_id=cap.id and c.business_company_id=bus.id",
        ];
        $list = $capitalModel
                ->table("capital c,business_company bus,capital_company cap")
                ->field("c.*,bus.companyname as busCompanyname,cap.companyname as capCompanyname")
                ->where($where)
                ->order("cap.timeadd desc")
                ->select();
        return $list;
    }
    
    
    
    
}
