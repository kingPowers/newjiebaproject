<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace version;

use version\LoanConfig;
use version\LoanOrder;
use version\LoanIssue;
class LoanTender extends LoanConfig{
    public $allowAppMethods = ["addLoanTender"];
    private  $_maxRepayingOrderNum = 1;//未还款订单的最大数量
    
    public $loanId;//产品ID
    public $money;//贷款金额
    
    public $loanOrder;//loanOrder对象
    /*
     * 新增贷款订单
     *  
     */
    public function addLoanTender(){
        $memberInfo = $this->getLoanOrder()->getMember()->getMemberInfo();
        if(false===$memberInfo){
            $this->error("会员未登录");
        }
        if(empty($this->money))$this->error("请输入借款金额");
        if(empty($this->loanId))$this->error("产品ID不能为空");
        $this->beforeAddLoanTender($memberInfo);
        
        $issueObject = new LoanIssue($this->commonModel,["loanId"=>$this->loanId,"money"=>$this->money]);
        $tenderData = array_merge(["memberid"=>$memberInfo["id"]],$issueObject->tender());
        $allotData = $issueObject->allotList();
        
        $tenderModel = new \LoanTenderModel();
        if(!$tenderModel->create($tenderData))$this->error($tenderModel->getError());
        
        try{
            if (!$tenderModel->inTrans()) {
                $tenderModel->startTrans();
                $trans = true;
            }
            if(false==($addId = $tenderModel->add())){
                throw new Exception("没申请成功(101)");
            }
            //还款订单
            foreach($allotData as $k=>$allot){
                $allot["tender_id"] = $addId;
                $allot["status"] = 0;
                $allot["allot_sn"] = $allot["allot_sn"].($k+1);
                if(false==M("loan_allot")->add($allot)){
                    throw new Exception("没申请成功(102)");
                }
            }
            
            if ($trans) {
    		$tenderModel->commit();
            }
            
            $this->success("申请成功");
            
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            if ($trans) {
                $tenderModel->rollback();
            }
            $this->error($error);
        }
    }
    
    
    
    
    //新增订单之前
    private function beforeAddLoanTender($memberInfo){
        //绑定银行卡
        if(false==$this->getLoanOrder()->getMember()->bankCardStatus($memberInfo["id"])){
            $this->error("未绑定银行卡");
        }
        //额度核查
        $quota = (new Quota($this->commonModel,["memberId"=>$memberInfo["id"]]))->getCreditQuota();
        if($this->money>$quota["maxMoney"] || $this->money<$quota["minMoney"]){
            $this->error("借款金额必须在{$quota["minMoney"]}-{$quota["maxMoney"]}之间");
        }
        //未还款的订单
        $orderWhere = [
            "where"=>["memberId"=>$memberInfo["id"],"orderStatus"=>2],
        ];
        $orderList = $this->getLoanOrder()->getAllTenderOrderList($orderWhere,-1);
        if($orderList["count"]>=$this->_maxRepayingOrderNum){
            $this->error("您尚有未还款的订单，不能再次申请！");
        }
        //未处理的订单
        $orderWhere = [
            "where"=>["memberId"=>$memberInfo["id"],"status"=>1],
        ];
        $orderList = $this->getLoanOrder()->getAllTenderOrderList($orderWhere,-1);
        if($orderList["count"]>=1){
            $this->error("您尚有未处理的订单，不能再次申请！");
        }
        
        
        
    }


    //获取loanOrder对象
    public function getLoanOrder(){
        if($this->loanOrder===null){
            $this->loanOrder = new LoanOrder($this->commonModel);
        }
        return $this->loanOrder;
    }
    
    /*
     * 贷款订单信息
     * 
     */
    public function getTenderList($memberId,$page = -1,$number = 12){
        $model = new \LoanTenderModel();
        $params = ["where"=>["memberId"=>$memberId,"orderStatus"=>3]];
        $list = $this->getLoanOrder()->getAllTenderOrderList($params, $page,$number);
        foreach($list["list"] as &$val){
            $val["lateCount"] = $this->lateOrderCount($memberId, $val["timeadd"]);
        }
        
        return $list;
    }
    /*
     * 逾期订单统计
     *       统计某个用户位于某一个时间段之前的逾期订单数
     *      @param $memberId  用户ID
     *      @timeadd 日期
     *      return int
     */
    public function  lateOrderCount($memberId,$timeadd = null){
        $where = [
            "tender.memberid"=>$memberId,
            "tender.timeadd"=>["elt",$timeadd],
            "tender.id"=>["exp","=allot.tender_id"],
            "allot.status"=>1,
            "allot.late_fee"=>["gt","0"],
        ];
        return (new \LoanTenderModel())->table("loan_tender tender,loan_allot allot")->where($where)->count();
    }
}
