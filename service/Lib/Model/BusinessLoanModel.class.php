<?php
/*
 * 合作公司产品管理Model
 */

class BusinessLoanModel  extends Model{
    const STATUS_ENABLE = 1;//产品正常状态
	
    const STATUS_DISABLE = 2;//产品未启用状态
     //数据表名称
    protected $trueTableName = "business_loan";
    /*
     * 资金方产品数组
     */
    public $capitalLoan;
    /*
     * 传输的产品数据
     */
    public $postData;
    /*
     * 检查产品信息错误记录
     */
    public $capLoanError = "";
    /*
     * 自动验证
     */
    protected $_validate = [
        ["business_company_id","require","合作公司ID不能为空",self::MUST_VALIDATE],
        ["capital_loan_id","require","资金方产品ID不能为空",self::MUST_VALIDATE],
        ["name","require","产品名称不能为空",self::MUST_VALIDATE],
        ["min_loanmoney","require","最小借款金额不能为空",self::MUST_VALIDATE],
        ["max_loanmoney","require","最大借款金额不能为空",self::MUST_VALIDATE],
        ["loan_periode_id","require","借款期限ID不能为空",self::MUST_VALIDATE],
        ["periode_rate","require","借款利率不能为空",self::MUST_VALIDATE],
        ["late_periode_rate","require","逾期费率不能为空",self::MUST_VALIDATE],
        ["procedure_free","require","手续费不能为空",self::MUST_VALIDATE],
        ["plat_free_rate","require","平台管理费率不能为空",self::MUST_VALIDATE],
        
        ["min_loanmoney","is_numberic","最小借款金额不正确",self::MUST_VALIDATE,"function"],
        ["max_loanmoney","is_numberic","最大借款金额不正确",self::MUST_VALIDATE,"function"],
        ["periode_rate","is_numberic","借款利率不正确",self::MUST_VALIDATE,"function"],
        ["late_periode_rate","is_numberic","逾期费率不正确",self::MUST_VALIDATE,"function"],
        ["procedure_free","is_numberic","手续费不正确",self::MUST_VALIDATE,"function"],
        ["plat_free_rate","is_numberic","平台管理费率不正确",self::MUST_VALIDATE,"function"],
        
        ["business_company_id","checkBusinessCompanyId","合作公司ID不正确",self::MUST_VALIDATE,"callback"],
        ["capital_loan_id","checkCapitalLoanId","资金方产品ID不正确",self::MUST_VALIDATE,"callback"],
        ["loan_periode_id","checkLoanPeriodeId","借款期限ID不正确",self::MUST_VALIDATE,"callback"],
        
        ["min_loanmoney","checkCapitalLoan","最小借款金额不正确",self::MUST_VALIDATE,"callback",self::MODEL_BOTH,"loanmoney"],
        ["max_loanmoney","checkCapitalLoan","最大借款金额不正确",self::MUST_VALIDATE,"callback",self::MODEL_BOTH,"loanmoney"],
        ["periode_rate","checkCapitalLoan","借款利率不正确",self::MUST_VALIDATE,"callback",self::MODEL_BOTH,"periode_rate"],
        ["late_periode_rate","checkCapitalLoan","逾期费率不正确",self::MUST_VALIDATE,"callback",self::MODEL_BOTH,"late_periode_rate"],
        ["procedure_free","checkCapitalLoan","手续费不正确",self::MUST_VALIDATE,"callback",self::MODEL_BOTH,"procedure_free"],
        //["plat_free_rate","checkCapitalLoan","平台管理费不正确",self::MUST_VALIDATE,"callback",self::MODEL_BOTH,"plat_free"],
        
        
    ];
    //自动完成
    protected $_auto = [
       ["business_loan_number","createBusinessLoanNumber",self::MODEL_INSERT,"callback"],
    ];
    
    /*
     * 产品必须--在资金方产品范围内
     */
    public function checkCapitalLoan($value,$key){
        $capLoanInfo = $this->capitalLoan;
        if($key=="loanmoney"){//借款金额
            if(!($value>=$capLoanInfo["min_loanmoney"] && $value<=$capLoanInfo["max_loanmoney"])){
                $this->capLoanError = "借款金额必须在{$capLoanInfo["min_loanmoney"]}-{$capLoanInfo["max_loanmoney"]}之间";
                return false;
            }
        }elseif($key=="periode_rate"){
            if($value<$capLoanInfo["periode_rate"]){
                $this->capLoanError = "借款利率不得低于{$capLoanInfo["periode_rate"]}%";
                return false;
            }
        }elseif($key=="late_periode_rate"){
            if($value<$capLoanInfo["late_periode_rate"]){
                $this->capLoanError = "逾期费率不得低于{$capLoanInfo["late_periode_rate"]}%";
                return false;
            }
        }elseif($key=="procedure_free"){
            if($value<$capLoanInfo["procedure_free"]){
                $this->capLoanError = "手续费不得低于{$capLoanInfo["procedure_free"]}元";
                return false;
            }
        }elseif($key=="plat_free"){
            if($value<$capLoanInfo["plat_free_rate"]){
                $this->capLoanError = "平台管理费不得低于{$capLoanInfo["plat_free_rate"]}%";
                return false;
            }
        }
        return true;
    }
    //合作公司ID
    public function checkBusinessCompanyId($id){
        return (boolean)(new BusinessCompanyModel())->getBusinessCompany($id);
    }
    //资金产品ID
    public function checkCapitalLoanId($id){
        if(false==$this->capitalLoan){
            $this->capitalLoan = (new CapitalLoanModel())->where(["id"=>$id])->find();
        }
        return (boolean)$this->capitalLoan;
    }
    //借款期限ID
    public function checkLoanPeriodeId($id){
        $model = new LoanPeriodeModel();
        $loanPeriode = $model->where(["id"=>$id])->find();
        if(false==$loanPeriode)return false;
        $minPeriode = str_replace($model->loanPeriodeUnit,$model->loanPeriodeUnitE,$this->capitalLoan["min_loan_periode"]);
        $maxPeriode = str_replace($model->loanPeriodeUnit,$model->loanPeriodeUnitE,$this->capitalLoan["max_loan_periode"]);
        $currentPeriode = str_replace($model->loanPeriodeUnit,$model->loanPeriodeUnitE,$loanPeriode["periode"].$loanPeriode["unit"]);
        if(!(strtotime("+{$minPeriode}")<=strtotime("+{$currentPeriode}") && strtotime("+{$currentPeriode}")<=strtotime("+{$maxPeriode}"))){
            $this->capLoanError = "借款期限必须在{$this->capitalLoan["min_loan_periode"]}-{$this->capitalLoan["max_loan_periode"]}之间";
            return false;
        }
        return true;
    }
    
     /*
     * 创建编号
     */
    public function createBusinessLoanNumber($number = null){
        if($number!==null && !$this->where(["business_loan_number"=>$number])->find())return $number;
        for($i=0;$i<10;$i++){
            $number = "Loan".time().rand(111,999).rand(111,999);
            if(false==$this->where(["business_loan_number"=>$number])->find())break;
        }
        return $number;
    }
}
