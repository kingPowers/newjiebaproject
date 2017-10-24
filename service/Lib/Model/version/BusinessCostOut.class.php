<?php

namespace version;

/**
 * 预存款支出管理
 *          --合作公司预存款支出
 *          用于合作公司的分润支出
 * 
 */
use version\Object;
use version\LoanOrder;
use version\ChannelRule;
class BusinessCostOut extends Object{
    
   public $channelRule;//channelRule.class.php对象
    
    /*
     * 新增支出订单 --business_costout表
     *      --loan_tender表中触发器触发
    public function addCostOut(){
        
    }
    */
    
    /*
     * 生成分润账单
     *      @param $tenderId  贷款订单ID，loan_tender主键
     *      $startTime  订单开始时间
     *      $endTime    订单结束时间
     *  
     *   @return array  账单生成数量数组说明
     */
    public function createCostOrder($tenderId = null,$startTime = null,$endTime = null){
        $where = [];
        $model = new \BusinessCostOutModel();
        if($startTime!==null)$where["timeadd"] = ["egt",$startTime];
        if($endTime!==null)$where["timeadd"] = ["elt",$startTime];
        if($tenderId!==null)$where["tender_id"] = $tenderId;
        $where["status"] = $model::STATUS_DISABLE;
        //预分润列表
        $costList = $model->where($where)->order("timeadd asc")->select();
        $orderList = [];$successOrder = $errorOrder = 0;
        //订单列表
        if(false!=$costList){
            $tenderIds = implode(",", array_column($costList,"tender_id"));
            $orderList = $this->getOrderList($tenderIds);//dump($orderList);exit;
            foreach($orderList["list"] as $order){
                //计算渠道分润
                $ruleList = $this->getChannelRule()->calculateRule($order);//dump($ruleList);exit;
                $result = $this->addProfitOrder($ruleList);
                if(true==$result){
                    $successOrder++;
                }else{
                    $errorOrder++;
                }
                sleep(0.01);
            }
        }
       return ["totalOrder"=>count($orderList),"errorOrder"=>$errorOrder,"successOrder"=>$successOrder]; 
        
    }
    
    /*
     * 新增分润订单
     *  @param array $ruleList  分润规则列表
     *  @retur boolean
     */
    protected function addProfitOrder($ruleList){
        $profitOrderModel = new \ProfitOrderModel();
        $businessCostOut = new \BusinessCostOutModel();
        $businessPreModel = new \BusinessPredepositModel();
        foreach($ruleList as $key=>$value){
            if(!$profitOrderModel->create($value)){
                return false;
            }
        }
        $isTrans = false;
        if(!$profitOrderModel->inTrans()){
            $isTrans = true;
            $profitOrderModel->startTrans();
        }
        
        try {
            foreach($ruleList as $key=>$proOrder){
                $profitOrderModel->create($proOrder);
                if(!$profitOrderModel->add()){
                    throw new \Exception ("账单生成失败");
                }
            }
            $saveData = [
                "status"=>\BusinessCostOutModel::STATUS_ENABLE,
                "cost_money"=>array_sum(array_column($ruleList,"profit_money")),
            ];
            $saveData["total_balance"] = $businessPreModel->totalBalance($ruleList[0]["business_company_id"])-$saveData["cost_money"];//预存款账户余额
            if(!$businessCostOut->where(["tender_id"=>$ruleList[0]["tender_id"]])->save($saveData)){
                throw new \Exception("修改账单分期失败");
            }
            if($isTrans){
                $profitOrderModel->commit();
            }
            return true;
        } catch (Exception $ex) {
            if($isTrans){
                $profitOrderModel->rollback();
            }
            
            return false;
        }
    }


    /*
     * 获取贷款订单列表
     *      @param $tenderIds 订单ID  多个订单ID，隔开
     *      return array
     */
    public function getOrderList($tenderIds){
        $loanOrderObject = new LoanOrder($this->commonModel);
        $where = [
            "id"=>["in",$tenderIds],
            "status"=>["not in",[\LoanTenderModel::STATUS_APPLY]],
        ];
        return $loanOrderObject->getAllTenderOrderList(["where"=>$where], -1);
    }
    //获取channelRule对象
    public function getChannelRule(){
        if($this->channelRule===null){
            $this->channelRule = new ChannelRule($this->commonModel);
        }
        return $this->channelRule;
    }
    
    
    
}
