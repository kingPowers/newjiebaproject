<?php

/*
 * 资金方产品管理类
 * 
 */

namespace version;

use version\LoanConfig;
class CapitalLoan extends LoanConfig{
    /*
     * 芝麻信用分最小值
     */
    //private $_configMinCreditScore = "1";
    
    /*
     * 新增资金方产品
     * @param array $data:产品数据
     *      eg. $data[
     *              "min_loan_periode"=>"7",//借款期限，默认为7天
     *              "max_loan_periode"=>"3月"//最大借款期限，表示3个月
     *          ]
     */
    public function addCapitalLoan($data){
        $model = new \CapitalLoanModel();
        $model->commonModel = $this->commonModel;
        $model->postData = $data;
        if($model->create($data)){
            if($addId = $model->add()){
                $this->success("资金方产品新增成功",["add_id"=>$addId]);
            }else{
                $this->error("哎呀，没保存成功(".$model->getError().")");
            }
        }else{
            $this->error($model->getError());
        }
    }
    
    /*
     * 保存资金方产品
     */
    public function saveCapitalLoan($data){
        $model = new \CapitalLoanModel();
        $model->commonModel = $this->commonModel;
         $model->postData = $data;
        if($model->create($data)){
            if($saveId = $model->save()){
                $this->success("资金方产品修改成功",["add_id"=>$saveId]);
            }else{
                $this->error("哎呀，没修改成功(".$model->getError().")");
            }
        }else{
            $this->error($model->getError());
        }
    }
    /*
     * 获取芝麻信用分最小值
     */
    public function getConfigCreditScore(){
        return $this->_configMinCreditScore;
    }
    
    /*
     * 根据公司ID获取资金方产品列表
     * @param string $capitalCompanyId:公司ID
     * @return array
     */
    public function getCapLoanList($capitalCompanyId){
        $model = new \CapitalLoanModel();
        if(empty($capitalCompanyId))return [];
        $where = [
            "where"=>["capId"=>$capitalCompanyId],
        ];
        return $this->getAllCapitalLoanList($where,-1);
    }
    
     /*
        * 所有产品列表
        *        根据指定的条件查询所有产品列表
        *  @param array $params  查询条件,包括where,order
        *  @param  int   $page    页数， 规定：-1表示不分页
        *  @param  int   $number  每页的条数  默认为12
        *  
        *  @return array
        * 
        *        eg.
        *        $params = [
        *            "where"=>["companyname"=>$companyName,],//where查询条件
        *            "order"=>"",//排序方式
        *        ];
        */
    public function getAllCapitalLoanList($params = [],$page = -1,$number = 12){
        $model = new \CapitalLoanModel();
        $paramWhere = isset($params["where"])?$params["where"]:[];
        $order = isset($params["order"])?$params["order"]:"";
        
        //资金方ID
        if(isset($paramWhere["capId"]) && !empty($paramWhere["capId"])){
            $where["cap.id"] =$paramWhere["capId"];
        }
        //产品ID
        if(isset($paramWhere["id"]) && !empty($paramWhere["id"])){
            $where["cap_l.id"] =$paramWhere["id"];
        }
        //资金方公司名称
        if(isset($paramWhere["companyname"]) && !empty($paramWhere["companyname"])){
            $where["cap.companyname"] = ["like","%{$paramWhere["companyname"]}%"];
        }
        //产品名称
        if(isset($paramWhere["name"]) && !empty($paramWhere["name"])){
            $where["cap_l.name"] = ["like","%{$paramWhere["name"]}%"];
        }
        $where["_string"] = "cap_l.capital_company_id=cap.id";
        $fields = "cap_l.*,"
                . "cap.companyname as cap_companyname,"
                . "cap.legal_name as capital_legal_name,"
                . "cap.legal_certinumber as capital_legal_certinumber,"
                . "cap.company_address as capital_company_address";
        if(-1===intval($page)){//不分页
            return (array)$model->table("capital_loan cap_l,capital_company cap")
                                ->where($where)
                                ->field($fields)
                                ->order($order)
                                ->select();
        }else{
            $count = $model->table("capital_loan cap_l,capital_company cap")
                                ->where($where)
                                ->field($fields)
                                ->order($order)
                                ->count();
            $list = (array)$model->table("capital_loan cap_l,capital_company cap")
                                ->where($where)
                                ->field($fields)
                                ->order($order)
                                ->limit(($page-1)*$number.",".$number)
                                ->select();
                    
            return ["count"=>intval($count),"list"=>$list];                    
        }
    }
    
     /*
     * 查询一条资金方产品信息
     * @param string $id 产品ID
     * @return  array
     */
    public function getOneCapitalLoan($id){
        $list = $this->getAllCapitalLoanList(["where"=>["id"=>$id]],-1);
        return (array)$list[0];
    }
    
}
