<?php
/*
 * 产品基类model
 * 
 */
class LoanConfigModel extends Model{
    //数据表名称
    protected $trueTableName = "loan_config";
    /*
     * 自动验证
     */
    protected $_validate = [
        ["loanmoney","require","借款金额",self::MUST_VALIDATE],
        ["loan_periode_ids","require","借款期限不能为空",self::MUST_VALIDATE],
        ["periode_rate","require","借款利率不能为空",self::MUST_VALIDATE],
        ["late_periode_rate","require","借款逾期利率不能为空",self::MUST_VALIDATE],
        ["procedure_free","require","手续费不能为空",self::MUST_VALIDATE],
        ["plat_free","require","平台管理费不能为空",self::MUST_VALIDATE],
        ["extend_free","require","其他费用不能为空",self::VALUE_VAILIDATE],
        
        ["loanmoney","checkScope","借款金额参数不正确",self::MODEL_BOTH],
        ["loan_periode_ids","checkLoanperiodeids","借款期限参数不正确",self::MODEL_BOTH],
        ["periode_rate","checkScope","借款利率参数不正确",self::MODEL_BOTH],
        ["late_periode_rate","checkScope","借款逾期利率参数不正确",self::MODEL_BOTH],
        ["procedure_free","checkScope","手续费参数不正确",self::MODEL_BOTH],
        ["plat_free","checkScope","平台管理费参数不正确",self::MODEL_BOTH],
        ["extend_free","checkScope","其他费用参数不正确",self::MODEL_BOTH],
        
    ];
    //自动完成
    protected $_auto = [
        ["loan_periode_ids","checkLoanperiodeids",self::MODEL_BOTH,"callback"],
    ];
    //范围检测
    public function checkScope($scopeValue){
        list($min,$max) = explode("-",$scopeValue);
        if($min===null || $max===null || $min>$max || $min<0){
            return false;
        }
        return true;
    }
    //借款期限
    public function checkLoanperiodeids($loanPeriodes){
        $loanPeriodeModel = new LoanPeriodeModel();
        $arrPeriode = explode(",",$loanPeriodes);$ids = [];
        foreach($arrPeriode as $val){
            if(false!=($periode = $loanPeriodeModel->where("concat(periode,unit)='{$val}'")->find())){
                $ids[] = $periode["id"];
            }else{
                return false;
            }
            
        }
        return implode(",",$ids);
    }
    
}
