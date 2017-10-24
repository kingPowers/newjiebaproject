<?php

/*
 *  数据统计管理类
 *          所有的数据统计计算
 */

namespace version;

class DataStat extends Object{
    public $member;//Member.class.对象
    public $loanOrder;//loanOrder.class对象
    /*
     * 渠道统计
     *  @param $channelCompanyId:渠道公司ID
     *  @return array
     */
    public function channelStat($channelCompanyId){
        $businessCompanyObject = new BusinessCompany($this->commonModel);
        $businessCompanyList = $businessCompanyObject->allCompanyList(["where"=>["channel_company_id"=>$channelCompanyId]], -1);
        $businessCompanyIds = array_column($businessCompanyList,"id");
        //用户量
        $totalMemberList = $this->getMember()->getAllMemberList(["where"=>["busCompanyId"=>["in",$businessCompanyIds]]],-1);
        $todayMemberList = $this->getMember()->getAllMemberList(["where"=>["busCompanyId"=>["in",$businessCompanyIds],"startTime"=>date("Y-m-d")]],-1);
        //贷款订单量
        $totalOrderList = $this->getLoanOrder()->getAllTenderOrderList(["where"=>["channelCompanyId"=>$channelCompanyId]],-1);
        $todayOrderList = $this->getLoanOrder()->getAllTenderOrderList(["where"=>["channelCompanyId"=>$channelCompanyId,"startTime"=>date("Y-m-d")]],-1);
        
        //分润订单量
        $profitModel = new \ProfitOrderModel();
        $totalProfitList = $profitModel->getProfitOrderList(["channel_company_id"=>$channelCompanyId]);
        $todayProfitList = $profitModel->getProfitOrderList(["channel_company_id"=>$channelCompanyId,"startTime"=>date("Y-m-d",strtotime("-1 days")),"endTime"=>date("Y-m-d")]);
        return [
            "totalMemberCount"=>count($totalMemberList["list"]),//用户总量
            "todayMemberCount"=>count($todayMemberList["list"]),//今日新增用户量
            "totalOrderCount"=>count($totalOrderList["list"]),//贷款订单总量
            "todayOrderCount"=>count($todayOrderList["list"]),//今日新增订单量
            "totalOrderMoney"=>array_sum(array_column($totalOrderList["list"],"money")),//贷款总金额
            "todayOrderMoney"=>array_sum(array_column($todayOrderList["list"],"money")),//今日新增贷款金额
            "totalProfitMoney"=>array_sum(array_column($totalProfitList,"profit_money")),//贷款总金额
            "todayProfitMoney"=>array_sum(array_column($todayProfitList,"profit_money")),//昨日新增贷款金额
        ];
    }
    /*
     * 合作公司统计
     *  @param $businessCompanyId:合作公司ID
     *  @return array
     */
    public function businessStat($businessCompanyId){
        $businessCompanyObject = new BusinessCompany($this->commonModel);
        //用户量
        $totalMemberList = $this->getMember()->getAllMemberList(["where"=>["busCompanyId"=>$businessCompanyId]],-1);
        $todayMemberList = $this->getMember()->getAllMemberList(["where"=>["busCompanyId"=>$businessCompanyId,"startTime"=>date("Y-m-d")]],-1);
        //贷款订单量
        $totalOrderList = $this->getLoanOrder()->getAllTenderOrderList(["where"=>["busCompanyId"=>$businessCompanyId]],-1);
        $todayOrderList = $this->getLoanOrder()->getAllTenderOrderList(["where"=>["busCompanyId"=>$businessCompanyId,"startTime"=>date("Y-m-d")]],-1);
        return [
            "totalMemberCount"=>count($totalMemberList["list"]),//用户总量
            "todayMemberCount"=>count($todayMemberList["list"]),//今日新增用户量
            "totalOrderCount"=>count($totalOrderList["list"]),//贷款订单总量
            "todayOrderCount"=>count($todayOrderList["list"]),//今日新增订单量
            "totalOrderMoney"=>array_sum(array_column($totalOrderList["list"],"money")),//贷款总金额
            "todayOrderMoney"=>array_sum(array_column($todayOrderList["list"],"money")),//今日新增贷款金额
        ];
    }
    
    /*
     * 管理员账户统计
     *  @param $businessCompanyId:合作公司ID
     *  @return array
     */
    public function managerStat(){
        $businessCompanyObject = new BusinessCompany($this->commonModel);
        //用户量
        $totalMemberList = $this->getMember()->getAllMemberList([],-1);
        $todayMemberList = $this->getMember()->getAllMemberList(["where"=>["startTime"=>date("Y-m-d")]],-1);
        //贷款订单量
        $totalOrderList = $this->getLoanOrder()->getAllTenderOrderList([],-1);
        $todayOrderList = $this->getLoanOrder()->getAllTenderOrderList(["where"=>["startTime"=>date("Y-m-d")]],-1);
        return [
            "totalMemberCount"=>count($totalMemberList["list"]),//用户总量
            "todayMemberCount"=>count($todayMemberList["list"]),//今日新增用户量
            "totalOrderCount"=>count($totalOrderList["list"]),//贷款订单总量
            "todayOrderCount"=>count($todayOrderList["list"]),//今日新增订单量
            "totalOrderMoney"=>array_sum(array_column($totalOrderList["list"],"money")),//贷款总金额
            "todayOrderMoney"=>array_sum(array_column($todayOrderList["list"],"money")),//今日新增贷款金额
        ];
    }
    
    
    public function getMember(){
        if($this->member===null){
            $this->member = new Member($this->commonModel);
        }
        return $this->member;
    }
    
    public function getLoanOrder(){
        if($this->loanOrder===null){
            $this->loanOrder = new LoanOrder($this->commonModel);
        }
        return $this->loanOrder;
    }
}
