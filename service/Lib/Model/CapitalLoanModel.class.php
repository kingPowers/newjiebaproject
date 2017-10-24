<?php
/*
    资金方产品管理model
 */

class CapitalLoanModel  extends Model{
    //数据表名称
    protected $trueTableName = "capital_loan";
    
    public $postData;
    /*
     * 自动验证
     */
    protected $_validate = [
        ["capital_company_id","require","资金方ID不能为空",self::MUST_VALIDATE],
        ["name","require","产品名称不能为空",self::MUST_VALIDATE],
        ["min_loanmoney","require","最小借款金额不能为空",self::MUST_VALIDATE],
        ["max_loanmoney","require","最大借款金额不能为空",self::MUST_VALIDATE],
        ["min_loanmoney","is_numeric","最小借款金额不正确",self::MUST_VALIDATE,"function"],
        ["max_loanmoney","is_numeric","最大借款金额不正确",self::MUST_VALIDATE,"function"],
        ["loan_repayment","require","还款方式不能为空",self::MUST_VALIDATE],
        ["min_loan_periode","require","借款期限下限不能为空",self::MUST_VALIDATE],
        ["max_loan_periode","require","借款期限上限不能为空",self::MUST_VALIDATE],
        ["periode_rate","require","利率不能为空",self::MUST_VALIDATE],
        ["periode_rate","is_numeric","利率不正确",self::MUST_VALIDATE,"function"],
        //["deposit_percent","require","保证金比例不能为空",self::MUST_VALIDATE],
        ["is_sesame_credit","require","是否需要芝麻信用分不能为空",self::MUST_VALIDATE],
        
        ["capital_company_id","checkCapitalCompany","资金方ID不正确",self::MODEL_BOTH,"callback"],
        ["sesame_credit_score","checkCreditScore","芝麻信用分通过标准分数不能为空",self::MODEL_BOTH,"callback"],
        
    ];
    //自动完成
    protected $_auto = [
        ["min_loan_periode","checkLoanPeriode",self::MODEL_INSERT,"callback"],
        ["max_loan_periode","checkLoanPeriode",self::MODEL_INSERT,"callback"],
    ];
    /*
     * 获取资金方公司信息
     */
    public function checkCapitalCompany($id){
        $capitalCompanyModel = new \CapitalCompanyModel();
        return $capitalCompanyModel->getCapitalCompany($id);
    }
    
    /*
     * 借款期限
     *      eg.$loanPeriodeName=7  return 7天
     *         $loanPeriodeName=7天 return 7天
     *         $loanPeriodeName=3时 return 3天
     */
    public function checkLoanPeriode($loanPeriodeName){
        $periodeModel = new LoanPeriodeModel();
        $arrUnit = $periodeModel->loanPeriodeUnit;
        $length = mb_strlen($loanPeriodeName,"utf-8");
        if($length>0 && !in_array(mb_substr($loanPeriodeName, $length-1,1),$arrUnit)){
            return intval($loanPeriodeName).$arrUnit[0];
        }
        return $loanPeriodeName;
    }
    /*
     * 检查芝麻信用分
     *      当开启芝麻信用分的时候，芝麻信用分不能为空
     */
    public function checkCreditScore($score){
        if(isset($this->postData["is_sesame_credit"]) && 1==intval($this->postData["is_sesame_credit"])){
            return empty($score)?false:true;
        }
        return true;
    }
   
}
