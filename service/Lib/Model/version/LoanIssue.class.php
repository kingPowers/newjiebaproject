<?php
/*
 * 订单计算
 *  
 *      产品ID必须
 *      
 */
namespace version;

class LoanIssue extends LoanConfig{
    //put your code here
    public $loanId;//产品ID
    
    public $money;//申请金额
    
    public $tenderId;//tenderID
    
    private $_businessLoan;//产品信息
    
    //初始化
    public function init() {
        parent::init();
        if($this->loanId===null)$this->error("产品ID为空");
        $busLoanModel = new \BusinessLoanModel();
        $this->_businessLoan = $busLoanModel->where(["id"=>$this->loanId])->find();
        if(false==$this->_businessLoan)$this->error("产品ID不正确");
        $this->money = round($this->money,2);
    }
    //申请订单数据，tender数据
    public function tender(){
        $tenderModel = new \LoanTenderModel();
        $feeList = $this->calculateFee();
        $tender = [];
        $tender["business_loan_id"] = $this->loanId;//产品ID
        $tender["money"] = round($this->money,2);//借款金额
        $tender["status"] = $tenderModel::STATUS_APPLY;//借款状态
        $tender["total_fee"] = $feeList["total"];//总费用
        $tender["pay_money"] = $tender["money"]-$tender["total_fee"];//打款金额
        return $tender;
    }

    /*
     * 还款账单
     *      返回二维数组
     */
    public function allotList(){
        $repaymentList = [];
        if($this->_businessLoan["loan_repayment_id"]==2){//本息到付-生成一个账单
            $feeList = $this->calculateFee();
            $allot = [];
            $allot["procedure_free"] = $feeList["procedure_free"];//手续费
            $allot["periode_fee"] = $feeList["periode_fee"];//借款费用
            $allot["plat_free"] = $feeList["plat_free"];//平台管理费
            $allot["repayment_money"] = $this->money;//还款金额
            $allot["allot_sn"] = $this->createAllotSn();//还款编号
            $repaymentList[0] = $allot;
        }elseif($this->businessLoan["loan_repayment_id"]==1){
            //1：付息还本，按照月还款
            
        }
        return $repaymentList;
    }
    
    /*
     * 费用计算
     * @return array
     *      
     *         目前计算公式仅仅支持 【本息到付】
     *          
     *          eg.[
     *              "periode_fee"=>0,//借款费用
                    "procedure_free"=>0,//手续费
                    "plat_free"=>0,//平台管理费
     *              "total"=>0,//统计费用
     *              ]
     *          
     */
    public function calculateFee(){
        $loanPeriodeModel = new \LoanPeriodeModel();
        $periodeInfo = $loanPeriodeModel->where(["id"=>$this->_businessLoan["loan_periode_id"]])->find();
        $loanDays = $periodeInfo["periode"]*$loanPeriodeModel->changeUnitList[$periodeInfo["unit"]];//转换为天数
        $feeList = [];
        $feeList["procedure_free"] = $this->_businessLoan["procedure_free"];//手续费
        $feeList["periode_fee"] = $this->_businessLoan["periode_rate"]*$loanDays*$this->money/100;//借款费用
        $feeList["plat_free"] = $this->_businessLoan["plat_free_rate"]*$this->money/100;//平台管理费
        $feeList["total"] = array_sum($feeList);//费用统计
        $feeList["pay_money"] = round($this->money-$feeList["total"],2);//到账金额
        $feeList["periode_rate"] = $this->_businessLoan["periode_rate"];//借款利率
        $feeList["plat_free_rate"] = $this->_businessLoan["plat_free_rate"];//平台管理费率
        $feeList["start_time"] = date("Y-m-d",strtotime("+1 days"))." 00:00:00";//开始时间
        $feeList["end_time"] = date('Y-m-d', strtotime("+{$loanDays} days", strtotime($feeList["start_time"])))." 23:59:59";//结束时间(应还日期)
        $feeList["repayment_type"] = "一次性还款";
        return $feeList;
    }
    
    /*
     * 逾期费用计算
     * @return array
     *         目前计算公式仅仅支持 【本息到付】
     *          
     *          eg.[
                    "late_fee"=>0,//逾期费用
     *              "late_days"=>0,//逾期天数
     *              ]
     *          
     */
    public function calculateLateFee($tenderId = null){
        $this->tenderId = $tenderId?$tenderId:$this->tenderId;
        if(false==$this->tenderId)$this->error("tenderID为空");
        $lateList = [];
        if($this->_businessLoan["loan_repayment_id"]==2){//本息到付
            $allot = M("loan_allot")->where(["tender_id"=>$this->tenderId])->find();
            $lateDays = ceil((time()-strtotime($allot['end_time']))/86400);
            $lateList["late_days"] = $lateDays>0?$lateDays:0;//逾期天数
            $lateList["late_fee"] = round($lateList["late_days"]*$this->_businessLoan["late_periode_rate"]*$allot["repayment_money"]/100,2);//逾期费用
        }
         return $lateList;
    }
   
    
     /*
     * 创建编号
     */
    private function createAllotSn($number = null){
        $model = M("loan_allot");
        if($number!==null && !$model->where(["allot_sn"=>$number])->find())return $number;
        for($i=0;$i<10;$i++){
            $number = "all".time().rand(111,999).rand(111,999);
            if(false==$model->where(["allot_sn"=>$number])->find())break;
        }
        return $number;
    }

}
