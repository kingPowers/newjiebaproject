<?php

namespace version;

/**
 * 清结算管理
 * 
 */
class Clear extends Object{
    public $model;
    public function init() {
        parent::init();
        $this->model = new \BusinessPredepositModel();
    }
    /*
     * 清结算管理列表
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
    public function clearList($params = [],$page = -1,$number = 12){
        $paramWhere = isset($params["where"])?$params["where"]:[];
        $order = isset($params["order"])?$params["order"]:"";
        
        //交易日期
        if(!empty($paramWhere["startTime"]) && !empty($paramWhere["endTime"])){
            $where["tender.timeadd"] = [["egt",$paramWhere["startTime"]],["elt",$paramWhere["endTime"]]];
        }
        //渠道公司名称
        if(!empty($paramWhere["channelCompanyname"])){
            $where["cha.companyname"] = ["like","%{$paramWhere["channelCompanyname"]}%"];
        }
        //渠道公司ID
        if(!empty($paramWhere["channelCompanynameId"])){
            $where["cha.id"] = $paramWhere["channelCompanynameId"];
        }
        //合作公司
        if(!empty($paramWhere["businessCompanyname"])){
            $where["bus.companyname"] = ["like","%{$paramWhere["businessCompanyname"]}%"];
        }
        
        $where["tender.status"] = ["in",[\LoanTenderModel::STATUS_SUCCESS, \LoanTenderModel::STATUS_FAIL]];
        $where["_string"] = "tender.id=allot.tender_id  "
                . "and tender.id=cost.tender_id "
                . "and tender.business_loan_id=loan.id "
                . "and loan.capital_loan_id=cap_loan.id "
                . "and loan.business_company_id=bus.id "
                . "and bus.id=bc.business_company_id "
                . "and bc.channel_company_id=cha.id";
        
        $fields = "tender.*,"
                . "cost.cost_money,cost.clear_time,"
                . "cap_loan.periode_rate as cap_periode_rate,"
                . "bus.companyname as business_companyname,"
                . "cha.companyname as channel_companyname";
        
        $table = "loan_tender tender,"
                . "loan_allot allot,"
                . "business_costout cost,"
                . "business_loan loan,"
                . "capital_loan cap_loan,"
                . "business_company bus,"
                . "business_channel bc,"
                . "channel_company cha";
        $profitOrderModel = new \ProfitOrderModel();
        if(-1===intval($page)){//不分页
             $list = (array)$this->model->table($table)
                                ->where($where)
                                ->field($fields)
                                ->order($order)
                                ->select();
             foreach($list as &$val){
                 $channelProfitList = $profitOrderModel->getChannelProfit($val["id"]);
                 $val["capital_money"] = round($val["money"]*$val["cap_periode_rate"]/100,2);//贷款本金
                 $val["first_channel_profit"] = round($channelProfitList[1]["profit_money"],2);//一级渠道费用
                 $val["second_channel_profit"] = round($channelProfitList[2]["profit_money"],2);//二级渠道费用
                 $val["third_channel_profit"] = round($channelProfitList[3]["profit_money"],2);//3级渠道费用
                 $val["business_clear_fee"] = round($val["total_fee"]-$val["capital_money"]-$val["cost_money"],2);//合作公司结算费用
                 $val["clear_status"] = $channelProfitList[1]["status"];//结算状态
             }
           //dump(M()->getLastSql()); 
             return $list;
        }else{
            $count = $this->model->table($table)
                                ->where($where)
                                ->field($fields)
                                ->order($order)
                                ->count();
            $list = (array)$this->model->table($table)
                                ->where($where)
                                ->field($fields)
                                ->order($order)
                                ->limit(($page-1)*$number.",".$number)
                                ->select();
            foreach($list as &$val){
                 $channelProfitList = $profitOrderModel->getChannelProfit($val["id"]);
                 $val["capital_money"] = round($val["money"]*$val["cap_periode_rate"]/100,2);//贷款成本
                 $val["first_channel_profit"] = round($channelProfitList[1]["profit_money"],2);//一级渠道费用
                 $val["second_channel_profit"] = round($channelProfitList[2]["profit_money"],2);//二级渠道费用
                 $val["third_channel_profit"] = round($channelProfitList[3]["profit_money"],2);//3级渠道费用
                 $val["business_clear_fee"] = round($val["total_fee"]-$val["capital_money"]-$val["cost_money"],2);//合作公司结算费用
                 $val["clear_status"] = $channelProfitList[1]["status"];//结算状态
             }
            //dump(M()->getLastSql());
            return ["count"=>intval($count),"list"=>$list];      
        }
    }
    
     /*
     * 分润管理列表
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
    public function clearProfitList($params = [],$page = -1,$number = 12){
        $paramWhere = isset($params["where"])?$params["where"]:[];
        $order = isset($params["order"])?$params["order"]:"";
        
        //交易日期
        if(!empty($paramWhere["startTime"]) && !empty($paramWhere["endTime"])){
            $where["tender.timeadd"] = [["egt",$paramWhere["startTime"]],["elt",$paramWhere["endTime"]]];
        }
        //渠道公司名称
        if(!empty($paramWhere["channelCompanyname"])){
            $where["cha.companyname"] = ["like","%{$paramWhere["channelCompanyname"]}%"];
        }
        //渠道公司ID
        if(!empty($paramWhere["channelCompanynameId"])){
            $where["cha.id"] = $paramWhere["channelCompanynameId"];
        }
        //合作公司
        if(!empty($paramWhere["businessCompanyname"])){
            $where["bus.companyname"] = ["like","%{$paramWhere["businessCompanyname"]}%"];
        }
        
        $where["tender.status"] = ["in",[\LoanTenderModel::STATUS_SUCCESS, \LoanTenderModel::STATUS_FAIL]];
        $where["_string"] = "tender.id=allot.tender_id  "
                . "and tender.id=cost.tender_id "
                . "and tender.business_loan_id=loan.id "
                . "and loan.capital_loan_id=cap_loan.id "
                . "and loan.business_company_id=bus.id "
                . "and bus.id=bc.business_company_id "
                . "and bc.channel_company_id=cha.id";
        
        $fields = "tender.*,"
                . "bus.companyname as business_companyname,bus.business_number,"
                . "cost.cost_money,cost.clear_time,"
                . "cap_loan.periode_rate as cap_periode_rate";
        
        $table = "loan_tender tender,"
                . "loan_allot allot,"
                . "business_costout cost,"
                . "business_loan loan,"
                . "capital_loan cap_loan,"
                . "business_company bus,"
                . "business_channel bc,"
                . "channel_company cha";
        $profitOrderModel = new \ProfitOrderModel();
        $limit = -1===intval($page)?"":($page-1)*$number.",".$number;
        $count = $this->model->table($table)
                            ->where($where)
                            ->field($fields)
                            ->order($order)
                            ->count();
        $list = (array)$this->model->table($table)
                            ->where($where)
                            ->field($fields)
                            ->order($order)
                            ->limit($limit)
                            ->select();
        //$totalMoney = $this->model->table($talbe)->where($where)->sum("tender.money");//当前交易总金额
        //$tenderIds = array_column($list, "id");
        //$totalProfitMoney = $this->model->table("profit_order")->where(["channel_level"=>1,["tender_id"=>["in",$tenderIds]]])->sum("profit_money");//平台分润总额
        foreach($list as &$val){
             $channelProfitList = $profitOrderModel->getChannelProfit($val["id"]);
             $val["capital_money"] = round($val["money"]*$val["cap_periode_rate"]/100,2);//贷款成本
             $val["first_channel_profit"] = round($channelProfitList[1]["profit_money"],2);//一级渠道费用
             $val["second_channel_profit"] = round($channelProfitList[2]["profit_money"],2);//二级渠道费用
             $val["third_channel_profit"] = round($channelProfitList[3]["profit_money"],2);//3级渠道费用
             $val["business_clear_fee"] = round($val["total_fee"]-$val["capital_money"]-$val["cost_money"],2);//合作公司结算费用
             $val["clear_status"] = $channelProfitList[1]["status"];//结算状态
         }
        //dump(M()->getLastSql());
        return ["count"=>intval($count),"list"=>$list,"totalMoney"=>array_sum(array_column($list,"money")),"totalProfitMoney"=>array_sum(array_column($list,"first_channel_profit"))];      
        
    }
    
    
}